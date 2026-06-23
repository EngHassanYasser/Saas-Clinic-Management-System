{{-- ===== EDIT MODAL ===== --}}
<div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
    @keydown.escape.window="showEditModal = false">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden" @click.outside="showEditModal = false">

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
            <h2 class="font-semibold text-gray-800 text-sm">
                تعديل بيانات الطبيب
            </h2>

            <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-red-500 transition">
                <i class="fa fa-xmark"></i>
            </button>
        </div>

        {{-- FORM --}}
        <template x-if="form">
            <form :action="`/doctors/${form.id}`" method="POST" enctype="multipart/form-data"
                class="p-6 flex flex-col gap-5">

                @csrf
                @method('PUT')

                {{-- IMAGE UPLOAD --}}
                <div class="flex flex-col items-center gap-3">

                    <label class="text-xs text-gray-500 self-start">
                        صورة الطبيب
                    </label>

                    {{-- avatar --}}
                    <div class="relative w-28 h-28">

                        {{-- image or icon --}}
                        <div
                            class="w-28 h-28 rounded-full border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden bg-gray-50 hover:border-teal-400 transition">

                            <template x-if="form.image">
                                <img :src="form.image" class="w-full h-full object-cover">
                            </template>

                            <template x-if="!imagePreview">
                                <i class="fa fa-user text-gray-300 text-3xl"></i>
                            </template>

                        </div>

                        {{-- edit button --}}
                        <label
                            class="absolute bottom-0 right-0 bg-teal-600 text-white w-8 h-8 flex items-center justify-center rounded-full cursor-pointer hover:bg-teal-700 transition shadow">
                            <i class="fa fa-camera text-xs"></i>

                            <input type="file" name="image" accept="image/*" class="hidden"
                                @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            imagePreview = URL.createObjectURL(file);
                                        }
                                   ">
                        </label>

                    </div>

                    <p class="text-[11px] text-gray-400">
                        اضغط على الكاميرا لتغيير الصورة
                    </p>
                </div>

                {{-- FIELDS --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-xs text-gray-500">اسم الطبيب</label>
                        <input type="text" name="name" :value="form.name"
                            class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
                    </div>

                    <div x-data="{ open: false, selected: null }" class="relative">

                        <label class="block text-xs text-gray-500 mb-1.5">
                            التخصص <span class="text-red-400">*</span>
                        </label>

                        <!-- Button Trigger -->
                        <button type="button" @click="open = !open"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white text-left flex justify-between items-center">

                            <span x-text="form.speciality.id ? form.speciality.name : 'اختار التخصص'"></span>

                            <span>▼</span>
                        </button>
                        <!-- Hidden input -->
                        <input type="hidden" name="speciality_id" :value="form.speciality?.id">
                        <!-- Dropdown -->
                        <div x-show="open" @click.outside="open = false"
                            class="absolute z-50 mt-2 w-full max-h-60 overflow-auto border bg-white rounded-lg shadow">

                            <template x-for="speciality in specialities" :key="speciality.id">
                                <div @click="form.speciality = speciality; open = false"
                                    class="px-3 py-2 text-sm hover:bg-teal-50 cursor-pointer" x-text="speciality.name">
                                </div>
                            </template>

                        </div>
                        @error('speciality_id')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">الموبايل</label>
                        <input type="text" name="phone" :value="form.phone"
                            class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">الإيميل</label>
                        <input type="email" name="email" :value="form.email"
                            class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="flex gap-3 pt-2">

                    <button @click="updateDoctor(doctor.id)"
                        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 rounded-lg transition shadow-sm">
                        حفظ التعديلات
                    </button>

                    <button type="button" @click="showEditModal = false;imagePreview=null"
                        class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
                        إلغاء
                    </button>

                </div>

            </form>
        </template>

    </div>
</div>
