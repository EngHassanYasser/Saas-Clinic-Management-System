import getters from "./getters";
import helpers from "./helpers";
import state from "./state";
import actions from "./actions";
import api from "./api";
import globalHelpers from '../global/helpers';

export function doctorsForm(serverData) {
    const doctors = {
        ...helpers,
        ...state(serverData),
        ...actions,
        ...api,
        ...globalHelpers,
    };
    Object.defineProperties(
        doctorsForm,
        Object.getOwnPropertyDescriptors(getters),
    );

    return doctors;
}
