@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-slate-800 flex items-center">
        <svg class="w-6 h-6 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Cajeros
    </h3>
    <div class="flex gap-3">
        <a class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-4 py-2 rounded-lg transition-all shadow-md" href="{{ route('admin.cajeros.create') }}">Nuevo Cajero</a>
        <a class="bg-gradient-to-r from-slate-500 to-slate-600 hover:from-slate-600 hover:to-slate-700 text-white px-4 py-2 rounded-lg transition-all shadow-md" href="{{ route('admin.dashboard') }}">Volver</a>
    </div>
</div>

@if (session('status'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('status') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-lg border border-slate-200">
    <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-6 py-4 flex justify-between items-center rounded-t-lg">
        <h5 class="text-lg font-semibold text-slate-800">Listado de Cajeros</h5>
        <div class="flex items-center gap-3">
            <input type="text" id="searchInput" class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white" placeholder="Buscar por nombre/correo">
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full" id="cashiersTable">
            <thead class="bg-gradient-to-r from-slate-100 to-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase tracking-wider">Correo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-600 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($cajeros as $cajero)
                    <tr class="hover:bg-gradient-to-r hover:from-slate-50 hover:to-blue-50 transition-all">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 text-white rounded-full flex items-center justify-center text-sm font-medium shadow-md">
                                    {{ strtoupper(substr($cajero->nombre, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-800">{{ $cajero->nombre }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $cajero->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($cajero->activo ?? 1))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200">
                                    <svg class="w-2 h-2 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-slate-100 to-gray-100 text-slate-700 border border-slate-200">
                                    <svg class="w-2 h-2 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.cajeros.edit', $cajero->id) }}" class="bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-500 hover:to-orange-600 text-white p-2 rounded-lg transition-all shadow-md" title="Editar">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </a>
                                <form method="post" action="{{ route('admin.cajeros.toggle', $cajero->id) }}" class="inline">
                                    @csrf
                                    <button class="{{ ($cajero->activo ?? 1) ? 'bg-gradient-to-r from-slate-400 to-slate-500 hover:from-slate-500 hover:to-slate-600' : 'bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700' }} text-white p-2 rounded-lg transition-all shadow-md" title="{{ ($cajero->activo ?? 1) ? 'Desactivar' : 'Activar' }}">
                                        @if(($cajero->activo ?? 1))
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                <button class="bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white p-2 rounded-lg transition-all shadow-md" onclick="confirmDelete({{ $cajero->id }}, '{{ $cajero->nombre }}')" title="Eliminar">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 012 0v4a1 1 0 11-2 0V7zM12 7a1 1 0 012 0v4a1 1 0 11-2 0V7z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                @if(($cajero->activo ?? 1))
                                    <a class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white p-2 rounded-lg transition-all shadow-md" target="_blank" href="{{ route('login', ['email' => $cajero->email]) }}" title="Probar cajero">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div class="text-lg font-medium mb-2 text-slate-700">Sin cajeros registrados</div>
                            <div class="text-sm text-slate-500">Crea tu primer cajero para comenzar</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 rounded-b-lg">
        <p class="text-sm text-slate-600">Puedes crear/editar usuarios en el módulo <strong class="text-slate-800">Usuarios</strong>.</p>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div id="deleteModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border border-slate-200 w-96 shadow-2xl rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-slate-800">Confirmar eliminación</h3>
                <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="mb-6">
                <p class="text-sm text-slate-600">¿Estás seguro de que quieres eliminar al cajero <strong id="cashierName" class="text-slate-800"></strong>?</p>
                <p class="text-sm text-rose-600 mt-2">Esta acción no se puede deshacer.</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" class="bg-gradient-to-r from-slate-300 to-slate-400 hover:from-slate-400 hover:to-slate-500 text-slate-800 px-4 py-2 rounded-lg transition-all" onclick="closeModal()">Cancelar</button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white px-4 py-2 rounded-lg transition-all shadow-md">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('cashierName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/cajeros/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Filtro rápido por texto (nombre/correo)
const searchInput = document.getElementById('searchInput');
const table = document.getElementById('cashiersTable');
if (searchInput && table) {
    searchInput.addEventListener('input', () => {
        const term = searchInput.value.toLowerCase();
        table.querySelectorAll('tbody tr').forEach(tr => {
            const text = tr.innerText.toLowerCase();
            tr.style.display = text.includes(term) ? '' : 'none';
        });
    });
}
</script>
@endsection
