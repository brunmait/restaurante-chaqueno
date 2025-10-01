<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif; 
            font-size: 11px; 
            margin: 0; 
            padding: 15px;
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #15803d;
            padding-bottom: 15px;
        }
        .header h1 { 
            margin: 0 0 8px 0; 
            font-size: 18px; 
            color: #15803d;
            font-weight: bold;
        }
        .restaurant-info {
            font-size: 10px;
            color: #666;
            margin-bottom: 10px;
        }
        .ticket-info {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .ticket-info table {
            width: 100%;
            border: none;
        }
        .ticket-info td {
            border: none;
            padding: 2px 4px;
            font-size: 10px;
        }
        .client-info {
            background: #e8f5e8;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 4px solid #15803d;
        }
        .client-info h3 {
            margin: 0 0 5px 0;
            font-size: 11px;
            color: #15803d;
        }
        table.items { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px;
        }
        table.items th, table.items td { 
            border: 1px solid #ddd; 
            padding: 6px 4px; 
            font-size: 10px;
        }
        table.items th { 
            background: #15803d; 
            color: white;
            font-weight: bold;
        }
        table.items tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        .right { text-align: right; }
        .center { text-align: center; }
        .total-section {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border: 2px solid #15803d;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }
        .total-final {
            font-weight: bold;
            font-size: 14px;
            color: #15803d;
            border-top: 1px solid #15803d;
            padding-top: 5px;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #666;
        }
        .footer .thanks {
            font-size: 12px;
            color: #15803d;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .qr-section {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #f0f9ff;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Header del Restaurante -->
    <div class="header">
        <h1>🍖 RINCÓN CHAQUEÑO 🍖</h1>
        <div class="restaurant-info">
            <strong>Especialidad en Chancho a la Cruz y Pollo a la Leña</strong><br>
            📍 El Alto, Bolivia<br>
            📞 Teléfono: 63217872<br>
            🕒 Horarios: Viernes, Sábado, Domingo y Feriados 10:00 - 15:00
        </div>
    </div>

    <!-- Información del Comprobante -->
    <div class="ticket-info">
        <table>
            <tr>
                <td><strong>Comprobante:</strong> {{ $ticket }}</td>
                <td class="right"><strong>Fecha:</strong> {{ $fecha }}</td>
            </tr>
            <tr>
                <td><strong>Cajero:</strong> {{ $cajero }}</td>
                <td class="right"><strong>Método:</strong> {{ $metodo ?? 'Efectivo' }}</td>
            </tr>
        </table>
    </div>

    <!-- Información del Cliente -->
    @if($cliente || isset($cliente_telefono))
    <div class="client-info">
        <h3>👤 DATOS DEL CLIENTE</h3>
        @if($cliente)
            <strong>Nombre:</strong> {{ $cliente }}<br>
        @endif
        @if(isset($cliente_telefono) && $cliente_telefono)
            <strong>Teléfono:</strong> {{ $cliente_telefono }}<br>
        @endif
    </div>
    @endif

    <!-- Detalle de Productos -->
    <table class="items">
        <thead>
            <tr>
                <th style="width:50%">Descripción</th>
                <th style="width:12%" class="center">Cant.</th>
                <th style="width:19%" class="right">Precio Unit.</th>
                <th style="width:19%" class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $it)
                <tr>
                    <td>{{ $it['descripcion'] }}</td>
                    <td class="center">{{ $it['cantidad'] }}</td>
                    <td class="right">Bs. {{ number_format($it['precio'], 0) }}</td>
                    <td class="right">Bs. {{ number_format($it['subtotal'], 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Resumen de Pago -->
    <div class="total-section">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>Bs. {{ number_format($total, 0) }}</span>
        </div>
        <div class="total-row total-final">
            <span>TOTAL A PAGAR:</span>
            <span>Bs. {{ number_format($total, 0) }}</span>
        </div>
        <hr style="margin: 8px 0; border: none; border-top: 1px solid #ccc;">
        <div class="total-row">
            <span>Monto Recibido:</span>
            <span>Bs. {{ number_format($pago, 0) }}</span>
        </div>
        <div class="total-row">
            <span>Cambio:</span>
            <span>Bs. {{ number_format($cambio, 0) }}</span>
        </div>
    </div>

    <!-- Sección QR para Contacto -->
    <div class="qr-section">
        <strong>📱 ¡Síguenos y contáctanos!</strong><br>
        <small>WhatsApp: 63217872 | Pedidos y consultas</small>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="thanks">¡GRACIAS POR SU PREFERENCIA!</div>
        <div>🌟 Sabor auténtico chaqueño en cada bocado 🌟</div>
        <div>Este comprobante es válido como constancia de compra</div>
        <div style="margin-top: 8px;">{{ now()->format('d/m/Y H:i:s') }} - Sistema POS Rincón Chaqueño</div>
    </div>
</body>
</html>
