      {{-- ===== FILTERS ===== --}}
      <div class="bg-white p-4 rounded-2xl border shadow-sm mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

              <div class="relative">
                  <i class="fas fa-search absolute right-3 top-3.5 text-gray-400"></i>
                  <input x-model="search" type="text"
                      class="w-full border border-gray-200 rounded-xl px-10 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                      placeholder="ابحث باسم العيادة أو الاشتراك">
              </div>

              <select x-model="statusFilter"
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                  <option value="">كل الحالات</option>
                  <option value="active">نشط</option>
                  <option value="expired">منتهي</option>
                  <option value="expiring">قريب الانتهاء</option>
              </select>

              <select x-model="planFilter"
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                  <option value="">كل الخطط</option>
                  <option value="basic">Basic</option>
                  <option value="premium">Premium</option>
                  <option value="enterprise">Enterprise</option>
              </select>

          </div>
      </div>
