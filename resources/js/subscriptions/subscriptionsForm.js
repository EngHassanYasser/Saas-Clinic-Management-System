import api from "./api";
import state from "./state";
import getters from "./getters";
import helpers from "./helpers";
import actions from "./actions";
import validation from "./validation";
export function subscriptionsForm(serverData) {
    const subscriptions = {
        ...state(serverData),
        ...actions,
        ...helpers,
        ...api,
        ...validation,
    };
    Object.defineProperties(
        subscriptions,
        Object.getOwnPropertyDescriptors(getters),
    );

    return subscriptions;
}
