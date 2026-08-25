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
     * =========================================================
     * DATOS COMPLETOS DEL DASHBOARD
     * =========================================================
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

        $topProducts = $this->getTopProducts();

        $lowStock = $this->getLowStockProducts();

        $activities = $this->getRecentActivity();

        $dailySummary = $this->getDailySummary();

        return array_merge(
            $filters,
            [

                'labels' => $labels,

                'revenue' => $revenue,

                'stats' => $stats,

                'categories' => $categories,

                'chips' => $chips,

                'topProducts' => $topProducts,

                'lowStock' => $lowStock,

                'activities' => $activities,

                'dailySummary' => $dailySummary,

            ]
        );
    }


    /**
     * =========================================================
     * FILTROS
     * =========================================================
     */
    private function getFilters(Request $request): array
    {
        $currentYear = now()->year;

        $year = (int) $request->query(
            'year',
            $currentYear
        );

        $month = $request->query('month');

        $month = !empty($month)
            ? (int) $month
            : null;

        return [

            'year' => $year,

            'month' => $month,

            'categoryId' => (int) $request->query(
                'category_id',
                0
            ),

            'availableYears' => collect(
                range(
                    $currentYear - 2,
                    $currentYear + 2
                )
            )
                ->sortDesc()
                ->values()
                ->all(),

        ];
    }


    /**
     * =========================================================
     * CONSULTA BASE DE VENTAS
     * =========================================================
     *
     * Se utilizan los estados reales del sistema:
     *
     * CONFIRMADO
     * ENTREGADO
     *
     * No usamos orders.status.
     */
    private function dashboardSalesQuery()
    {
        return Order::query()
            ->whereHas(
                'estadoPedido',
                function ($query) {

                    $query->whereIn(
                        'codigo',
                        [
                            \App\Models\EstadoPedido::CONFIRMADO,
                            \App\Models\EstadoPedido::ENTREGADO,
                        ]
                    );

                }
            );
    }


    /**
     * =========================================================
     * GRÁFICO - ETIQUETAS
     * =========================================================
     */
    private function getChartLabels(
        int $year,
        ?int $month
    ): array {

        /*
         * TODO EL AÑO
         */
        if (empty($month)) {

            $labels = [];

            for ($m = 1; $m <= 12; $m++) {

                $labels[] = ucfirst(
                    Carbon::create(
                        $year,
                        $m,
                        1
                    )->translatedFormat('M')
                );
            }

            return $labels;
        }


        /*
         * UN MES
         */
        $days = Carbon::create(
            $year,
            $month,
            1
        )->daysInMonth;

        return collect(
            range(1, $days)
        )->map(
            fn ($day) => (string) $day
        )->toArray();
    }


    /**
     * =========================================================
     * GRÁFICO - INGRESOS
     * =========================================================
     */
    private function getRevenue(
        int $year,
        ?int $month
    ): array {

        $revenue = [];


        /*
         * TODO EL AÑO
         */
        if (empty($month)) {

            for ($m = 1; $m <= 12; $m++) {

                $revenue[] = (float) $this
                    ->dashboardSalesQuery()
                    ->whereYear(
                        'created_at',
                        $year
                    )
                    ->whereMonth(
                        'created_at',
                        $m
                    )
                    ->sum('total_price');
            }

            return $revenue;
        }


        /*
         * UN MES
         */
        $days = Carbon::create(
            $year,
            $month,
            1
        )->daysInMonth;


        for ($day = 1; $day <= $days; $day++) {

            $revenue[] = (float) $this
                ->dashboardSalesQuery()
                ->whereYear(
                    'created_at',
                    $year
                )
                ->whereMonth(
                    'created_at',
                    $month
                )
                ->whereDay(
                    'created_at',
                    $day
                )
                ->sum('total_price');
        }


        return $revenue;
    }


    /**
     * =========================================================
     * ESTADÍSTICAS PRINCIPALES
     * =========================================================
     */
    private function getStats(
        int $year,
        ?int $month
    ): array {

        $query = $this
            ->dashboardSalesQuery()
            ->whereYear(
                'created_at',
                $year
            );


        if (!empty($month)) {

            $query->whereMonth(
                'created_at',
                $month
            );
        }


        return [

            /*
             * Ventas del período
             */
            'revenue' => (float) (clone $query)
                ->sum('total_price'),


            /*
             * Pedidos del período
             */
            'orders' => (int) (clone $query)
                ->count(),


            /*
             * Productos generales
             */
            'products' => (int) Product::count(),


            /*
             * Clientes
             */
            'customers' => (int) User::whereHas(
                'role',
                function ($query) {

                    $query->where(
                        'codigo',
                        'CUSTOMER'
                    );

                }
            )->count(),

        ];
    }


    /**
     * =========================================================
     * CATEGORÍAS
     * =========================================================
     */
    private function getCategories()
    {
        return Category::query()
            ->orderBy('name')
            ->get();
    }


    /**
     * Categorías para chips.
     */
    private function getCategoryChips(): array
    {
        return Category::query()
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map(
                function ($category) {

                    return [

                        'i' => 'bi-tag',

                        't' => $category->name,

                    ];
                }
            )
            ->values()
            ->all();
    }


    /**
     * =========================================================
     * PRODUCTOS MÁS VENDIDOS
     * =========================================================
     */
    private function getTopProducts(): array
    {
        /*
         * Verificamos que exista order_items.
         */
        if (!Schema::hasTable('order_items')) {
            return [];
        }


        /*
         * Verificamos quantity.
         */
        if (!Schema::hasColumn(
            'order_items',
            'quantity'
        )) {
            return [];
        }


        /*
         * Verificamos product_id.
         */
        if (!Schema::hasColumn(
            'order_items',
            'product_id'
        )) {
            return [];
        }


        return DB::table('order_items as oi')

            ->join(
                'products as p',
                'p.id',
                '=',
                'oi.product_id'
            )

            ->join(
                'orders as o',
                'o.id',
                '=',
                'oi.order_id'
            )

            ->join(
                'estados_pedido as ep',
                'ep.id',
                '=',
                'o.estado_pedido_id'
            )

            ->whereIn(
                'ep.codigo',
                [
                    \App\Models\EstadoPedido::CONFIRMADO,
                    \App\Models\EstadoPedido::ENTREGADO,
                ]
            )

            ->select(

                'p.id',

                'p.name',

                'p.image',

                DB::raw(
                    'SUM(oi.quantity) as qty_sold'
                )

            )

            ->groupBy(

                'p.id',

                'p.name',

                'p.image'

            )

            ->orderByDesc(
                'qty_sold'
            )

            ->limit(5)

            ->get()

            ->map(
                function ($row) {

                    return (object) [

                        'id' => $row->id,

                        'name' => $row->name,

                        'image' => $this->getImageUrl(
                            $row->image
                        ),

                        'qty_sold' => (int) $row->qty_sold,

                    ];
                }
            )

            ->toArray();
    }

    /**
     * =========================================================
     * IMAGEN DEL PRODUCTO
     * =========================================================
     */
    private function getImageUrl(?string $image): string
    {
        if (empty($image)) {
            return asset('images/no-image.png');
        }

        /*
        * Si ya es una URL completa
        */
        if (
            Str::startsWith(
                $image,
                [
                    'http://',
                    'https://'
                ]
            )
        ) {
            return $image;
        }

        /*
        * Si existe en storage/app/public
        */
        if (Storage::disk('public')->exists($image)) {
            return Storage::url($image);
        }

        /*
        * Imagen por defecto
        */
        return asset('images/no-image.png');
    }


    /**
     * =========================================================
     * STOCK BAJO
     * =========================================================
     */
    private function getLowStockProducts(): array
    {
        /*
         * Usamos el scope existente del modelo Product.
         */
        return Product::query()
            ->stockBajo()
            ->orderBy('stock')
            ->limit(5)
            ->get()
            ->map(
                function ($product) {

                    return (object) [

                        'id' => $product->id,

                        'name' => $product->name,

                        'stock' => (int) $product->stock,

                        'stock_minimo' => (int) (
                            $product->stock_minimo ?? 10
                        ),

                        'image' => $this->getImageUrl(
                            $product->image
                        ),

                    ];
                }
            )
            ->toArray();
    }


    /**
     * =========================================================
     * ACTIVIDAD RECIENTE
     * =========================================================
     *
     * Muestra los últimos 5 pedidos registrados en el sistema,
     * independientemente del estado del pedido.
     *
     * IMPORTANTE:
     * Esta sección es diferente al cálculo de ventas.
     *
     * - Dashboard de ventas:
     *   Solo CONFIRMADO / ENTREGADO.
     *
     * - Actividad reciente:
     *   Muestra cualquier pedido reciente.
     */
    private function getRecentActivity(): array
    {
        return Order::query()

            ->with([
                'user',
                'estadoPedido'
            ])

            /*
            * IMPORTANTE:
            *
            * NO filtramos por estado aquí.
            *
            * La actividad reciente debe mostrar también:
            *
            * PENDIENTE
            * CONFIRMADO
            * EN PREPARACIÓN
            * ENTREGADO
            * etc.
            */

            ->orderByDesc('created_at')

            ->limit(5)

            ->get()

            ->map(function ($order) {

                /*
                * Nombre del cliente
                */
                $customer = optional(
                    $order->user
                )->name ?? 'Cliente';


                /*
                * Número del pedido
                */
                $orderNumber =
                    $order->numero_pedido
                    ?? $order->id;


                /*
                * Estado del pedido
                */
                $status = optional(
                    $order->estadoPedido
                )->nombre ?? null;


                /*
                * Descripción
                */
                $description =
                    $customer .
                    ' realizó un pedido por S/ ' .
                    number_format(
                        (float) $order->total_price,
                        2
                    );


                /*
                * Agregar estado si existe
                */
                if (!empty($status)) {

                    $description .=
                        ' · ' . $status;

                }


                return (object) [

                    'title' =>
                        'Pedido #' .
                        $orderNumber,


                    'description' =>
                        $description,


                    'created_at' =>
                        $order->created_at,


                    'order_id' =>
                        $order->id,


                    'status' =>
                        $status,

                ];

            })

            ->values()

            ->toArray();
    }


    /**
     * =========================================================
     * RESUMEN DEL DÍA
     * =========================================================
     */
    private function getDailySummary(): array
    {
        $today = now()->toDateString();


        /*
         * Ventas de hoy
         */
        $salesToday = $this
            ->dashboardSalesQuery()
            ->whereDate(
                'created_at',
                $today
            )
            ->sum('total_price');


        /*
         * Pedidos de hoy
         */
        $ordersToday = $this
            ->dashboardSalesQuery()
            ->whereDate(
                'created_at',
                $today
            )
            ->count();


        /*
         * Clientes registrados hoy
         */
        $customersToday = User::query()
            ->whereDate(
                'created_at',
                $today
            )
            ->count();


        /*
         * Productos vendidos hoy
         */
        $productsSoldToday = 0;


        if (
            Schema::hasTable('order_items') &&
            Schema::hasColumn(
                'order_items',
                'quantity'
            ) &&
            Schema::hasColumn(
                'order_items',
                'product_id'
            )
        ) {

            $productsSoldToday = DB::table(
                'order_items as oi'
            )

                ->join(
                    'orders as o',
                    'o.id',
                    '=',
                    'oi.order_id'
                )

                ->join(
                    'estados_pedido as ep',
                    'ep.id',
                    '=',
                    'o.estado_pedido_id'
                )

                ->whereDate(
                    'o.created_at',
                    $today
                )

                ->whereIn(
                    'ep.codigo',
                    [
                        \App\Models\EstadoPedido::CONFIRMADO,
                        \App\Models\EstadoPedido::ENTREGADO,
                    ]
                )

                ->sum('oi.quantity');
        }


        return [

            'sales' => (float) $salesToday,

            'orders' => (int) $ordersToday,

            'customers' => (int) $customersToday,

            'productsSold' => (int) $productsSoldToday,

        ];
    }
}