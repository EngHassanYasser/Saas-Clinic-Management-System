<div>
    <label class="block mb-2 text-sm font-semibold text-slate-700">
        Phone Number
    </label>
    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
        class="w-full rounded-xl border-slate-300 focus:border-cyan-600 focus:ring-cyan-600">
    @error('phone')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
