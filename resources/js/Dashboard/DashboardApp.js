import Actions from "./Actions";
import State from "./State";
import Getters from "./Getters";
export function DashboardApp(serverData) {
    const Dashboard = {
        ...Actions,
        ...State(serverData),
    };
    Object.defineProperties(
        Dashboard,
        Object.getOwnPropertyDescriptors(Getters),
    );
    return Dashboard
}
