<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WhatsAppController extends Controller
{
    private function client()
    {
        return new Client([
            'base_uri' => 'https://graph.facebook.com/'.env('WHATSAPP_API_VER','v20.0').'/',
            'timeout'  => 20,
        ]);
    }

    private function apiSend($payload)
    {
        $client = $this->client();
        return $client->post(env('WHATSAPP_PHONE_ID').'/messages', [
            'headers' => [
                'Authorization' => 'Bearer '.env('WHATSAPP_TOKEN'),
                'Content-Type'  => 'application/json',
            ],
            'json' => $payload,
        ]);
    }

    /*** 1) WEBHOOK VERIFY (GET) ***/
    public function verifyWebhook(Request $request)
    {
        $mode = $request->input('hub_mode');
        $token = $request->input('hub_verify_token');
        $challenge = $request->input('hub_challenge');

        if ($mode === 'subscribe' && $token === env('WHATSAPP_VERIFY_TOKEN', 'verify_me')) {
            return response($challenge, 200);
        }
        return response('Error de verificación', 403);
    }

    /*** 2) WEBHOOK RECEIVE (POST) ***/
    public function receiveWebhook(Request $request)
    {
        $data = $request->all();

        $entry = $data['entry'][0] ?? null;
        $changes = $entry['changes'][0] ?? null;
        $value = $changes['value'] ?? null;
        $messages = $value['messages'][0] ?? null;

        if ($messages) {
            $from = $messages['from'];
            $type = $messages['type'];
            $text = '';

            if ($type === 'text') {
                $text = trim(strtolower($messages['text']['body'] ?? ''));
            } elseif ($type === 'interactive') {
                $id = $messages['interactive']['button_reply']['id'] ?? ($messages['interactive']['list_reply']['id'] ?? '');
                $text = trim(strtolower($id));
            }

            if (in_array($text, ['hola','menu','menú','pedido','pedidos'])) {
                $this->sendInteractiveMenu($from);
            } elseif (in_array($text, ['pagar','qr','pago'])) {
                $this->sendPaymentQrTo($from);
            } elseif (in_array($text, ['hacer_pedido'])) {
                $this->apiSendText($from, "Perfecto 👌 Indícame tu pedido (producto, cantidad y dirección).");
            } elseif (in_array($text, ['ver_menu'])) {
                $this->apiSendText($from, "Menú del día 📋\n- Chancho a la Cruz Bs.50-90\n- Pollo a la Leña Bs.60\n- Promociones especiales\nResponde con Hacer_Pedido cuando decidas.");
            } else {
                $this->sendInteractiveMenu($from);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /*** Helper: Texto ***/
    private function apiSendText($to, $body)
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $body]
        ];
        $this->apiSend($payload);
    }

    /*** 3) MENÚ INTERACTIVO ***/
    private function sendInteractiveMenu($to)
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => ['text' => "¡Hola! Soy el asistente de Rincón Chaqueño 🍖✨\nElige una opción:"],
                'action' => [
                    'buttons' => [
                        ['type' => 'reply', 'reply' => ['id' => 'hacer_pedido', 'title' => 'Hacer pedido']],
                        ['type' => 'reply', 'reply' => ['id' => 'ver_menu',    'title' => 'Ver menú']],
                        ['type' => 'reply', 'reply' => ['id' => 'pagar',       'title' => 'Pagar (QR)']],
                    ]
                ]
            ]
        ];
        $this->apiSend($payload);
    }

    /*** 4) ENVIAR QR DE PAGO ***/
    public function sendPaymentQr(Request $request)
    {
        $to = $request->input('to', env('WHATSAPP_DEFAULT_TO'));
        $linkPago = $request->input('link', 'https://tusitio.com/pago/123');
        return $this->sendPaymentQrTo($to, $linkPago)
            ? back()->with('ok','QR enviado por WhatsApp ✅')
            : back()->with('error','No se pudo enviar el QR');
    }

    private function sendPaymentQrTo($to, $linkPago = null)
    {
        try {
            $link = $linkPago ?: 'https://rinconchaqueno.com/pago/123';
            
            // Por ahora enviar solo el link hasta configurar QR
            $this->apiSendText($to, "Aquí tienes tu enlace de pago 💳\n\n🔗 $link\n\nTras pagar, responde con *Hecho*.");
            
            return true;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }

    /*** Enviar menú manual ***/
    public function sendMenu(Request $request)
    {
        $to = $request->input('to', env('WHATSAPP_DEFAULT_TO'));
        $this->sendInteractiveMenu($to);
        return back()->with('ok', 'Menú enviado ✅');
    }
}