export default function (serverData = {}) {
    return {
        doctors: serverData.doctors,
        weekDays:serverData.weekDays,
        selectedDays: [],
        showModel: false,
        addMode:false,
        editeMode:false,
        editSchedule: null,
        currentDoctor: null, 
    };
}
