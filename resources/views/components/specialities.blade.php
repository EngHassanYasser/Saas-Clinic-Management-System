 {{-- Dropdown List --}}
 <div x-show="open" x-transition @click.outside="open = false"
     class="absolute right-0 left-0 z-50 mt-1 bg-white border rounded-xl
                    shadow-lg max-h-48 overflow-y-auto">

     @foreach ($specialities as $speciality)
         <button type="button"
             @click="selected = '{{ $speciality->name }}'; selectedId = '{{ $speciality->id }}'; open = false"
             class="w-full text-right px-4 py-2.5 text-sm hover:bg-blue-50
                           hover:text-blue-700 transition"
             :class="selectedId == '{{ $speciality->id }}' ? 'bg-blue-50 text-blue-700 font-medium' :
                 'text-gray-700'">
             {{ $speciality->name }}
         </button>
     @endforeach

 </div>
 </div>

 @error('speciality_id')
     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
 @enderror
 </div>
