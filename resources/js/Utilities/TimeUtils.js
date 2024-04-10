// utilities/timeUtils.js
export function createTimeSlots(start, durationHours = 4, intervalMinutes = 30) {
    let slots = [];
    for (let i = 0; i < (durationHours * 60) / intervalMinutes; i++) {
        let slotTime = new Date(start.getTime() + i * intervalMinutes * 60000);
        slots.push(slotTime.toISOString());
    }
    return slots;
}