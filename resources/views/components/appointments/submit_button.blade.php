<form action="{{ route('appointments.store') }}" method="POST">
     @csrf
    <input type="hidden" x-model="specialtyId" name="specialityId" />
    <input type="hidden" x-model="serviceId" name="clinicServiceId" />
    <input type="hidden" x-model="clinicId" name="clinicId" />
    <input type="hidden" x-model="doctorId" name="doctorId" />
    <input type="hidden" x-model="selectedSlot" name="slot" />
    <input type="hidden" x-model="visit_date" name="visiteDate" />
    <button x-show="currencSection === dateTimeSection" type="submit"
        class="w-full py-3.5 rounded-xl font-semibold text-white transition-colors"
        :class="!selectedSlot ? 'bg-gray-300 cursor-not-allowed' :
            'bg-teal-600 hover:bg-teal-700 active:bg-teal-800'">
        <span x-show="!submitting"> حجز</span>
        <span x-show="submitting">جاري الحجز...</span>
    </button>
</form>
