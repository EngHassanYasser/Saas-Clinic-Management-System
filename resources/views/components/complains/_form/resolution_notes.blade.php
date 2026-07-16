@if (auth()->user()->type === 'clinic')
    <div x-show="mode == 'update'">
        <p class="text-xs text-gray-400 mb-1.5">الرد على الشكوى</p>
        <textarea name="resolution_notes" rows="3"
            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition resize-none"
            placeholder="اكتب ردك هنا...">{{ old('resolution_notes') }}</textarea>
    </div>
@endif