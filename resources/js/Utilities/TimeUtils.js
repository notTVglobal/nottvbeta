// utilities/timeUtils.js
/**
 * Creates an array of Date objects representing time slots.
 *
 * This function generates a sequence of time slots starting from a specified
 * start time, continuing for a given number of hours, and spaced apart
 * by a specified interval in minutes.
 *
 * @param {Date} start - The start time from which to generate slots, as a Date object.
 * @param {number} [durationHours=4] - The total duration for which to generate slots, in hours.
 * @param {number} [intervalMinutes=30] - The interval between each slot, in minutes.
 * @returns {Date[]} An array of Date objects, each representing a time slot.
 *
 * Example:
 * If start is `new Date('2024-01-01T08:00:00Z')`, durationHours is 2, and intervalMinutes is 30,
 * the output will be an array of Date objects representing the times:
 * `08:00`, `08:30`, `09:00`, and `09:30`.
 */
export function createTimeSlots(start, durationHours = 4, intervalMinutes = 30) {
    let slots = [];
    for (let i = 0; i < (durationHours * 60) / intervalMinutes; i++) {
        let slotTime = new Date(start.getTime() + i * intervalMinutes * 60000);
        slots.push(slotTime);  // Keep as Date object
    }
    return slots;
}