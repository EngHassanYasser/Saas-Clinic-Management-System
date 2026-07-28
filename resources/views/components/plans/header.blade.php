<!-- Header -->

<div class="flex flex-col gap-2 rounded-1xl border border-gray-200 bg-white p-4 shadow-sm lg:flex-row lg:items-center lg:justify-between">

    <div class="flex items-start gap-2">

        <div class="flex h-10 w-14 items-center justify-center rounded-2xl bg-indigo-100">

            <i class="fa-solid fa-layer-group text-2xl text-indigo-600"></i>

        </div>

        <div>

            <h4 class="text-1xl font-bold tracking-tight text-gray-900">

                إدارة الباقات

            </h4>

            <p class="mt-2 max-w-xl text-gray-500">

                قم بإنشاء وإدارة باقات الاشتراك الخاصة بالعيادات، وتحديد الأسعار والحدود والمميزات المتاحة لكل باقة.

            </p>

        </div>

    </div>

    <button
        @click="openCreate()"
        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-6 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-xl">

        <i class="fa-solid fa-plus"></i>

        <span>إضافة باقة</span>

    </button>

</div>