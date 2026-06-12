 <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

     {{-- RIGHT COL --}}
     <div class="xl:col-span-2 flex flex-col gap-6">
         <x-doctors.basic-info />
         <x-doctors.notes />
     </div>

     {{-- LEFT COL --}}
     <div class="flex flex-col gap-6">

         <x-doctors.image-upload />
         <x-doctors.buttons />
     </div>

 </div>
