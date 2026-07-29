import actions from "./actions";
import state from "./state";
import getters from "./getters";
export function DashboardApp(serverData) {
    const dashboard = {
        ...actions,
        ...state(serverData),
    };
    Object.defineProperties(
        dashboard,
        Object.getOwnPropertyDescriptors(getters),
    );
    return dashboard
}
