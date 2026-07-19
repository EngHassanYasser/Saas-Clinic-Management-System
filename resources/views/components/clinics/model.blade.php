<div x-show="showModal" x-cloak @click.self="showModal = false" @keydown.escape.window="showModal = false"
    x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-90 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">

        <x-clinics.model.header />
        <div class="p-6 grid grid-cols-2 gap-4">
            <x-clinics.model.clinic_name />
            <x-clinics.model.email />
            <x-clinics.model.city />
            <x-clinics.model.status />
            <x-clinics.model.plan />
        </div>
        <x-clinics.model.validation_error />
        <x-clinics.model.actions />
    </div>
</div>
<x-clinics.confirm-delete />
