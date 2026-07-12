import state from "./state";
import stats from "./stats";
import actions from "./actions";
import getters from "./getters";
import helpers from "./helpers";
import filters from "./filters";

export function complaintsForm(serverData) {
    const complains = {
        ...state(serverData),
        ...actions,
        ...helpers,
        ...stats,
        ...filters,
    };
    Object.defineProperties(
        complaintsForm,
        Object.getOwnPropertyDescriptors(getters),
    );

    return complains;
}
