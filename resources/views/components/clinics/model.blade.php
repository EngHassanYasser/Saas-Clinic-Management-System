<div
    x-show="showModal"
    x-cloak
    @click.self="showModal = false"
    @keydown.escape.window="showModal = false"
    x-transition.opacity
class="fixed inset-0 z-50 flex items-start justify-center bg-black/40 pt-22 px-2">
    <div
        x-transition
        class="w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-xl bg-white shadow-xl"
    >
        <x-clinics.model.header />
        <form
            :action="mode == 'update'
                ? '{{ url('clinics') }}/' + form.id
                : '{{ route('clinics.store') }}'"
            method="POST"
        >
            <x-clinics.model._form_fileds />
            <x-clinics.model.actions />
        </form>
    </div>
</div>
<x-clinics.confirm-delete />