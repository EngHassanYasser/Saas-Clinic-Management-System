    <div x-show="showVacationModal" x-transition.opacity @click.self="closeModal()" x-cloak
        class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center" dir="rtl">

        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl">

            <x-vications.model.header />

            <form
                :action="addMode
                    ?
                    '{{ route('vications.store') }}' :
                    '{{ url('vications') }}/' + editVication.id"
                method="POST">
                <template x-if="!addMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                @csrf
                <x-vications._form />

            </form>
        </div>
    </div>
