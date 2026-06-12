  {{-- البيانات الأساسية --}}
  <div class="bg-white rounded-xl border border-gray-100 p-5">
      <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
          <i class="fa fa-user text-teal-500"></i> البيانات الأساسية
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

          <div>
              <label class="block text-xs text-gray-500 mb-1.5">اسم الطبيب <span class="text-red-400">*</span></label>
              <input type="text" name="name" value="{{ old('name') }}"
                  class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition"
                  placeholder="د. محمد أحمد">
              @error('name')
                  <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
              @enderror
          </div>

          <div x-data="{ open: false, selected: null }" class="relative">

              <label class="block text-xs text-gray-500 mb-1.5">
                  التخصص <span class="text-red-400">*</span>
              </label>

              <!-- Button Trigger -->
              <button type="button" @click="open = !open"
                  class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white text-left flex justify-between items-center">

                  <span x-text="selected ? selected.name : 'اختار التخصص'"></span>

                  <span>▼</span>
              </button>
              <!-- Hidden input -->
              <input type="hidden" name="speciality_id" :value="selected ? selected.id : ''">
              <!-- Dropdown -->
              <div x-show="open" @click.outside="open = false"
                  class="absolute z-50 mt-2 w-full max-h-60 overflow-auto border bg-white rounded-lg shadow">

                  @foreach ($specialities as $speciality)
                      <div @click="selected = { id: {{ $speciality->id }}, name: '{{ $speciality->name }}' }; open = false"
                          class="px-3 py-2 text-sm hover:bg-teal-50 cursor-pointer">
                          {{ $speciality->name }}
                      </div>
                  @endforeach

              </div>
              @error('speciality_id')
                  <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
              @enderror
          </div>

          <div>
              <label class="block text-xs text-gray-500 mb-1.5">سعر الكشف <span class="text-red-400">*</span></label>
              <div class="relative">
                  <input type="number" name="price" value="{{ old('price') }}" min="0"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition"
                      placeholder="200">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">ج.م</span>
              </div>
              @error('price')
                  <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
              @enderror
          </div>
          <div>
              <label class="block text-xs text-gray-500 mb-1.5">رقم الهاتف <span class="text-red-400">*</span></label>
              <input type="text" name="phone" value="{{ old('phone') }}"
                  class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition"
                  placeholder="+20123456789">
              @error('phone')
                  <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
              @enderror
          </div>
          <div>
              <label class="block text-xs text-gray-500 mb-1.5">البريد الالكتروني</label>
              <input type="text" name="email" value="{{ old('email') }}"
                  class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition"
                  placeholder="example@gmail.com">
              @error('email')
                  <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
              @enderror
          </div>

      </div>
  </div>
