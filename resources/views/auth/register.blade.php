<x-guest-layout>
    @if($errors->any() && session('registration_errors'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            <h3 class="font-bold">Errores en el registro:</h3>
            <ul class="mt-2 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Campos del formulario (igual que antes) -->
    </form>
</x-guest-layout>