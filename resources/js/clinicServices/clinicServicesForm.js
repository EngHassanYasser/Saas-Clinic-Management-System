import state from "./state";
import actions from "./actions";
import helpers from "./helpers";
import getters from "./getters";
import api from "./api";
export function clinicServicesForm(serverData) {
    const clinicServices = {
        ...state(serverData),
        ...actions,
        ...helpers,
        ...api,
    };
    Object.defineProperties(
        schedulesForm,
        Object.getOwnPropertyDescriptors(getters),
    );
    return clinicServices;
}
