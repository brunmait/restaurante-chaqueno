<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Venta #{{ $ticket }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#000; }
        .center { text-align:center; }
        .mt-2 { margin-top:8px; }
        .mt-4 { margin-top:16px; }
        table { width:100%; border-collapse: collapse; }
        th, td { padding:6px 4px; border-bottom:1px solid #ddd; }
        .small { font-size: 11px; color:#555; }
    </style>
</head>
<body>
    <div class="center">
        <h3>Rincón Chaqueño</h3>
        <div class="small">Comprobante de Venta</div>
    </div>

    <div class="mt-2">
        <strong>Número:</strong> {{ $ticket }}<br>
        <strong>Fecha:</strong> {{ $fecha }}<br>
        <strong>Cajero:</strong> {{ $cajero }}
    </div>

    <div class="mt-4">
        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th class="center">Cant.</th>
                    <th class="center">Precio</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Plato costillas ({{ $costillas }} costillas)</td>
                    <td class="center">1</td>
                    <td class="center">Bs. {{ number_format($precio, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right"><strong>Total:</strong></td>
                    <td class="center"><strong>Bs. {{ number_format($precio, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-4 small">
        Consumo de stock: {{ number_format($kilos_consumidos, 3) }} kg ({{ $gramos_costilla }} g por costilla)
    </div>

    <div class="center mt-4 small">
        ¡Gracias por su compra!
    </div>
</body>
</html>
