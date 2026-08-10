<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">تفاصيل الشكوى</label>
    <textarea name="description" rows="5" x-model="currentComplaintt.description" rows="5"
        class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"
        placeholder="اكتب تفاصيل المشكلة بشكل واضح ودقيق...">{{ old('description') }}</textarea>
</div>
