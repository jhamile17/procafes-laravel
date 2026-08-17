<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;
use App\Exports\ArrayExports;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PANEL PRINCIPAL
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('admin.reports.index');
    }


    /*
    |--------------------------------------------------------------------------
    | UTILIDADES
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene la columna real del total de la orden.
     */
    protected function orderTotalField(): ?string
    {
        if (Schema::hasColumn('orders', 'total_price')) {
            return 'total_price';
        }

        if (Schema::hasColumn('orders', 'total')) {
            return 'total';
        }

        return null;
    }


    /**
     * Obtiene la FK de categoría de products.
     *
     * En tu proyecto normalmente es categories_id.
     */
    protected function productCategoryField(): ?string
    {
        if (Schema::hasColumn('products', 'categories_id')) {
            return 'categories_id';
        }

        if (Schema::hasColumn('products', 'category_id')) {
            return 'category_id';
        }

        return null;
    }


    /**
     * Obtiene la FK del producto en order_items.
     */
    protected function orderItemProductField(): ?string
    {
        if (Schema::hasColumn('order_items', 'product_id')) {
            return 'product_id';
        }

        if (Schema::hasColumn('order_items', 'products_id')) {
            return 'products_id';
        }

        return null;
    }


    /**
     * Obtiene el campo de precio de order_items.
     */
    protected function orderItemPriceField(): ?string
    {
        if (Schema::hasColumn('order_items', 'unit_price')) {
            return 'unit_price';
        }

        if (Schema::hasColumn('order_items', 'price')) {
            return 'price';
        }

        return null;
    }


    /**
     * Rango de fechas.
     */
    protected function dateRange(Request $request): array
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfDay();

        return [$from, $to];
    }


    /**
     * Aplica el filtro de fechas a una consulta de órdenes.
     *
     * IMPORTANTE:
     * No utilizamos orders.status porque esa columna NO existe
     * en tu base de datos.
     */
    protected function applyDateFilter($query, Carbon $from, Carbon $to)
    {
        return $query->whereBetween(
            'o.created_at',
            [$from, $to]
        );
    }


    /**
     * Descarga un Excel.
     */
    protected function downloadExcel(
        string $filename,
        array $rows
    ): BinaryFileResponse {
        return Excel::download(
            new ArrayExports($rows),
            $filename
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 1. VENTAS DETALLADAS
    |--------------------------------------------------------------------------
    */

    public function sales(Request $request): BinaryFileResponse
    {
        [$from, $to] = $this->dateRange($request);

        $sales = DB::table('orders as o')

            ->join(
                'users as u',
                'u.id',
                '=',
                'o.user_id'
            )

            ->join(
                'order_items as oi',
                'oi.order_id',
                '=',
                'o.id'
            )

            ->join(
                'products as p',
                'p.id',
                '=',
                'oi.product_id'
            )

            ->select([
                'u.name as cliente',
                'p.name as producto',
                'oi.quantity',
                'oi.unit_price',
                'oi.subtotal',
                'o.created_at',
            ])

            ->whereBetween(
                'o.created_at',
                [$from, $to]
            )

            ->orderByDesc(
                'o.created_at'
            )

            ->get();


        $rows = [[
            'Cliente',
            'Producto',
            'Cantidad',
            'Precio Unitario',
            'Subtotal',
            'Fecha'
        ]];

        $totalVentas = 0;


        foreach ($sales as $sale) {

            $subtotal = (float) $sale->subtotal;

            $totalVentas += $subtotal;

            $rows[] = [
                $sale->cliente,
                $sale->producto,
                (int) $sale->quantity,

                'S/ ' . number_format(
                    (float) $sale->unit_price,
                    2
                ),

                'S/ ' . number_format(
                    $subtotal,
                    2
                ),

                Carbon::parse(
                    $sale->created_at
                )->format('d/m/Y H:i')
            ];
        }


        $rows[] = [
            'TOTAL GENERAL',
            '',
            '',
            '',
            'S/ ' . number_format(
                $totalVentas,
                2
            ),
            ''
        ];


        return $this->downloadExcel(
            "ventas_{$from->format('Ymd')}_{$to->format('Ymd')}.xlsx",
            $rows
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 2. PRODUCTOS MÁS VENDIDOS
    |--------------------------------------------------------------------------
    */

    public function bestSellers(Request $request): BinaryFileResponse
    {
        [$from, $to] = $this->dateRange($request);

        $productFk = $this->orderItemProductField();

        if (!$productFk) {

            return $this->downloadExcel(
                'productos_mas_vendidos.xlsx',
                [[
                    'Producto',
                    'Unidades',
                    'Importe (S/)'
                ]]
            );
        }


        $items = DB::table('order_items as oi')

            ->join(
                'products as p',
                "p.id",
                '=',
                "oi.$productFk"
            )

            ->join(
                'orders as o',
                'o.id',
                '=',
                'oi.order_id'
            )

            ->whereBetween(
                'o.created_at',
                [$from, $to]
            )

            ->select([
                'p.id as product_id',
                'p.name',

                DB::raw(
                    'SUM(oi.quantity) as qty_sold'
                ),

                DB::raw(
                    'SUM(oi.subtotal) as amount'
                ),
            ])

            ->groupBy(
                'p.id',
                'p.name'
            )

            ->orderByDesc(
                'qty_sold'
            )

            ->limit(100)

            ->get();


        $rows = [[
            'ProductoID',
            'Producto',
            'Unidades',
            'Importe (S/)'
        ]];

        $totalUnidades = 0;
        $totalImporte = 0;


        foreach ($items as $item) {

            $qty = (int) $item->qty_sold;
            $amount = (float) $item->amount;

            $totalUnidades += $qty;
            $totalImporte += $amount;


            $rows[] = [
                $item->product_id,
                $item->name,
                $qty,
                number_format(
                    $amount,
                    2,
                    '.',
                    ''
                )
            ];
        }


        $rows[] = [
            'TOTAL',
            '',
            $totalUnidades,
            number_format(
                $totalImporte,
                2,
                '.',
                ''
            )
        ];


        return $this->downloadExcel(
            "productos_mas_vendidos_{$from->format('Ymd')}_{$to->format('Ymd')}.xlsx",
            $rows
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 3. PRODUCTOS MENOS VENDIDOS
    |--------------------------------------------------------------------------
    */

    public function leastSellers(Request $request): BinaryFileResponse
    {
        [$from, $to] = $this->dateRange($request);

        $productFk = $this->orderItemProductField();

        $rows = [[
            'ProductoID',
            'Producto',
            'Unidades vendidas'
        ]];

        if (!$productFk) {

            return $this->downloadExcel(
                'productos_menos_vendidos.xlsx',
                $rows
            );
        }


        $items = DB::table('order_items as oi')

            ->join(
                'products as p',
                'p.id',
                '=',
                "oi.$productFk"
            )

            ->join(
                'orders as o',
                'o.id',
                '=',
                'oi.order_id'
            )

            ->whereBetween(
                'o.created_at',
                [$from, $to]
            )

            ->select([
                'p.id as product_id',
                'p.name',

                DB::raw(
                    'SUM(oi.quantity) as qty_sold'
                )
            ])

            ->groupBy(
                'p.id',
                'p.name'
            )

            ->orderBy(
                'qty_sold',
                'asc'
            )

            ->limit(20)

            ->get();


        $totalVendidos = 0;


        foreach ($items as $item) {

            $qty = (int) $item->qty_sold;

            $totalVendidos += $qty;

            $rows[] = [
                $item->product_id,
                $item->name,
                $qty
            ];
        }


        $rows[] = [
            'TOTAL',
            '',
            $totalVendidos
        ];


        return $this->downloadExcel(
            "productos_menos_vendidos_{$from->format('Ymd')}_{$to->format('Ymd')}.xlsx",
            $rows
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 4. INVENTARIO CRÍTICO
    |--------------------------------------------------------------------------
    */

    public function inventory(Request $request): BinaryFileResponse
    {
        $rows = [
            [
                'Producto',
                'Stock',
                'Estado'
            ]
        ];

        $totalStock = 0;

        Product::query()
            ->select([
                'id',
                'name',
                'stock'
            ])
            ->where(function ($query) {
                $query->where('stock', '<=', 10)
                    ->orWhereNull('stock');
            })
            ->orderByRaw('COALESCE(stock, 0) ASC')
            ->get()
            ->each(function ($product) use (
                &$rows,
                &$totalStock
            ) {

                /*
                |--------------------------------------------------------------------------
                | Si stock es NULL, lo tratamos como 0
                |--------------------------------------------------------------------------
                */

                $stock = is_null($product->stock)
                    ? 0
                    : (int) $product->stock;


                /*
                |--------------------------------------------------------------------------
                | Acumulamos el stock
                |--------------------------------------------------------------------------
                */

                $totalStock += $stock;


                /*
                |--------------------------------------------------------------------------
                | Determinamos estado
                |--------------------------------------------------------------------------
                */

                $estado = match (true) {

                    $stock <= 3 =>
                        'Crítico',

                    $stock <= 7 =>
                        'Bajo',

                    default =>
                        'Vigilar'
                };


                /*
                |--------------------------------------------------------------------------
                | Agregamos al Excel
                |--------------------------------------------------------------------------
                */

                $rows[] = [
                    $product->name,
                    $stock,
                    $estado
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'TOTAL STOCK',
            $totalStock,
            ''
        ];


        return $this->downloadExcel(
            'inventario_critico.xlsx',
            $rows
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 5. INVENTARIO COMPLETO
    |--------------------------------------------------------------------------
    */

    public function products(Request $request): BinaryFileResponse
    {
        $columns = [
            'name',
            'slug',
            'sku',
            'barcode',
            'sale_price',
            'price',
            'stock',
            'stock_minimo',
            'brand_id',
            'categories_id',
            'category_id',
            'created_at'
        ];


        $existingColumns = collect($columns)

            ->filter(
                fn ($column) =>
                    Schema::hasColumn(
                        'products',
                        $column
                    )
            )

            ->values()

            ->all();


        $headers = ['ProductoID'];


        foreach ($existingColumns as $column) {

            $headers[] = match ($column) {

                'sale_price' =>
                    'Precio Venta',

                'price' =>
                    'Precio',

                'stock_minimo' =>
                    'Stock Mínimo',

                'categories_id',
                'category_id' =>
                    'Categoría ID',

                'brand_id' =>
                    'Marca ID',

                default =>
                    ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $column
                        )
                    )
            };
        }


        $rows = [$headers];


        Product::query()

            ->select(
                array_merge(
                    ['id'],
                    $existingColumns
                )
            )

            ->orderBy('id')

            ->chunk(
                500,
                function ($products) use (
                    &$rows,
                    $existingColumns
                ) {

                    foreach ($products as $product) {

                        $line = [
                            $product->id
                        ];


                        foreach (
                            $existingColumns
                            as $column
                        ) {

                            $line[] =
                                $product->{$column};
                        }


                        $rows[] = $line;
                    }
                }
            );


        return $this->downloadExcel(
            'inventario_completo.xlsx',
            $rows
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 6. ÓRDENES
    |--------------------------------------------------------------------------
    */

    public function orders(Request $request): BinaryFileResponse
    {
        [$from, $to] = $this->dateRange($request);

        $totalField = $this->orderTotalField();


        $hasEstado =
            Schema::hasColumn(
                'orders',
                'estado_pedido_id'
            );


        $headers = [
            'OrderID'
        ];


        if ($hasEstado) {

            $headers[] =
                'Estado Pedido ID';
        }


        $headers[] = 'Total';
        $headers[] = 'Fecha';


        $rows = [$headers];


        Order::query()

            ->whereBetween(
                'created_at',
                [$from, $to]
            )

            ->orderByDesc(
                'created_at'
            )

            ->chunk(
                500,
                function ($orders) use (
                    &$rows,
                    $totalField,
                    $hasEstado
                ) {

                    foreach ($orders as $order) {

                        $line = [
                            $order->id
                        ];


                        if ($hasEstado) {

                            $line[] =
                                $order->estado_pedido_id;
                        }


                        $line[] =
                            $totalField
                                ? number_format(
                                    (float) $order->{$totalField},
                                    2,
                                    '.',
                                    ''
                                )
                                : '0.00';


                        $line[] =
                            $order->created_at
                                ? Carbon::parse(
                                    $order->created_at
                                )->format(
                                    'd/m/Y H:i'
                                )
                                : '';


                        $rows[] = $line;
                    }
                }
            );


        return $this->downloadExcel(
            "ordenes_{$from->format('Ymd')}_{$to->format('Ymd')}.xlsx",
            $rows
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 7. VENTAS POR CATEGORÍA
    |--------------------------------------------------------------------------
    */

    public function categories(Request $request): BinaryFileResponse
    {
        [$from, $to] = $this->dateRange($request);

        $categoryFk =
            $this->productCategoryField();

        $productFk =
            $this->orderItemProductField();


        $rows = [[
            'Categoría',
            'Unidades vendidas',
            'Ingresos (S/)'
        ]];


        /*
        |--------------------------------------------------------------------------
        | Si no existe relación de categoría
        |--------------------------------------------------------------------------
        */

        if (
            !$categoryFk ||
            !$productFk
        ) {

            return $this->downloadExcel(
                "ventas_por_categoria_{$from->format('Ymd')}_{$to->format('Ymd')}.xlsx",
                $rows
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTA AGRUPADA POR CATEGORÍA
        |--------------------------------------------------------------------------
        */

        $categories = DB::table(
                'order_items as oi'
            )

            ->join(
                'products as p',
                'p.id',
                '=',
                "oi.$productFk"
            )

            ->leftJoin(
                'categories as c',
                'c.id',
                '=',
                "p.$categoryFk"
            )

            ->join(
                'orders as o',
                'o.id',
                '=',
                'oi.order_id'
            )

            ->whereBetween(
                'o.created_at',
                [$from, $to]
            )

            ->select([

                DB::raw(
                    "COALESCE(c.name, 'Sin categoría') as category_name"
                ),

                DB::raw(
                    'SUM(oi.quantity) as total_qty'
                ),

                DB::raw(
                    'SUM(oi.subtotal) as total_amount'
                )
            ])

            ->groupBy(
                'category_name'
            )

            ->orderByDesc(
                'total_amount'
            )

            ->get();


        $totalQty = 0;
        $totalAmount = 0;


        /*
        |--------------------------------------------------------------------------
        | CONSTRUIR EXCEL
        |--------------------------------------------------------------------------
        */

        foreach (
            $categories
            as $category
        ) {

            $qty =
                (int) $category->total_qty;

            $amount =
                (float) $category->total_amount;


            $totalQty += $qty;

            $totalAmount += $amount;


            $rows[] = [

                $category->category_name,

                $qty,

                number_format(
                    $amount,
                    2,
                    '.',
                    ''
                )
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL GENERAL
        |--------------------------------------------------------------------------
        */

        $rows[] = [

            'TOTAL GENERAL',

            $totalQty,

            number_format(
                $totalAmount,
                2,
                '.',
                ''
            )
        ];


        return $this->downloadExcel(
            "ventas_por_categoria_{$from->format('Ymd')}_{$to->format('Ymd')}.xlsx",
            $rows
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 8. INGRESOS ÚLTIMOS 12 MESES
    |--------------------------------------------------------------------------
    */

    public function revenueExcel(): BinaryFileResponse
    {
        $totalField =
            $this->orderTotalField();


        $months = collect(
            range(0, 11)
        )->map(
            fn ($i) =>
                Carbon::now()
                    ->startOfMonth()
                    ->subMonths(11 - $i)
        );


        $rows = [[
            'Mes',
            'Ingresos (S/)'
        ]];


        foreach (
            $months
            as $start
        ) {

            $end =
                (clone $start)
                    ->endOfMonth();


            $sum = 0;


            if ($totalField) {

                $sum = DB::table(
                    'orders'
                )

                    ->whereBetween(
                        'created_at',
                        [$start, $end]
                    )

                    ->sum(
                        $totalField
                    );
            }


            $rows[] = [

                $start->format(
                    'Y-m'
                ),

                number_format(
                    (float) $sum,
                    2,
                    '.',
                    ''
                )
            ];
        }


        return $this->downloadExcel(
            'revenue_last_12m.xlsx',
            $rows
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 9. JSON PARA GRÁFICO
    |--------------------------------------------------------------------------
    */

    public function revenueJson(Request $request)
    {
        $group =
            $request->query(
                'group',
                'month'
            );


        $to =
            $request->filled('to')
                ? Carbon::parse(
                    $request->to
                )
                : now();


        $from =
            $request->filled('from')
                ? Carbon::parse(
                    $request->from
                )
                : (clone $to)->subMonths(12);


        $totalField =
            $this->orderTotalField();


        if (!$totalField) {

            return response()->json([
                'ok' => true,
                'group' => $group,
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'labels' => [],
                'data' => [],
                'total' => 0,
            ]);
        }


        switch ($group) {

            case 'year':

                $bucketExpr =
                    'YEAR(created_at)';

                $labelExpr =
                    "DATE_FORMAT(created_at, '%Y')";

                break;


            case 'week':

                $bucketExpr =
                    'YEARWEEK(created_at, 3)';

                $labelExpr =
                    "CONCAT(
                        YEAR(created_at),
                        '-W',
                        LPAD(
                            WEEK(created_at, 3),
                            2,
                            '0'
                        )
                    )";

                break;


            case 'day':

                $bucketExpr =
                    'DATE(created_at)';

                $labelExpr =
                    "DATE_FORMAT(
                        created_at,
                        '%Y-%m-%d'
                    )";

                break;


            case 'month':

            default:

                $bucketExpr =
                    "DATE_FORMAT(
                        created_at,
                        '%Y-%m'
                    )";

                $labelExpr =
                    "DATE_FORMAT(
                        created_at,
                        '%Y-%m'
                    )";

                break;
        }


        $rows = DB::table(
            'orders'
        )

            ->selectRaw(
                "$bucketExpr as bucket,
                 $labelExpr as label,
                 SUM($totalField) as revenue"
            )

            ->whereBetween(
                'created_at',
                [
                    $from->startOfDay(),
                    $to->endOfDay()
                ]
            )

            ->groupBy(
                'bucket',
                'label'
            )

            ->orderBy(
                'bucket'
            )

            ->get();


        $labels =
            $rows
                ->pluck('label')
                ->all();


        $data =
            $rows
                ->pluck('revenue')
                ->map(
                    fn ($value) =>
                        round(
                            (float) $value,
                            2
                        )
                )
                ->all();


        $total =
            array_sum($data);


        return response()->json([

            'ok' => true,

            'group' => $group,

            'from' =>
                $from->toDateString(),

            'to' =>
                $to->toDateString(),

            'labels' =>
                $labels,

            'data' =>
                $data,

            'total' =>
                round(
                    $total,
                    2
                )
        ]);
    }
}