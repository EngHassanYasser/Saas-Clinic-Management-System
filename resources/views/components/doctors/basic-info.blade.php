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

          <div>
              <label class="block text-xs text-gray-500 mb-1.5">التخصص <span class="text-red-400">*</span></label>
              <select name="specialty"
                  class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">
                  <option value="">اختر التخصص</option>
                  <option value="general" {{ old('specialty') == 'general' ? 'selected' : '' }}>طب عام</option>
                  <option value="cardiology" {{ old('specialty') == 'cardiology' ? 'selected' : '' }}>قلب وأوعية دموية
                  </option>
                  <option value="orthopedics" {{ old('specialty') == 'orthopedics' ? 'selected' : '' }}>عظام</option>
                  <option value="dermatology" {{ old('specialty') == 'dermatology' ? 'selected' : '' }}>جلدية</option>
                  <option value="pediatrics" {{ old('specialty') == 'pediatrics' ? 'selected' : '' }}>أطفال</option>
                  <option value="neurology" {{ old('specialty') == 'neurology' ? 'selected' : '' }}>مخ وأعصاب
                  </option>
                  <option value="gynecology" {{ old('specialty') == 'gynecology' ? 'selected' : '' }}>نساء وتوليد
                  </option>
                  <option value="ophthalmology"{{ old('specialty') == 'ophthalmology' ? 'selected' : '' }}>عيون</option>
                  <option value="ent" {{ old('specialty') == 'ent' ? 'selected' : '' }}>أنف وأذن وحنجرة
                  </option>
                  <option value="other" {{ old('specialty') == 'other' ? 'selected' : '' }}>أخرى</option>
              </select>
              @error('specialty')
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
              <label class="block text-xs text-gray-500 mb-1.5">مدة الكشف <span class="text-red-400">*</span></label>
              <select name="session_duration" @change="generateSlots()"
                  class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">
                  <option value="">اختر المدة</option>
                  <option value="15" {{ old('session_duration') == '15' ? 'selected' : '' }}>15 دقيقة</option>
                  <option value="20" {{ old('session_duration') == '20' ? 'selected' : '' }}>20 دقيقة</option>
                  <option value="30" {{ old('session_duration') == '30' ? 'selected' : '' }}>30 دقيقة</option>
                  <option value="45" {{ old('session_duration') == '45' ? 'selected' : '' }}>45 دقيقة</option>
                  <option value="60" {{ old('session_duration') == '60' ? 'selected' : '' }}>60 دقيقة</option>
              </select>
              @error('session_duration')
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
