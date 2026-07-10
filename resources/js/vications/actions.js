export default {

    openModal(){

        this.selectedVacation=null;

        this.form={
            doctor_id:"",
            reason:"",
            start_date:"",
            end_date:"",
            notes:""
        };

        this.showVacationModal=true;

    },

    closeModal(){

        this.showVacationModal=false;

    },

    editVacation(v){

        this.selectedVacation=v;

        this.form={

            doctor_id:v.doctor_id,
            reason:v.reason,
            start_date:v.start_date,
            end_date:v.end_date,
            notes:v.notes

        };

        this.showVacationModal=true;

    },

    saveVacation(){

    },

    confirmDelete(v){

        this.selectedVacation=v;

        this.showDeleteModal=true;

    },

    closeDeleteModal(){

        this.selectedVacation=null;

        this.showDeleteModal=false;

    },

    deleteVacation(){

    }

}