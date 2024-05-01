// utilities/timeUtils.js
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';

dayjs.extend(utc);
dayjs.extend(timezone);

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
 * @param {string} timezone - The timezone in which the time slots will be calculated.
 * @returns {Date[]} An array of Date objects, each representing a time slot.
 *
 * Example:
 * If start is `new Date('2024-01-01T08:00:00Z')`, durationHours is 2, and intervalMinutes is 30,
 * the output will be an array of Date objects representing the times:
 * `08:00`, `08:30`, `09:00`, and `09:30`.
 */
export function createTimeSlots(start, durationHours = 4, intervalMinutes = 30, timezone) {
    console.log(`Creating time slots starting from ${start}, for ${durationHours} hours, every ${intervalMinutes} minutes in timezone ${timezone}.`);
    let slots = [];
    let startTime = dayjs(start).tz(timezone);  // Convert start time to the correct time zone
    for (let i = 0; i < (durationHours * 60) / intervalMinutes; i++) {
        let slotTime = startTime.add(i * intervalMinutes, 'minute').toDate();  // Generate slots in the correct time zone
        slots.push(slotTime);  // Keep as Date object
    }
    return slots;
}
// export function createTimeSlots(start, durationHours = 4, intervalMinutes = 30) {
//     let slots = [];
//     let startTime = dayjs(start).tz(userStore.timezone);  // Convert start time to the correct time zone
//     for (let i = 0; i < (durationHours * 60) / intervalMinutes; i++) {
//         let slotTime = new Date(start.getTime() + i * intervalMinutes * 60000);
//         slots.push(slotTime);  // Keep as Date object
//     }
//     return slots;
// }