import Api from "./Api";
import State from "./State";
import Getters from "./Getters";
import Helpers from "./Helpers";
import Actions from "./Actions";
import Validation from "./Validation";
export function SubscriptionApp(serverData) {
    const Subscriptions = {
        ...State(serverData),
        ...Actions,
        ...Helpers,
        ...Api,
        ...Validation,
    };
    Object.defineProperties(
        Subscriptions,
        Object.getOwnPropertyDescriptors(Getters),
    );

    return Subscriptions;
}
