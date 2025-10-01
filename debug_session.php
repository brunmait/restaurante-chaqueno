<?php
// Script para debuggear la sesión actual
// Acceder desde: http://localhost/laravel/App_restaurante/debug_session.php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "<h2>🔍 Debug de Sesión</h2>";

if (Auth::check()) {
    $user = Auth::user();
    echo "<p>✅ Usuario autenticado: <strong>" . $user->nombre . "</strong></p>";
    echo "<p>📧 Email: " . $user->email . "</p>";
    echo "<p>🆔 ID: " . $user->id . "</p>";
    echo "<p>🎭 Rol ID: " . $user->rol_id . "</p>";
    
    if ($user->role) {
        echo "<p>👤 Rol: <strong>" . $user->role->name . "</strong></p>";
        echo "<p>📝 Descripción: " . $user->role->description . "</p>";
    } else {
        echo "<p>❌ Sin rol asignado</p>";
    }
    
    echo "<hr>";
    echo "<p>🔗 <a href='/admin'>Ir al panel admin</a></p>";
    echo "<p>🚪 <a href='/logout' onclick='event.preventDefault(); document.getElementById(\"logout-form\").submit();'>Cerrar sesión</a></p>";
    echo '<form id="logout-form" action="/logout" method="POST" style="display: none;">';
    echo csrf_field();
    echo '</form>';
} else {
    echo "<p>❌ No hay usuario autenticado</p>";
    echo "<p>🔗 <a href='/login'>Iniciar sesión</a></p>";
}

$kernel->terminate($request, $response);