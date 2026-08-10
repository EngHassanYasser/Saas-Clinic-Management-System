<div x-show="showModel" x-cloak x-transition.opacity class="fixed inset-0 z-[9999]"
    @keydown.escape.window="showModel = false">

    <div class="absolute inset-0 bg-black/50" @click="showModel = false"> </div>
    <!-- Modal Wrapper -->
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <!-- Modal -->
        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.stop
            class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b bg-white sticky top-0 z-10">
                <h2 class="text-lg font-semibold text-gray-800">
                    إضافة شكوى
                </h2>
                <button type="button" @click="showModel = false"
                    class="w-10 h-10 rounded-full hover:bg-gray-100 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <!-- Body -->
            <div class="flex-1 overflow-y-auto p-6">
                <form
                    :action="mode == 'add' ?
                        '{{ route('complaintts.store') }}' :
                        '{{ url('complaintts') }}/' + currentComplaintt.id"
                    method="POST">
                    @csrf
                    <template x-if="mode == 'update'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <x-complaintts._form />
                </form>
            </div>
        </div>
    </div>
</div>
