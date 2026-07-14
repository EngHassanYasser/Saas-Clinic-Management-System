@if (auth()->user()->type === 'clinic')
    <div>
        <p class="text-xs text-gray-400 mb-1.5">الرد على الشكوى</p>
        <textarea id="replyText" rows="3"
            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition resize-none"
            placeholder="اكتب ردك هنا..."></textarea>
    </div>
@else
    <div>
        <p class="text-xs text-gray-400 mb-1.5">الرد على الشكوى</p>
        <textarea id="replyText" rows="3" readonly
            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none transition resize-none bg-gray-50"></textarea>
    </div>
@endif
