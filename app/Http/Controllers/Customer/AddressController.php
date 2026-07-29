<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\LocationIQService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
   public function search(
    Request $request,
    LocationIQService $locationIQ
): JsonResponse {

    $request->validate([
        'q' => ['required', 'string', 'min:2'],
    ]);

    return response()->json(
        $locationIQ->search(
            $request->string('q')->toString()
        )
    );
}
}