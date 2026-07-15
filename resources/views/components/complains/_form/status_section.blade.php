<div class="flex gap-2">
    <template x-for="status in statuses" :key="status.value">
        <label class="flex-1 cursor-pointer">
            <input type="radio" name="status" :value="status.value" class="peer hidden">

            <span class="block w-full text-center text-xs py-2 rounded-lg border peer-checked:text-white"
                :class="[status.border, status.text, status.checked]" x-text="status.label">
            </span>
        </label>
    </template>
</div>
