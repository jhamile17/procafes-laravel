<?php

namespace App\Services\Dashboard;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardService
{
    /**
     * Obtiene toda la información del dashboard.
     */
    public function getDashboardData(Request $request): array
    {
        Carbon::setLocale('es');

        $filters = $this->getFilters($request);

        $labels = $this->getChartLabels(
            $filters['year'],
            $filters['month']
        );

        $revenue = $this->getRevenue(
            $filters['year'],
            $filters['month']
        );

        $stats = $this->getStats(
            $filters['year'],
            $filters['month']
        );

        $categories = $this->getCategories();

        $chips = $this->getCategoryChips();

        $best = $this->getTopProducts();

        $stock = $this->getLowStockProducts();

        $activities = $this->getRecentActivity();

        $dailySummary = $this->getDailySummary();

        return array_merge($filters, [

            'labels' => $labels,

            'revenue' => $revenue,

            'stats' => $stats,

            'categories' => $categories,

            'chips' => $chips,

            'best' => $best,

            'stock' => $stock,

            'activities' => $activities,

            'dailySummary' => $dailySummary,

        ]);
    }
    
    /**
     * Obtiene los filtros enviados desde la vista.
     */
    private function getFilters(Request $request): array
    {
        $currentYear = now()->year;

        $year = (int) $request->get('year', $currentYear);

        $month = $request->get('month');

        return [

            'year' => $year,

            'month' => $month,

            'categoryId' => (int) $request->query('category_id', 0),

            // Mostrar un rango de años aunque no existan ventas
            'availableYears' => collect(
                range($currentYear - 2, $currentYear + 2)
            )->sortDesc()->values(),

        ];
    }

    /**
     * Consulta base para las ventas válidas del dashboard.
     */
    private function dashboardSalesQuery()
    {
        return Order::query()
            ->whereHas('estadoPedido', function ($query) {
                $query->whereIn('codigo', [
                    \App\Models\EstadoPedido::CONFIRMADO,
                    \App\Models\EstadoPedido::ENTREGADO,
                ]);
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Gráfico
    |--------------------------------------------------------------------------
    */

    private function getChartLabels(int $year, ?int $month): array
    {
        if (empty($month)) {

            return collect(range(1, 12))
                ->map(function ($m) {

                    return ucfirst(
                        Carbon::create()
                            ->month($m)
                            ->translatedFormat('M')
                    );

                })
                ->toArray();
        }

        $days = Carbon::create($year, $month)->daysInMonth;

        return collect(range(1, $days))->toArray();
    }

    private function getRevenue(int $year, ?int $month): array
    {
        $revenue = [];

        /*
        |--------------------------------------------------------------------------
        | Vista anual
        |--------------------------------------------------------------------------
        */

        if (empty($month)) {

            for ($m = 1; $m <= 12; $m++) {

                $revenue[] = $this->dashboardSalesQuery()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum('total_price');

            }

            return $revenue;
        }

        /*
        |--------------------------------------------------------------------------
        | Vista mensual
        |--------------------------------------------------------------------------
        */

        $days = Carbon::create($year, $month)->daysInMonth;

        for ($d = 1; $d <= $days; $d++) {

            $revenue[] = $this->dashboardSalesQuery()
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', $d)
                ->sum('total_price');
        }

        return $revenue;
    }

    /*
    |--------------------------------------------------------------------------
    | Estadísticas
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene las estadísticas principales del dashboard.
     */
    private function getStats(int $year, ?int $month): array
    {
        $query = $this->dashboardSalesQuery()
            ->whereYear('created_at', $year);

        if (!empty($month)) {
            $query->whereMonth('created_at', $month);
        }

        return [

            /*
            |--------------------------------------------------------------------------
            | Estadísticas del período seleccionado
            |--------------------------------------------------------------------------
            */

            'revenue' => (clone $query)->sum('total_price'),

            'orders' => (clone $query)->count(),

            /*
            |--------------------------------------------------------------------------
            | Totales generales del sistema
            |--------------------------------------------------------------------------
            */

            'products' => Product::count(),

            'customers' => User::whereHas('role', function ($query) {

                $query->where('codigo', 'CUSTOMER');

            })->count(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Categorías
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene todas las categorías.
     */
    private function getCategories()
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Obtiene las categorías mostradas como chips.
     */
    private function getCategoryChips(): array
    {
        return Category::orderBy('name')
            ->take(10)
            ->get()
            ->map(function ($category) {

                return [

                    'i' => 'bi-tag',

                    't' => $category->name,

                ];

            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Productos
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene los productos más vendidos.
     */
    private function getTopProducts(): array
    {
        if (
            !Schema::hasTable('order_items') ||
            !Schema::hasColumn('order_items', 'quantity')
        ) {
            return [];
        }

        return DB::table('order_items as oi')
            ->join('products as p', 'p.id', '=', 'oi.product_id')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('estados_pedido as ep', 'ep.id', '=', 'o.estado_pedido_id')
            ->whereIn('ep.codigo', [
                \App\Models\EstadoPedido::CONFIRMADO,
                \App\Models\EstadoPedido::ENTREGADO,
            ])
            ->select(
                'p.id',
                'p.name',
                'p.image',
                DB::raw('SUM(oi.quantity) as qty_sold')
            )
            ->groupBy(
                'p.id',
                'p.name',
                'p.image'
            )
            ->orderByDesc('qty_sold')
            ->limit(5)
            ->get()
            ->map(function ($row) {

                $image = $this->getImageUrl($row->image);

                return [

                    'id' => $row->id,

                    'name' => $row->name,

                    'orders' => (int) $row->qty_sold,

                    'total' => 0,

                    'img' => $image,

                ];

            })
            ->toArray();
    }

    /**
     * Devuelve la URL pública de una imagen.
     */
    private function getImageUrl(?string $image): string
    {
        if (empty($image)) {
            return asset('images/no-image.png');
        }

        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }

        if (Storage::disk('public')->exists($image)) {
            return Storage::url($image);
        }

        return asset('images/no-image.png');
    }

    /**
     * Obtiene los productos con stock bajo.
     */
    private function getLowStockProducts(): array
    {
        return Product::stockBajo()
            ->orderBy('stock')
            ->limit(5)
            ->get()
            ->map(function ($product) {

                return [

                    'name'  => $product->name,

                    'stock' => $product->stock,

                    'img'   => $this->getImageUrl($product->image),

                ];

            })
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Actividad
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene la actividad reciente del sistema.
     */
    private function getRecentActivity(): array
    {
        return Order::with(['user', 'estadoPedido'])
            ->whereHas('estadoPedido', function ($query) {
                $query->whereIn('codigo', [
                    \App\Models\EstadoPedido::CONFIRMADO,
                    \App\Models\EstadoPedido::ENTREGADO,
                ]);
            })
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($order) {

                return [

                    'number' => $order->numero_pedido,

                    'customer' => optional($order->user)->name ?? 'Cliente',

                    'total' => $order->total_price,

                    'date' => $order->created_at?->format('d/m/Y H:i'),

                ];

            })
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Resumen del día
    |--------------------------------------------------------------------------
    */

    /****
     * Obtiene el resumen del día.
     */
    private function getDailySummary(): array
    {
        $today = now()->toDateString();

        $salesToday = $this->dashboardSalesQuery()
            ->whereDate('created_at', $today)
            ->sum('total_price');

        $ordersToday = $this->dashboardSalesQuery()
            ->whereDate('created_at', $today)
            ->count();

        $customersToday = User::whereDate('created_at', $today)
            ->count();

        $productsSoldToday = 0;

        if (
            Schema::hasTable('order_items') &&
            Schema::hasColumn('order_items', 'quantity')
        ) {
            $productsSoldToday = DB::table('order_items as oi')
                ->join('orders as o', 'o.id', '=', 'oi.order_id')
                ->join('estados_pedido as ep', 'ep.id', '=', 'o.estado_pedido_id')
                ->whereDate('o.created_at', $today)
                ->whereIn('ep.codigo', [
                    \App\Models\EstadoPedido::CONFIRMADO,
                    \App\Models\EstadoPedido::ENTREGADO,
                ])
                ->sum('oi.quantity');
        }

        return [

            'sales' => $salesToday,

            'orders' => $ordersToday,

            'customers' => $customersToday,

            'productsSold' => $productsSoldToday,

        ];
    }
    
}
