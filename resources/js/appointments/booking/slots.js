export default {

    loadSlots(dateStr) {

        this.slotsLoading = true;

        this.slots = [];

        setTimeout(() => {

            this.slots = this.generateDemoSlots(
                this.selected.doctor,
                dateStr
            );

            this.slotsLoading = false;

        }, 500);

    },

    generateDemoSlots(doctor, dateStr) {

        const day = new Date(dateStr).getDay();

        if (day === 5)
            return [];

        const duration = doctor.duration;

        const slots = [];

        let cursor = 600;

        const shiftEnd = 1020;

        const breakStart = 780;

        const breakEnd = 840;

        const seed =
            (doctor.id * 31 + parseInt(dateStr.replaceAll('-', ''))) % 5;

        const booked = new Set([seed, seed + 3, seed + 6]);

        let i = 0;

        while (cursor + duration <= shiftEnd) {

            if (!(cursor >= breakStart && cursor < breakEnd)) {

                const h = Math.floor(cursor / 60);

                const m = cursor % 60;

                slots.push({

                    start:
                        `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`,

                    label: this.formatTime(h, m),

                    booked: booked.has(i)

                });

                i++;

            }

            cursor += duration;

        }

        return slots;

    }

}