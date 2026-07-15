import state from "./state";
import stats from "./stats";
import actions from "./actions";
import getters from "./getters";
import helpers from "./helpers";
import filters from "./filters";
import constants from "./constants";
import globalHelpers from "../global/helpers";
import globalState from '../global/state';

export function complaintsForm(serverData) {
    const complains = {
        ...state(serverData),
        ...actions,
        ...helpers,
        ...stats,
        ...filters,
        ...constants,
        ...globalHelpers,
        ...globalState,
    };
    Object.defineProperties(
        complains,
        Object.getOwnPropertyDescriptors(getters),
    );

    return complains;
}
