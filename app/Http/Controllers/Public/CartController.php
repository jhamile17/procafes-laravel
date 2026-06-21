<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Cart\SessionCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly SessionCartService $cartService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->cartService->summary($request)
        );
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        return response()->json(
            $this->cartService->add(
                $request,
                (int) $data['product_id'],
                (int) ($data['qty'] ?? 1),
            )
        );
    }

    public function update(Request $request, int $productId): JsonResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        return response()->json(
            $this->cartService->update(
                $request,
                $productId,
                (int) $data['qty'],
            )
        );
    }

    public function remove(Request $request, int $productId): JsonResponse
    {
        return response()->json(
            $this->cartService->remove($request, $productId)
        );
    }

    public function clear(Request $request): JsonResponse
    {
        return response()->json(
            $this->cartService->clear($request)
        );
    }
}