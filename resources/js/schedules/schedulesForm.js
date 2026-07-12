import getters from "./getters";
import state from "./state";
import helpers from "./helpers";
import actions from "./actions";
import constants from "./constants";
export function schedulesForm(serverData) {
    const schedules = {
        ...state(serverData),
        ...helpers,
        ...actions,
        ...constants,
    };
    Object.defineProperties(
        schedulesForm,
        Object.getOwnPropertyDescriptors(getters),
    );

    return schedules;
}
