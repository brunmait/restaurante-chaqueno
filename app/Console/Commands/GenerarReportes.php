<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ReporteController;

class GenerarReportes extends Command
{
    protected $signature = 'reportes:generar {tipo=diario}';
    protected $description = 'Genera reportes automáticos del sistema';

    public function handle()
    {
        $tipo = $this->argument('tipo');
        $reporteController = new ReporteController();

        switch ($tipo) {
            case 'diario':
                $reporteController->generarReporteDiario();
                $this->info('Reporte diario generado correctamente.');
                break;
            
            case 'mensual':
                $reporteController->generarReporteMensual();
                $this->info('Reporte mensual generado correctamente.');
                break;
            
            case 'stock':
                $reporteController->generarReporteStockAgotado();
                $this->info('Verificación de stock completada.');
                break;
            
            default:
                $this->error('Tipo de reporte no válido. Use: diario, mensual, stock');
                return 1;
        }

        return 0;
    }
}