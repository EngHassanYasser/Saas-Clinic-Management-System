@if (session('message'))
    <div class="bg-green-100 border text-center border-green-300 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('message') }}
    </div>
@endif
