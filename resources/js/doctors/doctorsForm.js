import getters from "./getters";
import helpers from "./helpers";
import state from "./state";
import actions from "./actions";
import api from "./api";
import globalHelpers from '../global/helpers';
import globalState from '../global/state';
export function doctorsForm(serverData) {
    const doctors = {
        ...helpers,
        ...state(serverData),
        ...actions,
        ...api,
        ...globalHelpers,
        ...globalState,
    };
    Object.defineProperties(
        doctors,
        Object.getOwnPropertyDescriptors(getters),
    );

    return doctors;
}
