<div x-show="showModel" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
    @keydown.escape.window="showEditModal = false">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden" @click.outside="showEditModal = false">

        <x-doctors.model.header />

            <form
                :action="mode == 'add' ?
                    '{{ route('doctors.store') }}' :
                    '{{ url('doctors') }}/' + currentDoctor.id"
                method="POST" enctype="multipart/form-data" class="p-6 flex flex-col gap-5">
                @csrf
                <template x-if="mode == 'update'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <x-doctors.model.image_upload />

                <x-doctors.model.fields />

                <x-doctors.model.actions />

            </form>
    </div>
</div>
