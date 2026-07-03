<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('es');

        $categoryId = (int) $request->query('category_id', 0);

        /*
        |--------------------------------------------------------------------------
        | Últimos 12 meses
        |--------------------------------------------------------------------------
        */

        $now = Carbon::now()->startOfMonth();
        $start = (clone $now)->subMonths(11);
        $end = (clone $now)->addMonth();

        $months = collect(range(0, 11))
            ->map(fn($i) => (clone $start)->addMonths($i));

        $labels = $months
            ->map(fn($m) => ucfirst($m->isoFormat('MMM')) . ' ' . $m->year)
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Ingresos por mes
        |--------------------------------------------------------------------------
        */

        $revenue = array_fill(0, 12, 0);

        if (Schema::hasColumn('orders', 'total_price')) {

            $query = DB::table('orders')
                ->selectRaw("
                    DATE_FORMAT(created_at,'%Y-%m') as ym,
                    SUM(total_price) as total
                ")
                ->whereBetween('created_at', [$start, $end]);

            $rows = $query
                ->groupBy('ym')
                ->pluck('total', 'ym');

            $revenue = $months->map(function ($m) use ($rows) {

                $key = $m->format('Y-m');

                return (float) ($rows[$key] ?? 0);

            })->values()->all();
        }

        /*
        |--------------------------------------------------------------------------
        | Estadísticas
        |--------------------------------------------------------------------------
        */

        $stats = [

            'revenue' => Order::sum('total_price'),

            'orders' => Order::count(),

            'products' => Product::count(),

            'customers' => User::count(),

        ];

        /*
        |--------------------------------------------------------------------------
        | Categorías
        |--------------------------------------------------------------------------
        */

        $categories = Category::orderBy('name')->get();

        $chips = $categories
            ->take(10)
            ->map(function ($category) {

                return [

                    'i' => 'bi-tag',

                    't' => $category->name,

                ];

            })
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Productos más vendidos
        |--------------------------------------------------------------------------
        */

        $best = [];

        if (
            Schema::hasTable('order_items') &&
            Schema::hasColumn('order_items', 'quantity')
        ) {

            $best = DB::table('order_items as oi')
                ->join('products as p', 'p.id', '=', 'oi.product_id')
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

                    $image = $row->image;

                    if (
                        $image &&
                        !Str::startsWith($image, ['http://', 'https://'])
                    ) {

                        $image = Storage::disk('public')->exists($image)

                            ? Storage::url($image)

                            : asset('images/no-image.png');

                    }

                    return [

                        'id' => $row->id,

                        'name' => $row->name,

                        'orders' => (int) $row->qty_sold,

                        'total' => 0,

                        'img' => $image ?: asset('images/no-image.png'),

                    ];

                })
                ->toArray();
        }

        /*
        |--------------------------------------------------------------------------
        | Vista
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', [

            'labels' => $labels,

            'revenue' => $revenue,

            'stats' => $stats,

            'chips' => $chips,

            'best' => $best,

            'categories' => $categories,

            'categoryId' => $categoryId,

        ]);
    }
}