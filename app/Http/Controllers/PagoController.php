<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function crear(Request $request)
    {
        $request->validate([
            'concepto' => 'required|string',
            'monto' => 'required|numeric|min:1',
            'pedido_id' => 'nullable|integer'
        ]);

        $payment = Payment::create([
            'uuid' => Str::uuid(),
            'pedido_id' => $request->pedido_id,
            'concepto' => $request->concepto,
            'monto_centavos' => (int) round($request->monto * 100),
            'currency' => 'BOB',
            'gateway' => 'qr_simple',
        ]);

        // Simular checkout URL (en producción sería de tu pasarela)
        $checkoutUrl = route('pagos.show', $payment->uuid);
        $payment->update(['checkout_url' => $checkoutUrl]);

        return redirect()->route('pagos.show', $payment->uuid);
    }

    public function show(string $uuid)
    {
        $payment = Payment::where('uuid', $uuid)->firstOrFail();
        return view('pagos.show', compact('payment'));
    }

    public function marcarPagado(Request $request, string $uuid)
    {
        $payment = Payment::where('uuid', $uuid)->firstOrFail();
        
        if ($payment->status !== 'paid') {
            $payment->update(['status' => 'paid']);
            
            // Actualizar pedido si existe
            if ($payment->pedido_id) {
                DB::table('pedidos')->where('id', $payment->pedido_id)
                    ->update(['estado_pago' => 'pagado']);
            }
        }
        
        return response()->json(['success' => true]);
    }
}