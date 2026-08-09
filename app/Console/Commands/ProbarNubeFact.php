<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Comprobante;
use App\Services\Facturacion\NubeFactService;
use Illuminate\Console\Command;

class ProbarNubeFact extends Command
{
    protected $signature = 'nubefact:test';

    protected $description = 'Probar emisión de un comprobante en NubeFact';

    public function handle(NubeFactService $nubeFact): int
    {
        $comprobante = Comprobante::with([
            'order.user',
            'order.items.product',
            'estadoComprobante',
            'electronicDocument',
        ])->findOrFail(5); // <-- cambia por el ID del comprobante

        try {

            $documento = $nubeFact->emitir($comprobante);

            $this->info('================================');
            $this->info('COMPROBANTE EMITIDO');
            $this->info('Serie : '.$documento->serie);
            $this->info('Número: '.$documento->numero);
            $this->info('PDF   : '.$documento->pdf_url);
            $this->info('XML   : '.$documento->xml_url);
            $this->info('CDR   : '.$documento->cdr_url);
            $this->info('================================');

        } catch (\Throwable $e) {

            $this->error('ERROR');
            $this->error($e->getMessage());
            $this->error($e->getFile());
            $this->error('Línea '.$e->getLine());

        }

        return self::SUCCESS;
    }
}