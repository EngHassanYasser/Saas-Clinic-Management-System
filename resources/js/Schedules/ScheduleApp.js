import Getters from "./Getters";
import State from "./State";
import Helpers from "./Helpers";
import Actions from "./Actions";
import Constants from "./Constants";
export function ScheduleApp(serverData) {
    const Schedules = {
        ...State(serverData),
        ...Helpers,
        ...Actions,
        ...Constants,
    };
    Object.defineProperties(
        Schedules,
        Object.getOwnPropertyDescriptors(Getters),
    );

    return Schedules;
}
