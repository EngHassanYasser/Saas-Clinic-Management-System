import State from "./State";
import Actions from "./Actions";
export function ProfileApp(serverData) {
    const Profile = {
        ...State(serverData),
        ...Actions,
    };
    return Profile;
}
