import state from "./state";
import helpers from "./helpers";
import getters from './getters';
import actions from "./actions";
export function vicationForm(serverData ={}) {
    const vication = {
        ...state(serverData),
        ...helpers,
        ...actions,

        ...Object.defineProperties(
            {},
            Object.getOwnPropertyDescriptors(getters)
        )

    };

    return vication;
}
