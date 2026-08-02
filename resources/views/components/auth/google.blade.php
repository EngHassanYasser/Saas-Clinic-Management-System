<form action="{{ route('google.redirect') }}" method="GET">
    <button type="submit"
        class="w-full border border-gray-200 py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-gray-50">
        <i class="fa-brands fa-google text-red-500"></i>
        تسجيل الدخول بواسطة Google
    </button>
    <div class="flex items-center gap-6 mb-4">

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="type" value="{{ \App\Enums\RoleType::PATIENT->value }}" class="text-blue-600"
                checked>
            <span>مريض</span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="type" value="{{ \App\Enums\RoleType::CLINIC->value }}" class="text-blue-600">
            <span>عيادة</span>
        </label>

    </div>
</form>
