import state from "./state";
import actions from "./actions";
import helpers from "./helpers";
import getters from "./getters";
import api from "./api";
import globalHelpers from '../global/helpers';
import globalState from '../global/state';

export function clinicServicesForm(serverData) {
    const clinicServices = {
        ...state(serverData),
        ...actions,
        ...helpers,
        ...api,
        ...globalHelpers,
        ...globalState,
    };
    Object.defineProperties(
        clinicServices,
        Object.getOwnPropertyDescriptors(getters),
    );
    return clinicServices;
}
