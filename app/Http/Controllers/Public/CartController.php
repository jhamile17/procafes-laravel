<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

use App\Services\Cart\LegacySessionCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly LegacySessionCartService $cartService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->cartService->summary());
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'qty' => ['nullable', 'integer', 'min:1'],
            'image' => ['nullable', 'string'],
            'url' => ['nullable', 'string'],
            'variant' => ['nullable'],
        ]);

        return response()->json($this->cartService->add($data));
    }

    public function update(Request $request, string $rowId): JsonResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        return response()->json(
            $this->cartService->update($rowId, (int) $data['qty'])
        );
    }

    public function remove(string $rowId): JsonResponse
    {
        return response()->json($this->cartService->remove($rowId));
    }

    public function clear(): JsonResponse
    {
        return response()->json($this->cartService->clear());
    }
}

