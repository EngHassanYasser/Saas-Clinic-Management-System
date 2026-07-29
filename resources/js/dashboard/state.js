export default function (serverData = {}) {
    return {
        stats: serverData.stats ?? [],
        lastActivities: serverData.lastActivities.data ?? [],
        pagination: serverData.lastActivities.links ?? [],

    };
}
