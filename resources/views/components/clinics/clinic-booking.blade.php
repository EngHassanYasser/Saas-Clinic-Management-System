  <!-- BOOKING -->
  <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm">

      <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">
          حجز موعد
      </h2>

      <div class="space-y-3">

          <select
              class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
              <option>اختر الدكتور</option>
              <option>د. أحمد محمود</option>
              <option>د. سارة علي</option>
          </select>

          <select
              class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
              <option>نوع الخدمة</option>
              <option>كشف عام</option>
              <option>رسم قلب</option>
              <option>متابعة ضغط</option>
          </select>

          <input type="date"
              class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
              min="{{ date('Y-m-d') }}">

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">

              <span class="text-xs text-center px-3 py-2 rounded-xl bg-gray-100 text-gray-500 border">
                  اختر التاريخ أولاً
              </span>

          </div>

          <button class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
             إضافه حجز
          </button>
          <span class="text-sm text-red-600 mt-1 block text-center font-medium">
             لتأكيد حجزك يرجي دفع 20% من قيمة الكشف من خلال حسابك او من خلال الدفع بفودافون كاش وارساله الايصال لرقم الواتس الخاص بالعياده للتأكيد اليدوي
          </span>

      </div>
  </div>
