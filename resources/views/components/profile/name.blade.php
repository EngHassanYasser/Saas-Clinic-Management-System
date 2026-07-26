<div>
    <label class="block mb-2 text-sm font-semibold text-slate-700">
        Full Name
    </label>
    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
        class="w-full rounded-xl border-slate-300 focus:border-cyan-600 focus:ring-cyan-600">
    @error('name')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
