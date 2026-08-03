<div>
    <label class="block mb-2 text-sm font-semibold text-slate-700">
        Address
    </label>

    <input x-model="user.address" type="text" name="address" value="{{ old('address', auth()->user()->address) }}"
        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm
                   focus:border-cyan-600 focus:ring-2 focus:ring-cyan-200 focus:outline-none">

    @error('address')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
