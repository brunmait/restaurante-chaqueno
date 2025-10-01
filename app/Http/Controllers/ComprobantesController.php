<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ComprobantesController extends Controller
{
    public function index()
    {
        $comprobantes = DB::table('pedidos_online')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('cashier.comprobantes.index', compact('comprobantes'));
    }

    public function show($id)
    {
        $comprobante = DB::table('pedidos_online')->where('id', $id)->first();
        
        if (!$comprobante) {
            abort(404);
        }
        
        return view('cashier.comprobantes.show', compact('comprobante'));
    }

    public function qr($id)
    {
        $comprobante = DB::table('pedidos_online')->where('id', $id)->first();
        
        if (!$comprobante) {
            abort(404);
        }

        $url = route('comprobantes.public', $comprobante->numero_ticket);
        
        // Generar QR simple con Google Charts API
        $qrUrl = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . urlencode($url);
        
        return redirect($qrUrl);
    }

    public function publicShow($ticket)
    {
        $comprobante = DB::table('pedidos_online')
            ->where('numero_ticket', $ticket)
            ->first();
        
        if (!$comprobante) {
            abort(404);
        }
        
        return view('public.comprobante', compact('comprobante'));
    }
}