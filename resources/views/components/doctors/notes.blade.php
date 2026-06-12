{{-- ملاحظات --}}
<div class="bg-white rounded-xl border border-gray-100 p-5">
    <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
        <i class="fa fa-note-sticky text-teal-500"></i> ملاحظات
    </h2>
    <textarea name="notes" rows="4"
        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition resize-none"
        placeholder="أي معلومات إضافية عن الطبيب...">{{ old('notes') }}</textarea>
</div>
