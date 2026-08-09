<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        try {

            $contenido = [];

            $contenido[] = "========================================";
            $contenido[] = date('Y-m-d H:i:s');
            $contenido[] = "";
            $contenido[] = "METHOD";
            $contenido[] = $request->method();
            $contenido[] = "";
            $contenido[] = "URL";
            $contenido[] = $request->fullUrl();
            $contenido[] = "";
            $contenido[] = "HEADERS";
            $contenido[] = json_encode(
                $request->headers->all(),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
            $contenido[] = "";
            $contenido[] = "QUERY";
            $contenido[] = json_encode(
                $request->query(),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
            $contenido[] = "";
            $contenido[] = "POST";
            $contenido[] = json_encode(
                $request->all(),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
            $contenido[] = "";
            $contenido[] = "RAW";
            $contenido[] = $request->getContent();
            $contenido[] = "";
            $contenido[] = "========================================";
            $contenido[] = "";

            file_put_contents(
                storage_path('logs/webhook_debug.txt'),
                implode(PHP_EOL, $contenido),
                FILE_APPEND
            );

            return response()->json([
                'success' => true,
                'message' => 'Webhook recibido'
            ], 200);

        } catch (Throwable $e) {

            file_put_contents(
                storage_path('logs/webhook_error.txt'),
                "========================================\n",
                FILE_APPEND
            );

            file_put_contents(
                storage_path('logs/webhook_error.txt'),
                date('Y-m-d H:i:s') . PHP_EOL,
                FILE_APPEND
            );

            file_put_contents(
                storage_path('logs/webhook_error.txt'),
                $e->getMessage() . PHP_EOL,
                FILE_APPEND
            );

            file_put_contents(
                storage_path('logs/webhook_error.txt'),
                $e->getFile() . PHP_EOL,
                FILE_APPEND
            );

            file_put_contents(
                storage_path('logs/webhook_error.txt'),
                $e->getLine() . PHP_EOL,
                FILE_APPEND
            );

            file_put_contents(
                storage_path('logs/webhook_error.txt'),
                $e->getTraceAsString() . PHP_EOL . PHP_EOL,
                FILE_APPEND
            );

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}