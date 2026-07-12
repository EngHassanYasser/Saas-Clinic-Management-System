export default function (serverData = {}) {
    return {
        doctors: serverData.doctors,
        weekDays:serverData.weekDays,
        selectedDays: [],
        open: false,
        showModel: false,
        addMode:false,
        editeMode:false,
        editSchedule: null,
        currentDoctor: null, 
    };
}
