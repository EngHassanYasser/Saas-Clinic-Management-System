 <tbody class="divide-y divide-gray-50">
     <template x-for="complaintt in complaints" :key="complaintt.id">
         <tr class="hover:bg-gray-50 transition cursor-pointer" @click="openDetails(complaintt)">
             <td class="px-4 py-3">
                 <div class="flex items-center gap-2.5">
                     <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium flex-shrink-0"
                         :class="complaintt.color" x-text="complaintt.initials">
                     </div>
                     <span class="font-medium text-gray-700" x-text="complaintt.patient?.name || complaintt.patientName">
                     </span>
                 </div>
             </td>
             <td class="px-4 py-3 text-gray-600 max-w-xs truncate" x-text="complaintt.department">
             </td>
             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaintt.doctor.name">
             </td>
             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaintt.issueType">
             </td>
             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaintt.severity">
             </td>
             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaintt.description">
             </td>
             <td class="px-4 py-3">
                 <span class="text-xs px-2.5 py-1 rounded-full" :class="status(complaintt.status).cls"
                     x-text="status(complaintt.status).label">
                 </span>
             </td>
             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaintt.resolutionNotes">
             </td>
             <td class="px-4 py-3 text-gray-400 text-xs" x-text="formatDate(complaintt.visiteDate)">
             </td>
             <td class="px-4 py-3 text-gray-400 text-xs" x-text="formatDate(complaintt.resolvedAt)">
             </td>
             <td class="px-4 py-3">
                 <span class="text-xs px-2.5 py-1 rounded-full" x-text="formatDate(complaintt.createdAt)"></span>
             </td>
             <td class="px-4 py-3">
                 <div class="flex items-center gap-3">
                     <button @click.stop="editComplaint(complaintt)" class="text-red-400 hover:text-red-600">
                         <i class="fa fa-edit text-xs"></i>
                     </button>
                     <form :action="'{{ url('complaints') }}/' + complaintt.id" method="POST">
                         @csrf
                         @method('DELETE')
                         <button type="submit" class="text-red-400 hover:text-red-600">
                             <i class="fa fa-trash text-xs"></i>
                         </button>
                     </form>
                 </div>
             </td>
         </tr>
     </template>
 </tbody>
