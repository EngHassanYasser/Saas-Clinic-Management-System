 <input type="hidden" name="doctor_id" :value="currentDoctor?.id">

 <div class="p-5 flex flex-col gap-4">

     <x-schedules.model.days />
     <x-schedules.model.working_hours />

     <x-schedules.model.break />
     <x-schedules.model.appointment_duration />
     <x-schedules.model.availability />

 </div>

 <x-schedules.model.actions />
