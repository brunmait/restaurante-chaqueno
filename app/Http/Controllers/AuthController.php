<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        // Permitir prellenar el email desde query string
        $prefill = $request->query('email');

        // Generar CAPTCHA de letras y guardarlo en sesión
        $text = strtoupper(substr(bin2hex(random_bytes(8)), 0, 6));
        $request->session()->put('captcha_text', $text);

        return view('auth.login', ['prefill' => $prefill]);
    }

    public function login(Request $request)
    {
        // Validación de inputs: evita payloads maliciosos y campos faltantes
        $credentials = $request->validate([
            'email' => ['required','string'],
            // Prohibimos < y > para evitar inyección de etiquetas y exigimos longitud mínima
            'password' => ['required','string','min:6','regex:/^[^<>]+$/'],
            'captcha' => ['required','string','max:12'],
        ], [
            'password.regex' => 'La contraseña contiene caracteres no permitidos.',
        ]);

        // Validación de CAPTCHA (letras)
        $expected = $request->session()->pull('captcha_text');
        if ($expected === null || strtoupper(trim((string) $credentials['captcha'])) !== (string) $expected) {
            // Regenerar al fallar
            $text = strtoupper(substr(bin2hex(random_bytes(8)), 0, 6));
            $request->session()->put('captcha_text', $text);

            return back()->withErrors([
                'captcha' => 'CAPTCHA incorrecto. Inténtalo de nuevo.',
            ])->onlyInput('email');
        }

        // Auth::attempt usa consultas parametrizadas (bindings) -> sin concatenar strings => sin inyección SQL
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleName = $user && $user->role ? strtolower($user->role->name ?? '') : '';
            if ($roleName === 'cajero') {
                return redirect()->intended(route('cashier.dashboard'));
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        // Regenerar CAPTCHA al fallar credenciales
        $text = strtoupper(substr(bin2hex(random_bytes(8)), 0, 6));
        $request->session()->put('captcha_text', $text);

        return back()->withErrors([
            'email' => 'Credenciales inválidas.',
        ])->onlyInput('email');
    }

    // Imagen del CAPTCHA en SVG (no requiere extensión GD)
    public function captcha(Request $request)
    {
        $text = $request->session()->get('captcha_text', 'ABCDEFG');
        $width = 180; $height = 50;
        $chars = str_split($text);
        $x = 15;
        $svgLetters = '';
        foreach ($chars as $ch) {
            $y = random_int(28, 38);
            $rotate = random_int(-20, 20);
            $svgLetters .= '<text x="'.$x.'" y="'.$y.'" fill="#142878" font-size="22" font-family="monospace" transform="rotate('.$rotate.' '.$x.' '.$y.')">'.htmlspecialchars($ch, ENT_QUOTES,'UTF-8').'</text>';
            $x += 26;
        }
        // líneas de ruido
        $noise = '';
        for ($i=0; $i<4; $i++) {
            $noise .= '<line x1="0" y1="'.random_int(5,45).'" x2="'.$width.'" y2="'.random_int(5,45).'" stroke="#ccd3f1" stroke-width="1"/>';
        }
        for ($i=0; $i<40; $i++) {
            $noise .= '<circle cx="'.random_int(0,$width).'" cy="'.random_int(0,$height).'" r="'.random_int(0,1).'" fill="#e8ecff" />';
        }
        $svg = '<?xml version="1.0" encoding="UTF-8"?>'
             . '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'">'
             . '<rect width="100%" height="100%" fill="#ffffff"/>'.$noise.$svgLetters.'</svg>';
        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('public.home');
    }
}


