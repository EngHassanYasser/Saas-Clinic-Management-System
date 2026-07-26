<div>
    <label class="block mb-2 text-sm font-semibold text-slate-700">
        Email Address
    </label>
    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
        class="w-full rounded-xl border-slate-300 focus:border-cyan-600 focus:ring-cyan-600">
    @error('email')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
