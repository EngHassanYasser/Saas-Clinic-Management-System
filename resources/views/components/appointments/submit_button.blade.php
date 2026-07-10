<button x-show="currencSection === dateTimeSection" type="submit" :disabled="!isReady || submitting"
    class="w-full py-3.5 rounded-xl font-semibold text-white transition-colors"
    :class="(!isReady || submitting) ? 'bg-gray-300 cursor-not-allowed' :
    'bg-teal-600 hover:bg-teal-700 active:bg-teal-800'">
    <span x-show="!submitting"> حجز</span>
    <span x-show="submitting">جاري الحجز...</span>
</button>
