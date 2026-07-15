import state from "./state";
import helpers from "./helpers";
import getters from "./getters";
import actions from "./actions";
import globalHelpers from "../global/helpers";
import api from "./api";

export function vicationForm(serverData = {}) {
    const vication = {
        ...state(serverData),
        ...helpers,
        ...actions,
        ...globalHelpers,
        ...api,
    };
     Object.defineProperties(
        vication,
        Object.getOwnPropertyDescriptors(getters),
    );

    return vication;
}
