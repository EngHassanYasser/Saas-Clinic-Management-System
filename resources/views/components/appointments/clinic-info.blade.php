      {{-- Clinic & Address --}}
      <div class="flex items-start gap-3">
          <div
              class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0 border border-orange-100">
              <i class="fas fa-hospital text-orange-500 text-lg"></i>
          </div>
          <div>
              <p class="text-xs text-gray-400 mb-0.5">العيادة</p>
              <p class="font-bold text-gray-900 text-sm">{{ $appt['clinic_name'] }}</p>
              <p class="text-xs text-gray-500 mt-0.5 flex items-start gap-1">
                  <i class="fas fa-map-marker-alt text-gray-400 mt-0.5 flex-shrink-0"></i>
                  {{ $appt['address'] }}
              </p>
          </div>
      </div>
