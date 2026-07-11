import getters from "./getters";
import state from "./state";
import helpers from "./helpers";
export function schedulesForm(serverData) {
    const schedules = {
        ...state(serverData),
        ...helpers,
    };
    Object.defineProperties(
        schedulesForm,
        Object.getOwnPropertyDescriptors(getters),
    );

    return schedules;
}
