<div x-show= "mode == 'update'" x-cloack class="col-span-2">
    <label class="block text-xs font-semibold text-gray-500 mb-1.5">
        نوع الاشتراك <span class="text-red-400"></span>
    </label>
    <input
        :value="form.plan?.name ?? 'لا يوجد اشتراك'"
        readonly
        :class="form.plan ? 'text-gray-900' : 'text-red-500'"
        class="w-full border border-gray-200 bg-gray-50 text-sm px-3 py-2.5 rounded-lg outline-none border-none text-center">
</div>