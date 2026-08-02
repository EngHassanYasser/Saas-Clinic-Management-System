<div class="p-6 sm:p-8 border-b border-gray-100">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-clock text-teal-600"></i>
            مواعيد العمل الأسبوعية
        </h2>
        <div class="mb-6">
            <x-clinics.form.days />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-clinics.form.open_time />
                <x-clinics.form.close_time />
            </div>
        </div>
    </div>
</div>
