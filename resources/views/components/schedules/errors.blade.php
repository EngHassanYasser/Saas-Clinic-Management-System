@if ($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-red-600">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
