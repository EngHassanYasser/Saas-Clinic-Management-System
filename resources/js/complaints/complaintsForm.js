import state from "./state";
import stats from "./stats";
import actions from "./actions";
import getters from "./getters";
import helpers from "./helpers";
import filters from "./filters";
import globalHelpers from '../global/helpers';

export function complaintsForm(serverData) {
    const complains = {
        ...state(serverData),
        ...actions,
        ...helpers,
        ...stats,
        ...filters,
        ...globalHelpers,
    };
    Object.defineProperties(
        complaintsForm,
        Object.getOwnPropertyDescriptors(getters),
    );

    return complains;
}
