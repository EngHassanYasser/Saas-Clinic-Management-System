<div class="flex items-center gap-2 mb-8">
    <template x-for="(label, index) in steps" :key="index">
        <div class="flex-1 flex items-center gap-2" :class="{ 'flex-none': index === steps.length - 1 }">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shrink-0 transition-colors"
                :class="currentStep >= index + 1 ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-500'"
                x-text="index + 1" @click="currencSection = index +1"></div>
            <span class="text-xs sm:text-sm font-medium hidden sm:block transition-colors"
                :class="currentStep >= index + 1 ? 'text-teal-700' : 'text-gray-400'" x-text="label"></span>
            <div class="h-0.5 flex-1 bg-gray-200 mx-1" x-show="index < steps.length - 1"></div>
        </div>
    </template>
</div>
