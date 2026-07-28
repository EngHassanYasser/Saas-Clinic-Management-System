<div x-show="showModal" x-cloak x-transition.opacity @keydown.escape.window="closeModal()"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-6">

    <div @click.outside="closeModal()" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-[0_30px_80px_rgba(0,0,0,.25)]">
        <x-plans.model.header />
        <form :action.prevent="mode == 'add' ?
            '{{ route('plans.store') }}' :
            '{{ url('plans') }}/' + form.id"
            method="POST">
            @csrf
            <template x-if="mode == 'update'">
                @method('PUT')
            </template>
            <div class="space-y-8 p-8">
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-gray-800"> بيانات الباقة</h3>
                    <div class="grid gap-6 md:grid-cols-2">
                        <x-plans.model.name />
                        <x-plans.model.price />
                        <x-plans.model.doctors />
                        <x-plans.model.appointments />
                        <x-plans.model.status />
                    </div>
                </div>
            </div>
            <x-plans.model.actions />
        </form>
    </div>
</div
