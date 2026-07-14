 <tbody class="divide-y divide-gray-50">

     <template x-for="complaint in complaints" :key="complaint.id">

         <tr class="hover:bg-gray-50 transition cursor-pointer" @click="openDetails(complaint)">

             <td class="px-4 py-3">
                 <div class="flex items-center gap-2.5">

                     <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium flex-shrink-0"
                         :class="complaint.color" x-text="complaint.initials">
                     </div>

                     <span class="font-medium text-gray-700" x-text="complaint.patient.name">
                     </span>

                 </div>
             </td>

             <td class="px-4 py-3 text-gray-600 max-w-xs truncate" x-text="complaint.department">
             </td>
             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.doctor.name">
             </td>
             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.issue_type">
             </td>
             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.severity">
             </td>
             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.description">
             </td>
             <td class="px-4 py-3">

                 <span class="text-xs px-2.5 py-1 rounded-full" :class="status(complaint.status).cls"
                     x-text="status(complaint.status).label">
                 </span>

             </td>

             <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.resolution_notes">
             </td>
             <td class="px-4 py-3 text-gray-400 text-xs" x-text="formatDate(complaint.visit_date)">
             </td>
             <td class="px-4 py-3 text-gray-400 text-xs" x-text="formatDate(complaint.resolved_at)">
             </td>

             <td class="px-4 py-3">

                 <span class="text-xs px-2.5 py-1 rounded-full" x-text="formatDate(complaint.created_at)" </span>

             </td>


             <td class="px-4 py-3">

                 <div class="flex items-center gap-3">

                     <button @click.stop="openDetails(complaint)" class="text-teal-500 hover:text-teal-700">

                         <i class="fa fa-eye text-xs"></i>

                     </button>

                     <button @click.stop="confirmDelete(complaint)" class="text-red-400 hover:text-red-600">

                         <i class="fa fa-trash text-xs"></i>

                     </button>

                 </div>

             </td>

         </tr>

     </template>

 </tbody>
