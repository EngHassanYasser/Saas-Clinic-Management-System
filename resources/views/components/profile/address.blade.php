<div>
    <label class="block mb-2 text-sm font-semibold text-slate-700">
        Address
    </label>

    <input type="text" name="address" value="{{ old('address', auth()->user()->address) }}"
        class="w-full rounded-xl border-slate-300 focus:border-cyan-600 focus:ring-cyan-600">

    @error('address')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
