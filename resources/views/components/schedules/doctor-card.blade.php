      {{-- DOCTOR CARD --}}
                <div class="bg-white rounded-xl border border-gray-100" x-data="{
                    open: false,
                    showAddModal: false,
                    showEditModal: false,
                    editSchedule: null,
                
                    days: {
                        saturday: 'السبت',
                        sunday: 'الأحد',
                        monday: 'الاثنين',
                        tuesday: 'الثلاثاء',
                        wednesday: 'الأربعاء',
                        thursday: 'الخميس',
                        friday: 'الجمعة',
                    },
                
                    openEdit(schedule) {
                        this.editSchedule = { ...schedule };
                        this.showEditModal = true;
                    }
                }">