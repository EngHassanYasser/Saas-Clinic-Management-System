export default {
    formatTime12Hours(time) {
        const [hours, minutes] = time.split(':');

        const date = new Date();
        date.setHours(hours, minutes);

        const parts = new Intl.DateTimeFormat('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        }).formatToParts(date);

        const hour = parts.find(p => p.type === 'hour').value;
        const minute = parts.find(p => p.type === 'minute').value;
        const dayPeriod = parts.find(p => p.type === 'dayPeriod').value;

        return `${hour}:${minute}${dayPeriod}`;
    }
};