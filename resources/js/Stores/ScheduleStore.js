import { defineStore } from 'pinia'
import { useUserStore } from '@/Stores/UserStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { createTimeSlots } from '@/Utilities/TimeUtils'
import {
    addDays,
    addHours,
    addMonths,
    eachDayOfInterval,
    eachHourOfInterval,
    endOfMonth,
    endOfWeek,
    format,
    getHours,
    getMonth,
    getYear,
    isSameDay,
    isToday,
    isTomorrow,
    isYesterday,
    startOfDay,
    startOfHour,
    startOfMonth,
    startOfWeek,
    subMonths,
} from 'date-fns'

// Import dayjs and its plugins
import dayjs from 'dayjs'
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter'
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore' // To check if the day is the same
import relativeTime from 'dayjs/plugin/relativeTime'
import timezone from 'dayjs/plugin/timezone'
import utc from 'dayjs/plugin/utc'
import weekOfYear from 'dayjs/plugin/weekOfYear' // For week start and end calculations
import advancedFormat from 'dayjs/plugin/advancedFormat' // For more complex formatting options

// Extend dayjs with the plugins
dayjs.extend(relativeTime)
dayjs.extend(timezone)
dayjs.extend(weekOfYear)
dayjs.extend(advancedFormat)
dayjs.extend(utc)
dayjs.extend(isSameOrAfter)
dayjs.extend(isSameOrBefore)

function convertScheduleToTimezone(scheduleData) {
    const userStore = useUserStore() // Access the UserStore

    return scheduleData.data.map(item => {
        // Convert top-level start_time and end_time using UserStore methods
        // console.log(`Original startTime for ${item.id}: ${item.startTime}`);
        const startTimeInUserTz = item.startTime ? userStore.formatDateTimeFromUtcToUserTimezone(item.startTime, 'YYYY-MM-DD HH:mm:ss') : null
        const endTimeInUserTz = item.endTime ? userStore.formatDateTimeFromUtcToUserTimezone(item.endTime, 'YYYY-MM-DD HH:mm:ss') : null
        // Add debug logging to help trace conversion issues or confirm correct conversions
        // console.log(`Converted startTime for ${item.id}: ${startTimeInUserTz}`);

        return {
            ...item,
            startTime: startTimeInUserTz,
            endTime: endTimeInUserTz,
            timezone: userStore.timezone,
        }
    })
}

function convertDateTimeToGridTime(dateTime, format = 'YYYY-MM-DD HH:mm:ss') {
    const userStore = useUserStore() // Access the UserStore
    return userStore.formatDateTimeFromUtcToUserTimezone(dateTime, format)
}

// Helper function to get dates within the next 6 hours from viewingWindowStart
function getUpcomingContentDates(viewingWindowStart) {
    let dates = []
    let start = dayjs(viewingWindowStart) // Ensure viewingWindowStart is a Day.js object

    for (let i = 0; i < 6; i++) {
        // Add i hours to the start time, each time creating a new Day.js object
        dates.push(start.add(i, 'hour').toDate()) // Convert to Date if necessary; otherwise keep as Day.js object
    }

    return dates
}


// function resolveSchedulingConflicts(shows) {
//     // Sort shows by start time, then by priority for shows with the same start time
//     const sortedShows = shows.sort((a, b) => {
//         const startTimeComparison = new Date(a.startTime) - new Date(b.startTime)
//         if (startTimeComparison === 0) { // If start times are the same
//             return a.priority - b.priority // Compare by priority
//         }
//         return startTimeComparison
//     })
//
//     const resolvedShows = []
//     const showsByStartTime = {}
//
//     // Group shows by their start time
//     sortedShows.forEach(show => {
//         const startTime = new Date(show.startTime).toISOString() // Ensure it's in ISO format for keying
//         if (!showsByStartTime[startTime]) {
//             showsByStartTime[startTime] = []
//         }
//         showsByStartTime[startTime].push(show)
//     })
//
//     console.log("Shows grouped by start time:", showsByStartTime);
//
//     // For each start time, select the show with the highest priority (lowest priority number)
//     Object.values(showsByStartTime).forEach(group => {
//         if (group.length > 1) {
//             console.log("Conflict at time", group[0].startTime, "- resolving with:", group[0]);
//             // If there are conflicts, push only the show with the highest priority
//             resolvedShows.push(group[0]) // Assuming the group is already sorted by priority
//         } else {
//             // No conflict, push the single show
//             resolvedShows.push(group[0])
//         }
//     })
//
//     return resolvedShows
// }

// function resolveSchedulingConflicts(shows) {
//     // Convert start times to timestamps for sorting and conflict detection
//     shows.forEach(show => {
//         show.timestamp = new Date(show.startTime).getTime() // Add a timestamp property
//     })
//
//     // Sort shows by timestamp, then by priority for shows with the same timestamp
//     const sortedShows = shows.sort((a, b) => {
//         const startTimeComparison = a.timestamp - b.timestamp
//         if (startTimeComparison === 0) { // If timestamps are the same
//             return a.priority - b.priority // Compare by priority
//         }
//         return startTimeComparison
//     })
//
//     const resolvedShows = []
//     const showsByStartTime = {}
//
//     // Group shows by their timestamp
//     sortedShows.forEach(show => {
//         if (!showsByStartTime[show.timestamp]) {
//             showsByStartTime[show.timestamp] = []
//         }
//         showsByStartTime[show.timestamp].push(show)
//     })
//
//     // console.log('Shows grouped by timestamp:', showsByStartTime)
//
//     // For each group of shows with the same start time, select the show with the highest priority (lowest priority number)
//     Object.values(showsByStartTime).forEach(group => {
//         if (group.length > 1) {
//             console.log('Conflict at time', group[0].startTime, '- priority goes to:', group[0].content.name)
//             // If there are conflicts, push only the show with the highest priority
//             resolvedShows.push(group[0]) // Assuming the group is already sorted by priority
//         } else {
//             // No conflict, push the single show
//             resolvedShows.push(group[0])
//         }
//     })
//
//     return resolvedShows.map(show => {
//         // Return the show in its original format, without the temporary timestamp property
//         const {timestamp, ...originalShow} = show
//         return originalShow
//     })
// }

//
// function adjustShowsForGrid(shows) {
//     const userStore = useUserStore()
//     const appSettingStore = useAppSettingStore()
//     const timezone = userStore.timezone || 'UTC'
//     const now = dayjs()
//     const startOfCurrentHalfHour = now.subtract(now.minute() % 30, 'minutes').startOf('minute').tz(timezone).toDate()
//     const cols = appSettingStore.isVerySmallScreen ? 4 : appSettingStore.isSmallScreen ? 6 : 8
//
//     // Make sure the timeSlots are logged to verify their correctness
//     console.log('Time Slots:', this.timeSlots.map(slot => new Date(slot).toString()))
//
//
//     // Sort shows by their start times to ensure they are processed in chronological order
//     shows.sort((a, b) => new Date(a.startTime) - new Date(b.startTime))
//
//     // Initialize an array to keep track of column and row availability
//     let colOccupancy = new Array(cols).fill(null).map(() => [])
//
//     // Processing each show for grid placement
//     return shows.map(show => {
//         const showStart = new Date(show.startTime)
//         const showEnd = new Date(showStart.getTime() + show.durationMinutes * 60000)
//
//         let adjustedStart = (showStart < startOfCurrentHalfHour && showEnd > startOfCurrentHalfHour) ? startOfCurrentHalfHour : showStart
//         let effectiveEnd = showEnd > now.toDate() ? now.toDate() : showEnd // Ensure end is no later than now
//
//         let slotIndex = this.timeSlots.findIndex(slot => adjustedStart >= slot && adjustedStart < new Date(slot.getTime() + 30 * 60000))
//         if (slotIndex === -1) {
//             slotIndex = adjustedStart < this.timeSlots[0] ? 0 : this.timeSlots.length - 1 // Handle out of range start times
//         }
//
//         let durationInMilliseconds = effectiveEnd.getTime() - adjustedStart.getTime()
//         let totalMinutes = durationInMilliseconds / (30 * 60000)
//         let span = Math.ceil(totalMinutes)
//         span = Math.max(1, span) // Ensure span is at least 1
//         span = slotIndex + span > cols ? cols - slotIndex : span // Adjust span to not exceed total columns
//
//         // Find the minimum unoccupied row
//         let minRow = 1
//         for (let i = slotIndex; i < slotIndex + span; i++) {
//             while (colOccupancy[i].includes(minRow)) {
//                 minRow++
//             }
//         }
//
//         // Register this show in the occupied columns
//         for (let i = slotIndex; i < slotIndex + span; i++) {
//             colOccupancy[i].push(minRow)
//         }
//
//         console.log(`Show: ${show.content.name}, Start: ${showStart}, Adjusted Start: ${adjustedStart}, End: ${showEnd}, Now: ${now.toDate()}, Slot Index: ${slotIndex}, Span: ${span}, Row: ${minRow}`)
//         return {
//             ...show,
//             gridStart: slotIndex + 1,
//             gridSpan: span,
//             gridRow: minRow,  // Assign shows to rows based on availability and priority
//         }
//     }).filter(show => show.gridSpan > 0)
// }


// function adjustShowsForGrid(shows, timeSlots) {
//     const userStore = useUserStore()
//     const appSettingStore = useAppSettingStore()
//
//     const timezone = userStore.timezone || 'UTC'
//     const now = dayjs();
//     // const minutes = now.minute();
//     // const startOfCurrentHalfHour = now.subtract(minutes % 30, 'minute').startOf('minute').tz(timezone).toDate();
//     const startOfCurrentHalfHour = now.subtract(now.minute() % 30, 'minutes').startOf('minute').tz(timezone).toDate();
//     // const startOfCurrentHalfHour = now.subtract(minutes % 30, 'minute').startOf('minute').tz(timezone);
//     const cols = appSettingStore.isVerySmallScreen ? 4 : appSettingStore.isSmallScreen ? 6 : 8;
//
//     // Initialize an array to keep track of column availability
//     let colOccupancy = new Array(cols).fill(null);
//
//     // Sort shows by start time to ensure proper handling of overlaps
//     shows.sort((a, b) => new Date(a.startTime) - new Date(b.startTime));

// return shows.map(show => {
//     const showStart = new Date(show.startTime);
//     const showEnd = new Date(showStart.getTime() + show.durationMinutes * 60000);
//
//     // Adjusting showStart if the show is ongoing but started before the current grid view
//     let adjustedStart = showStart < startOfCurrentHalfHour && now < showEnd ? startOfCurrentHalfHour : showStart;
//
//     // Calculating the grid start index
//     let slotIndex = timeSlots.findIndex(slot => adjustedStart >= slot && adjustedStart < new Date(slot.getTime() + 30 * 60000));
//     if (slotIndex === -1) {
//         slotIndex = adjustedStart < timeSlots[0] ? 0 : timeSlots.length - 1; // Place at the start or the end based on timing
//     }
//
//     // Calculating the duration to display in the grid based on current time
//     let span = Math.ceil(((showEnd.getTime() > now.toDate().getTime() ? now.toDate().getTime() : showEnd.getTime()) - adjustedStart.getTime()) / (30 * 60000));
//     span = Math.max(1, span); // Ensure at least one grid slot is used
//
//     // Ensure the span does not exceed available columns from the slot index
//     span = slotIndex + span > cols ? cols - slotIndex : span;
//
//     // Set or clear show placement in grid
//     for (let i = slotIndex; i < slotIndex + span && i < cols; i++) {
//         if (colOccupancy[i] !== null) {
//             // Logic to handle overlapping shows, if necessary
//             colOccupancy[i] = null;
//         }
//         colOccupancy[i] = { gridStart: slotIndex + 1, gridSpan: span, show };
//     }
//
//     console.log(`Show placement: Show: ${show.content.name}, Start: ${showStart}, Adjusted Start: ${adjustedStart}, End: ${showEnd}, Slot Index: ${slotIndex}, Span: ${span}`);
//
//     return {
//         ...show,
//         gridStart: slotIndex + 1,
//         gridSpan: span,
//     };
// }).filter(show => show.gridSpan > 0);

//     return shows.map(show => {
//         const showStart = new Date(show.startTime); // Parse start time to a Date object
//         const showEnd = new Date(show.startTime);  // Initialize end time as a copy of start time
//         showEnd.setMinutes(showStart.getMinutes() + show.durationMinutes);
//
//         let span = Math.ceil(show.durationMinutes / 30)
//
//         // Find the index of the slot that intersects or follows the show start
//         let slotIndex = timeSlots.findIndex(slot => showStart < new Date(slot.getTime() + 30 * 60000));
//         if (slotIndex === -1) {
//             // If no slot is found, and the show starts before the first slot and ends after it, start from the first column.
//             slotIndex = 0; // Default to the first column if no exact match is found
//         }
//
//         if (showStart < startOfCurrentHalfHour && showEnd > startOfCurrentHalfHour) {
//             // Adjust the slotIndex to the first column and span based on the remaining time if show starts before but ends after startOfCurrentHalfHour
//             slotIndex = 0;
//             const effectiveStart = Math.max(showStart.getTime(), startOfCurrentHalfHour.getTime());
//             const remainingDuration = (showEnd.getTime() - effectiveStart) / 60000; // Convert to minutes
//             span = Math.ceil(remainingDuration / 30);
//         }
//
//         // const slotIndex = timeSlots.findIndex(slot => showStart >= slot && showStart < new Date(slot.getTime() + 30 * 60000));
//
//         // const slotIndex = timeSlots.findIndex(slot => {
//         //     let slotEnd = new Date(slot.getTime() + 30 * 60000)
//         //     return showStart >= slot && showStart < slotEnd
//         // })
//
//         // if (slotIndex + span > cols) { // Ensure the span does not exceed the number of available columns
//         //     span = cols - slotIndex
//         // }
//         //
//         //
//         // if (slotIndex + span > cols) { // Ensure the span does not exceed the number of available columns
//         //     span = cols - slotIndex;
//         // }
//
//         // Adjust col span based on overlapping shows
//     //     for (let i = slotIndex; i < Math.min(slotIndex + span, cols); i++) {
//     //         if (colOccupancy[i] != null) {
//     //             if (showStart.getTime() === startOfCurrentHalfHour.getTime()) {
//     //                 colOccupancy[i] = null; // Clear earlier show
//     //             } else {
//     //                 colOccupancy[i].gridSpan = Math.min(colOccupancy[i].gridSpan, i - colOccupancy[i].gridStart + 1);
//     //             }
//     //         }
//     //         colOccupancy[i] = { gridStart: slotIndex + 1, gridSpan: span, show };
//     //     }
//     //     // console.log('time slots: ' + timeSlots)
//     //     // console.log(`Adjusting show for grid placement. Show Start: ${showStart}, Show End: ${showEnd}, Duration: ${show.durationMinutes}, Calculated Slot Index: ${slotIndex}, Span: ${span}`)
//     //     console.log(`Adjusting show for grid placement. Show: ${show.content.name}, Show Start: ${showStart}, Duration: ${show.durationMinutes}, Calculated Slot Index: ${slotIndex}, Span: ${span}`)
//     //
//     //     // Return the show with additional grid positioning information
//     //     return {
//     //         ...show,
//     //         gridStart: slotIndex + 1, // Grid positioning is often 1-based in CSS
//     //         gridSpan: span,
//     //     }
//     // }).filter(show => {
//     //     const endCol = show.gridStart + show.gridSpan - 1;
//     //     return colOccupancy.slice(show.gridStart - 1, endCol).every(cell => cell && cell.show.id === show.id);
//     // });
// }


// timeSlots.forEach((slot, index) => {
//     if (!(slot instanceof Date)) {
//         console.error('Slot is not a Date object:', slot);
//         return; // Skip this iteration if `slot` is not a Date object
//     }
//
//     const slotStart = slot;
//     const slotEnd = new Date(slot.getTime() + 30 * 60000); // Each slot is 30 minutes long
//
//     const showExistsInSlot = showsWithPlacement.some(show => {
//         const showStart = new Date(show.startTime);
//         const showEnd = new Date(showStart.getTime() + show.durationMinutes * 60000);
//         const overlaps = (slotStart <= showEnd && slotEnd > showStart) || (showStart <= slotEnd && showEnd > slotStart);
//         return overlaps;
//     });
//
//     if (!showExistsInSlot) {
//         const formattedStartTime = dayjs(slotStart).format('YY-MM-DD HH:mm:ss');
//         gridItems.push({
//             placeholder: true,
//             startTime: formattedStartTime,
//             priority: 1,
//             gridStart: index + 1, // Ensure gridStart aligns correctly with the CSS grid (1-based index)
//             gridSpan: 1,
//             content: { name: 'Nothing scheduled.' },
//         });
//     }
// });
//
// const combinedShows = [...showsWithPlacement, ...gridItems];
// combinedShows.sort((a, b) => new Date(a.startTime) - new Date(b.startTime));
// return combinedShows;
// }

// Helper function to create a placeholder with a given start and span
// function createPlaceholder(start, span, row) {
//     return {
//         placeholder: true,
//         startTime: 'Placeholder',
//         priority: 0,
//         gridStart: start,
//         gridSpan: span,
//         gridRow: row,
//         content: {name: 'Nothing scheduled.'},
//     }
// }
//
// function createBlankSpotPlaceholder(column, span, row) {
//     return {
//         placeholder: true,
//         startTime: 'BlankSpot',
//         priority: 0,
//         gridStart: column,
//         gridSpan: span,
//         gridRow: row,
//         content: {name: 'Blank Spot'},
//     }
// }

// function fillEmptySlotsWithPlaceholders(showsWithPlacement, timeSlots) {
//     const gridItems = []
//
//     timeSlots.forEach((slot, index) => {
//         // Ensure every slot is a Date object, convert if necessary
//         // console.log('type of slot: ' + slot, typeof slot) // Check what `slot` contains and its type
//
//         // console.log('Debugging time slots and shows alignment');
//         if (!(slot instanceof Date)) {
//             console.error('slot is not a Date object:', slot)
//             return // Skip this iteration if `slot` is not a Date object
//         }
//
//         const slotStart = slot
//         const slotEnd = new Date(slot.getTime() + 30 * 60000); // Assuming each slot is 30 minutes long
//         // console.log(`Checking slot from ${slotStart} to ${slotEnd}`);
//
//         const showExistsInSlot = showsWithPlacement.some(show => {
//             const showStart = new Date(show.startTime);
//             const showEnd = new Date(showStart.getTime() + show.durationMinutes * 60000);
//             // console.log(`Comparing against show from ${showStart} to ${showEnd}`);
//             // return slotStart < showEnd && slotEnd > showStart;
//             // Checking overlap more carefully, including exact boundary conditions
//             const overlaps = (slotStart <= showEnd && slotEnd > showStart) || (showStart <= slotEnd && showEnd > slotStart);
//             if (overlaps) {
//                 // console.log(`Show overlaps with slot: ${show.id}`);
//             }
//             return overlaps;
//
//         });
//
//         if (!showExistsInSlot) {
//             // Format the slot start time as YY-MM-DD HH:MM:ss
//             const formattedStartTime = dayjs(slotStart).format('YY-MM-DD HH:mm:ss');
//             console.log(`No show found for slot starting at ${formattedStartTime}, inserting placeholder.`);
//
//             // Insert a placeholder show for this slot
//             gridItems.push({
//                 placeholder: true,
//                 startTime: formattedStartTime,
//                 priority: 1,
//                 gridStart: index + 1,
//                 gridSpan: 1,
//                 content: {name: 'Nothing scheduled.'},
//             })
//         }
//     })
//
//     // Merge and sort the grid items by their start time/gridStart to maintain chronological order
//     // return [...showsWithPlacement, ...gridItems].sort((a, b) => a.gridStart - b.gridStart)
//
//     // Merge and sort the grid items by their start time/gridStart to maintain chronological order
//     const combinedShows = [...showsWithPlacement, ...gridItems];
//     combinedShows.sort((a, b) => new Date(a.startTime) - new Date(b.startTime));
//     return combinedShows;
// }

const getTimeZone = () => {
    const userStore = useUserStore()
    // This function should return the timezone of the user.
    // This could be dynamic based on the user's settings or browser settings.
    // return Intl.DateTimeFormat().resolvedOptions().timeZone;
    return userStore.timezone
}

const initialState = () => ({
    baseTime: dayjs().tz(getTimeZone()).toDate(),
    currentHalfHour: dayjs().tz(getTimeZone()).startOf('hour').add(dayjs().minute() >= 30 ? 30 : 0, 'minute').toDate(),
    fourHoursLater: dayjs().tz(getTimeZone()).startOf('hour').add(dayjs().minute() >= 30 ? 30 : 0, 'minute').add(4, 'hour').toDate(),
    viewingWindowStart: dayjs().tz(getTimeZone()).startOf('hour').toDate(),
    currentMonth: dayjs().tz(getTimeZone()).startOf('month').toDate(),
    selectedDay: dayjs().tz(getTimeZone()).toDate(),
    currentWeekStart: dayjs().tz(getTimeZone()).startOf('week').toDate(),
    currentWeekEnd: dayjs().tz(getTimeZone()).endOf('week').toDate(),
    nextFourHoursOfContent: [],
    nextFourHoursOfContentWithPlaceholders: [],
    // nextFourHoursWithHalfHourIntervals: [],
    // viewingMode: 'automatic', // or 'userSelected'
    fiveDaySixHourSchedule: [], // Holds the schedule shows 5 day / 6 hour structured data
    todaysContent: [],
    weeklyContent: [],
    dataFetchLog: [],
    scheduleIsLoading: false,
    savingToSchedule: false,
    slotIntervalMinutes: 30,
    mediumScreenSlotHours: 4, // 4 hours = 8 slots
    smallScreenSlotHours: 2, // 2 hours = 4 slots
    verySmallScreenSlotHours: 1, // 1 hour = 2 slots
    timeSlots: null,
})

export const useScheduleStore = defineStore('scheduleStore', {
    state: initialState,
    actions: {
        resetAll() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        reset() {
            this.viewingWindowStart = dayjs().tz(getTimeZone()).startOf('hour').toDate()
            this.currentMonth = dayjs().tz(getTimeZone()).startOf('month').toDate()
            this.selectedDay = dayjs().tz(getTimeZone()).toDate()
        },
        async setSelectedDay(day) {
            // Ensure that 'day' is a Day.js object, convert if coming as a native Date or string
            const selectedDay = dayjs(day)

            // Set the selected day
            this.selectedDay = selectedDay.toDate() // Convert back to Date if necessary; otherwise keep as Day.js object

            // Explicitly set the viewingWindowStart to 4 AM for the selected day
            this.viewingWindowStart = selectedDay.startOf('day').add(4, 'hours').toDate()

            // Set the start and end of the week based on the selected day
            this.currentWeekStart = selectedDay.startOf('week').toDate() // Consider week starting on Sunday
            this.currentWeekEnd = selectedDay.endOf('week').toDate() // Consider week ending on Saturday

            // Use the updated fetch logic
            await this.fetchWeekDataIfNeeded()
        },
        setSelectedDayToToday(day) {
            const now = dayjs()  // Create a Day.js object for the current date and time

            // Set selectedDay to the current date and time
            this.selectedDay = now.toDate() // Convert back to Date if necessary; otherwise keep as Day.js object

            // Set viewingWindowStart to the start of the current hour using Day.js
            this.viewingWindowStart = now.startOf('hour').toDate()
        },
        async changeDay(days) {
            const currentTimeZone = getTimeZone() // or however you obtain the timezone
            // Extract the time component from the current viewing window start
            const currentTime = dayjs(this.viewingWindowStart).hour() * 60 + dayjs(this.viewingWindowStart).minute()

            // Create a new day and set it to the start of that day
            let newDay = dayjs(this.viewingWindowStart).tz(currentTimeZone).add(days, 'day').startOf('day')

            // Set the time back to the original time
            newDay = newDay.add(currentTime, 'minute')

            // Depending on your application's needs, you may or may not need to convert it back to a JavaScript Date object
            // If you need a Date object:
            this.viewingWindowStart = newDay.toDate()

            // If you can use dayjs objects directly (preferred if possible):
            this.viewingWindowStart = newDay

            // // Check if the week of the new day is already loaded
            // const weekStart = startOfWeek(newDay, {weekStartsOn: 0});
            // const weekEnd = endOfWeek(newDay, {weekStartsOn: 0});

            // Use the updated centralized fetch logic without redundant checks
            await this.fetchWeekDataIfNeeded()

            // // Check for the need to load data for the new week
            // if (this.needsDataForWeek(weekStart, weekEnd)) {
            //     await this.loadWeekFromDate(newDay).catch(error => {
            //         console.error("Failed to load data for the new week:", error);
            //     });
            // }

            // // Check for the need to load data for the new week
            // if (!this.needsDataForWeek()) {
            //     // Data for the current week has already been loaded; skip fetching
            //     console.log("Data for the current week has already been loaded; skipping redundant fetch.");
            //     return;
            // }
            //
            // // Proceed with data fetching if needed
            // await this.checkAndFetchForUpcomingContent();
        },
        async shiftHours(hours) {
            // Shift the viewing window by the specified number of hours
            this.viewingWindowStart = dayjs(this.viewingWindowStart).add(hours, 'hour').toDate()

            // Set the current week start and end based on the new viewing window start
            this.currentWeekStart = dayjs(this.viewingWindowStart).startOf('week').toDate()
            this.currentWeekEnd = dayjs(this.viewingWindowStart).endOf('week').toDate()

            // If the day has changed, update selectedDay and the week's range
            if (!dayjs(this.viewingWindowStart).isSame(dayjs(this.selectedDay), 'day')) {
                this.selectedDay = dayjs(this.viewingWindowStart).startOf('day').toDate()
            }

            // Use the updated fetch logic
            await this.fetchWeekDataIfNeeded()
        },
        isElevenPM(date) {
            // Convert the date to a Day.js object if it's not already one
            const time = dayjs(date)
            // Check if the hour is 23 (11 PM)
            return time.hour() === 23
        },
        // Actions to change the month
        async subtractMonth() {
            // Convert currentMonth to a Day.js object if it's not already and subtract one month
            this.currentMonth = dayjs(this.currentMonth).subtract(1, 'month').toDate()

            try {
                // Pass the new currentMonth to setSelectedDay
                await this.setSelectedDay(this.currentMonth)
                console.log('Set selected day based on current month:', this.currentMonth)
            } catch (error) {
                console.error(`Failed to set selected day based on current month ${this.currentMonth}:`, error)
            }
        },
        async addMonth() {
            // Convert currentMonth to a Day.js object if it's not already and add one month
            this.currentMonth = dayjs(this.currentMonth).add(1, 'month').toDate()

            try {
                // Pass the new currentMonth to setSelectedDay
                await this.setSelectedDay(this.currentMonth)
                console.log('Set selected day based on current month:', this.currentMonth)
            } catch (error) {
                console.error(`Failed to set selected day based on current month ${this.currentMonth}:`, error)
            }
        },
        async fetchFiveDaySixHourSchedule() {
            console.error('fetchFiveDaySixHourSchedule')
            try {
                const userStore = useUserStore()
                const response = await axios.get('/api/schedule')

                // Fallback to response timezone if userStore.timezone is not set
                const timezone = userStore.timezone || response.data.userTimezone || 'UTC' // Additional fallback to 'UTC'

                // Convert the entire schedule, including nested recurrenceDetails, to the user's timezone
                this.fiveDaySixHourSchedule = convertScheduleToTimezone(response.data, timezone)
                console.error('fetchFiveDaySixHourSchedule', response.data)
            } catch (error) {
                console.error('Failed to load schedule shows:', error)
                // Handle the error state as needed, e.g., setting an error state property
            }
        },
        async fetchTodaysContent() {
            try {
                const userStore = useUserStore()
                const response = await axios.get('/api/schedule/today')

                // Fallback to response timezone if userStore.timezone is not set
                const timezone = userStore.timezone || response.data.userTimezone || 'UTC' // Additional fallback to 'UTC'

                this.todaysContent = convertScheduleToTimezone(response.data, timezone)
            } catch (error) {
                console.error('Failed to fetch today\'s content:', error)
            }
        },
        async preloadWeeklyContent() {
            // Use the current date to preload content for the current week
            const currentDate = dayjs() // Creates a Day.js object representing the current date and time

            try {
                // Call loadWeekFromDate with the current Day.js date object, converted to a Date if necessary
                await this.loadWeekFromDate(currentDate.toDate()) // Convert to JavaScript Date if loadWeekFromDate expects a Date object
            } catch (error) {
                console.error('Failed to preload weekly content:', error)
            }
        },
        async loadWeekFromDate(date) {
            let formattedDate = null // Declare formattedDate outside of the try block
            this.scheduleIsLoading = true
            try {
                const userStore = useUserStore()
                // Ensure the date is in UTC format for the request
                // console.log('Date before formatted: ' + date)
                // Convert date to Day.js object and format it in ISO string with UTC
                const dayDate = dayjs(date)
                const fullISODate = dayDate.toISOString()
                // console.log(`Loading week data for date in UTC: ${fullISODate}`)

                // Fetch the week's schedule data
                // Send the dateTime and timezone as a JSON object in a POST request
                const response = await axios.post(`/api/schedule/week/${fullISODate}`)

                // const formattedDate = date.toISOString().split('T')[0];
                // Log and error handling
                const formattedDate = dayDate.format('YYYY-MM-DD') // For potential error messages and logging
                // console.log(`Loading week data for date: ${formattedDate}`); // Log the date being requested
                //
                // const response = await axios.get(`/api/schedule/week/${formattedDate}`);
                // console.log('Received response:', response.data) // Log the raw response data

                // Fallback to response timezone if userStore.timezone is not set
                const timezone = userStore.timezone || response.data.userTimezone || 'UTC' // Additional fallback to 'UTC'
                // console.log(`Using timezone: ${timezone}`) // Log the timezone being used

                const newData = convertScheduleToTimezone(response.data, timezone) // Ensure you are accessing the correct data property from the response

                // Merge newData into weeklyContent, avoiding duplicates
                this.weeklyContent = [...this.weeklyContent, ...newData].filter((value, index, self) =>
                        index === self.findIndex((t) => (
                            t.id === value.id && t.startTime === value.startTime
                        )),
                )

                // Optionally update fetch logs or perform additional state updates
                this.updateFetchLogs(date)

                this.scheduleIsLoading = false
            } catch (error) {
                console.error(`Failed to load content for week starting ${formattedDate}:`, error)
                this.scheduleIsLoading = false
            }
        },
        updateFetchLogs(date) {
            // Convert date to a Day.js object if it's not already one
            const dayDate = dayjs(date)

            // Use Day.js to calculate the start and end of the week
            const weekStart = dayDate.startOf('week').toISOString() // Assumes the week starts on Sunday
            const weekEnd = dayDate.endOf('week').toISOString() // Assumes the week ends on Saturday
            const fetchTime = dayjs().toISOString() // Current time in ISO format

            // Find existing log entry for the week
            const existingLogIndex = this.dataFetchLog.findIndex(log =>
                log.weekStart === weekStart && log.weekEnd === weekEnd)

            if (existingLogIndex !== -1) {
                // Update the last fetch time if the log already exists
                this.dataFetchLog[existingLogIndex].lastFetch = fetchTime
            } else {
                // Add a new log entry if it does not exist
                this.dataFetchLog.push({weekStart, weekEnd, lastFetch: fetchTime})
            }
        },
        needsDataForWeek() {
            // Helper function to format dates to 'YYYY-MM-DD' for easier comparison
            const formatISODate = (date) => dayjs(date).format('YYYY-MM-DD')

            // Current week range in 'YYYY-MM-DD' format
            const weekStartStr = formatISODate(this.currentWeekStart)
            const weekEndStr = formatISODate(this.currentWeekEnd)

            // Enhanced logging for debugging
            console.log(`Current week range: ${weekStartStr} to ${weekEndStr}`)
            console.log('Existing data fetch log entries:', this.dataFetchLog)

            // Iterate through the fetch log to check if the current week has been fetched
            const weekHasBeenFetched = this.dataFetchLog.some(log => {
                // Convert log dates to 'YYYY-MM-DD' format for comparison
                const logWeekStartStr = formatISODate(log.weekStart)
                const logWeekEndStr = formatISODate(log.weekEnd)

                // Log each comparison for insight
                console.log(`Comparing to fetched range: ${logWeekStartStr} to ${logWeekEndStr}`)

                return logWeekStartStr <= weekStartStr && logWeekEndStr >= weekEndStr
            })

            // Log the final determination
            console.log(`Week from ${weekStartStr} to ${weekEndStr} has ${weekHasBeenFetched ? '' : 'not '}been fetched.`)

            return !weekHasBeenFetched
        },
        calculateExtendedEndForUpcomingContent() {
            // Convert viewingWindowStart to a Day.js object if it's not already one
            const baseStartTime = dayjs(this.viewingWindowStart)

            // Extend by 6 hours to cover the upcoming content span
            // Day.js handles date and time addition cleanly, returning a new Day.js object
            const extendedEndTime = baseStartTime.add(6, 'hour')

            // Return the extended end time as a Date object, if needed elsewhere as a Date
            return extendedEndTime.toDate()
        },
        // Function to check for and fetch missing upcoming content
        async checkAndFetchForUpcomingContent() {
            const upcomingDates = getUpcomingContentDates(this.viewingWindowStart) // Assuming this returns Day.js objects
            const now = dayjs()
            const fifteenMinutesAgo = now.subtract(15, 'minutes')

            for (const date of upcomingDates) {
                const dateString = date.format('YYYY-MM-DD')  // Day.js format for 'YYYY-MM-DD'
                const contentCoverageAndFreshness = this.weeklyContent.some(content => {
                    const contentDate = dayjs(content.startTime).format('YYYY-MM-DD')  // Convert and compare as 'YYYY-MM-DD'
                    const lastFetchedTime = this.dataFetchLog[dateString]
                    const isFresh = lastFetchedTime && dayjs(lastFetchedTime) > fifteenMinutesAgo
                    return dateString === contentDate && isFresh
                })

                if (!contentCoverageAndFreshness) {
                    // This date needs data fetching
                    await this.fetchDataAndUpdateLog(dateString, date.toDate()) // Pass as Date if needed, or adjust downstream functions to accept Day.js objects
                    break // Assuming you fetch data for the week, so no need to check further dates within the same week
                }
            }
        },
        async fetchDataAndUpdateLog(dateString, date) {
            try {
                // Assuming 'date' is already a Day.js object; if not, convert it
                const dayDate = dayjs(date)

                await this.loadWeekFromDate(dayDate.toDate())  // Pass as a Date if needed, or adjust 'loadWeekFromDate' to accept Day.js objects
                // Successfully fetched, so update the log
                this.dataFetchLog[dateString] = dayjs().toISOString()  // Use Day.js to get the current time in ISO format
                console.log('Data fetched successfully for date:', dateString)
            } catch (error) {
                console.error(`Failed to fetch data for date ${dateString}:`, error)
            }
        },

        // Updated to use this.currentWeekStart and this.currentWeekEnd directly
        async fetchWeekDataIfNeeded() {
            // Assumes this.currentWeekStart and this.currentWeekEnd are already set
            if (this.needsDataForWeek()) {
                await this.checkAndFetchForUpcomingContent().catch(error => {
                    console.error('Failed to load data for the week:', error)
                    return false // Indicates failure to fetch when an error occurs
                })
            } else {
                console.log('Week data already loaded; no need to fetch.')
            }
        },

        // Function to simply update baseTime with the given time (expected to be a Day.js object or compatible input)
        updateBaseTime(time) {
            // Ensure the time is a Day.js object when setting baseTime
            this.baseTime = dayjs(time).toDate()  // Convert to Date if necessary; consider keeping it as Day.js object if possible
        },

        // Function to set baseTime based on a new time input (expected to be a string, Date, etc.)
        setBaseTime(newTime) {
            // Convert newTime to a Day.js object and then to Date if necessary
            this.baseTime = dayjs(newTime).toDate()  // This handles various input formats and ensures consistency
        },


        /**
         * This method orchestrates the update of show scheduling data for the next four hours.
         * It is designed to be triggered when there are changes to the base time or when a manual refresh is needed.
         * The method handles loading and organizing the scheduling data to be ready for display in the grid.
         */
        async updateNextFourHours() {
            // Set the loading state to true to indicate that data processing is underway.
            // This can be used to display a loading spinner or disable user interaction temporarily.
            this.scheduleIsLoading = true;

            // Update the time range for the scheduling grid. This adjusts `currentHalfHour` and `fourHoursLater`
            // based on the current `baseTime`. These values define the range of time for which shows will be displayed.
            this.updateTimeRange();

            // Update the time slots
            this.setTimeSlots();

            // Prepares the shows for the grid display by filtering, calculating grid slots,
            // resolving conflicts, and optionally filling empty grid slots with placeholders.
            // This step involves several functions that ensure shows are positioned correctly according to their
            // start time, duration, and conflicts with other shows.
            this.prepareShowsForGrid();

            // Reset the loading state to false indicates that the data processing is complete.
            // This allows the user interface to be interactive again and shows the updated data in the grid.
            this.scheduleIsLoading = false;
        },


        updateTimeRange() {
            // Ensure the baseTime is interpreted correctly in the current user's timezone
            const baseDate = dayjs(this.baseTime).tz(getTimeZone())

            // Calculate the rounded minutes to either 0 or 30 based on the current time
            const roundedMinutes = baseDate.minute() < 30 ? 0 : 30

            // Set the current half hour, rounding down to the nearest half-hour mark
            const currentHalfHour = baseDate.minute(roundedMinutes).second(0).millisecond(0).startOf('minute')

            // Calculate four hours later from the current half-hour mark
            const fourHoursLater = currentHalfHour.add(4, 'hours')

            // Update the store's currentHalfHour and fourHoursLater ensuring they are Date objects if required
            this.currentHalfHour = currentHalfHour.toDate()
            this.fourHoursLater = fourHoursLater.toDate()

            console.log('Fetching shows between:', currentHalfHour.format('YYYY-MM-DD HH:mm:ss'), 'and', fourHoursLater.format('YYYY-MM-DD HH:mm:ss'))
        },

        setTimeSlots() {
            const appSettingStore = useAppSettingStore()
            let slotHours

            // Determine the number of slot hours based on screen size
            if (appSettingStore.isVerySmallScreen) {
                slotHours = this.verySmallScreenSlotHours
            } else if (appSettingStore.isSmallScreen) {
                slotHours = this.smallScreenSlotHours
            } else {
                slotHours = this.mediumScreenSlotHours
            }

            const intervalMinutes = this.slotIntervalMinutes
            const slots = []
            const totalSlots = (slotHours * 60) / intervalMinutes

            // Ensure state.currentHalfHour is a Day.js object
            const currentHalfHour = dayjs(this.currentHalfHour)

            // Calculate the time for each slot using Day.js
            for (let i = 0; i < totalSlots; i++) {
                let slotTime = currentHalfHour.add(i * intervalMinutes, 'minute')
                slots.push(slotTime.toDate())  // Convert back to JavaScript Date if necessary
            }
            this.timeSlots = slots
            return slots.length
        },

        prepareShowsForGrid() {
            // Step 1: Filter shows within the desired time range
            const shows = this.filterShowsForTimeRange()

            // Step 2: Calculate initial grid slots for these shows
            const processedShows = this.calculateGridSlots(shows, this.timeSlots)

            if (!this.timeSlots || !Array.isArray(this.timeSlots) || this.timeSlots.length === 0) {
                console.error('timeSlots is not properly initialized.')
                // Handle this scenario, e.g., by initializing timeSlots, or skipping the update
                return
            }

            // Step 3: Update column occupancy and find the maximum row used
            const {colOccupancy, maxRowUsed} = this.updateColumnOccupancy(processedShows, this.timeSlots.length)

            // Step 4: Fill gaps in the grid with placeholders
            const gridItems = this.fillGapsAndCreatePlaceholders(colOccupancy, maxRowUsed, this.timeSlots.length)

            // Step 5: Combine processed shows with the placeholders
            const combinedShows = [...processedShows, ...gridItems]

            // Step 6: Sort and group shows by rows
            this.nextFourHoursOfContent = this.sortShowsByPosition(combinedShows)
        },

        filterShowsForTimeRange() {
            return this.weeklyContent.filter(show => {
                // Validate show data integrity
                if (typeof show.startTime !== 'string' || typeof show.durationMinutes !== 'number') {
                    console.warn('Invalid show data:', show.startTime, show.durationMinutes)
                    return false // Skip this show if it doesn't meet data expectations
                }

                const showStart = dayjs(show.startTime)
                const showEnd = dayjs(show.endTime)
                const isInTimeRange = showStart.isBefore(this.fourHoursLater) && showEnd.isAfter(this.currentHalfHour)

                // Detailed logging for debugging
                if (isInTimeRange) {
                    const hasStarted = showStart.isBefore(this.currentHalfHour) ? 'already started' : 'starts within range'
                    console.log(`Show: ${show.content.name}, ${hasStarted}, Start: ${showStart.format('HH:mm:ss')}, End: ${showEnd.format('HH:mm:ss')}, Duration: ${show.durationMinutes}`)
                }

                return isInTimeRange
            })
        },

        calculateGridSlots(shows, timeSlots) {
            // Validate the timeSlots array to prevent errors
            if (!Array.isArray(timeSlots) || timeSlots.length === 0) {
                console.error('Invalid or empty timeSlots array')
                return [] // Exit if no valid time slots to work with
            }

            return shows.map(show => {
                const showStart = dayjs(show.startTime);
                const showEnd = dayjs(show.endTime);
                console.log('************************************')
                console.log(`Processing show: ${show.content.name}, Start: ${show.startTime}, End: ${show.endTime}`);

                // Find the index of the slot where the show should start
                let slotIndex = timeSlots.findIndex(slot => showStart.isSameOrBefore(dayjs(slot)));

                // Adjust if the show starts exactly at a slot time or just after the last checked slot time
                if (slotIndex === -1 || showStart.isAfter(dayjs(timeSlots[slotIndex]))) {
                    slotIndex = Math.max(0, slotIndex);
                }

                console.log(`Comparing show start ${showStart.format()} to timeSlot index ${slotIndex} at ${dayjs(timeSlots[slotIndex]).format()}`);

                // Calculate the end slot index
                let endSlotIndex = timeSlots.findIndex(slot => showEnd.isSameOrBefore(dayjs(slot).add(30, 'minutes')));

                // If the end slot index points to a slot that starts after the show ends, subtract one
                if (endSlotIndex !== -1 && showEnd.isBefore(dayjs(timeSlots[endSlotIndex]))) {
                    endSlotIndex--;
                }

                // Handle cases where the show ends after the last slot
                if (endSlotIndex === -1 || showEnd.isSame(dayjs(timeSlots[timeSlots.length - 1]).add(30, 'minutes'))) {
                    endSlotIndex = timeSlots.length - 1;
                }

                console.log(`Comparing show end ${showEnd.format()} to timeSlot index ${endSlotIndex} at ${endSlotIndex !== -1 ? dayjs(timeSlots[endSlotIndex]).format() : 'out of range'}`);


                // Calculate the number of slots the show should span
                let span = endSlotIndex - slotIndex + 1;

                console.log(`Calculated gridStart: ${slotIndex + 1}, gridSpan: ${span}`);

                return {
                    ...show,
                    gridStart: slotIndex + 1, // Convert to 1-based index for grid positioning
                    gridSpan: span
                };
            });
        },

        updateColumnOccupancy(processedShows, cols) {
            let colOccupancy = new Array(cols).fill(null).map(() => new Set())
            let maxRowUsed = 0
            processedShows.forEach(show => {
                for (let i = show.gridStart - 1; i < show.gridStart - 1 + show.gridSpan; i++) {
                    if (i >= 0 && i < cols) {
                        colOccupancy[i].add(show.gridRow)
                        maxRowUsed = Math.max(maxRowUsed, show.gridRow)
                    }
                }
            })
            return {colOccupancy, maxRowUsed}
        },

        fillGapsAndCreatePlaceholders(colOccupancy, maxRowUsed, cols) {
            let gridItems = []
            for (let row = 1; row <= maxRowUsed; row++) {
                gridItems.push(...this.findAndFillGapsForSingleRow(colOccupancy, row, cols))
            }
            return gridItems
        },

        findAndFillGapsForSingleRow(colOccupancy, row, cols) {
            let gridItems = []
            let gapStart = -1
            for (let i = 0; i < cols; i++) {
                if (!colOccupancy[i].has(row)) {
                    gapStart = gapStart === -1 ? i : gapStart
                } else if (gapStart !== -1) {
                    gridItems.push(this.createPlaceholder(gapStart + 1, i - gapStart, row))
                    gapStart = -1
                }
            }
            if (gapStart !== -1) {
                gridItems.push(this.createPlaceholder(gapStart + 1, cols - gapStart, row))
            }
            return gridItems
        },

        sortShowsByPosition(combinedShows) {
            if (!Array.isArray(combinedShows)) {
                console.error('Expected an array of shows, received:', combinedShows)
                return [] // Return an empty array if not an array to prevent errors
            }

            // Sort shows directly by row and then by start position within each row
            return combinedShows.sort((a, b) => a.gridRow - b.gridRow || a.gridStart - b.gridStart)
        },

        createPlaceholder(start, span, row) {
            return {
                placeholder: true,
                startTime: 'Placeholder',
                priority: 0,
                gridStart: start,
                gridSpan: span,
                gridRow: row,
                content: {name: 'Nothing scheduled.'},
            }
        },

        createBlankSpotPlaceholder(start, span, row) {
            return {
                placeholder: true,
                startTime: 'Blank Spot',
                priority: 0,
                gridStart: start,
                gridSpan: span,
                gridRow: row,
                content: {name: 'Blank Spot'}, // Ensure it is differentiated from normal placeholders
            }
        },

    },

    getters: {
        currentTime: (state) => {
            return dayjs(state.baseTime).format('h:mm A')
        },

        // setTimeSlots: (state) => {
        //     const appSettingStore = useAppSettingStore()
        //     let slotHours
        //
        //     // Determine the number of slot hours based on screen size
        //     if (appSettingStore.isVerySmallScreen) {
        //         slotHours = state.verySmallScreenSlotHours
        //     } else if (appSettingStore.isSmallScreen) {
        //         slotHours = state.smallScreenSlotHours
        //     } else {
        //         slotHours = state.mediumScreenSlotHours
        //     }
        //
        //     const intervalMinutes = state.slotIntervalMinutes
        //     const slots = []
        //     const totalSlots = (slotHours * 60) / intervalMinutes
        //
        //     // Ensure state.currentHalfHour is a Day.js object
        //     const currentHalfHour = dayjs(state.currentHalfHour)
        //
        //     // Calculate the time for each slot using Day.js
        //     for (let i = 0; i < totalSlots; i++) {
        //         let slotTime = currentHalfHour.add(i * intervalMinutes, 'minute')
        //         slots.push(slotTime.toDate())  // Convert back to JavaScript Date if necessary
        //     }
        //     state.timeSlots = slots
        //     return slots.length
        // },

        nextFourHoursWithHalfHourIntervals: (state) => {
            const userStore = useUserStore() // Access the user store
            const appSettingStore = useAppSettingStore() // Access the settings store
            const userTimezone = userStore.timezone // Get the user's timezone
            const cols = appSettingStore.isVerySmallScreen ? 4 :
                appSettingStore.isSmallScreen ? 6 : 8


            const intervals = []
            const now = dayjs(state.baseTime).tz(userTimezone) // Get the current time in the user's timezone

            // Determine if the current minute is less than 30 to start at the top of the hour or at the half-hour
            let current = now.minute() < 30 ? now.startOf('hour') : now.startOf('hour').add(30, 'minutes')


            // Generate intervals for the next 4 hours, each 30 minutes apart
            // Adjust the loop count based on the number of columns/ intervals needed
            for (let i = 0; i < cols; i++) { // Adjust the number of intervals based on the screen size
                intervals.push({
                    formatted: current.format('hh:mm A'), // Formatted time for display
                    dateTimeString: current.format('YYYY-MM-DD HH:mm:ss'), // Y-m-d H:m:s format for comparisons
                })
                current = current.add(30, 'minute') // Move to the next 30-minute interval
            }

            return intervals
        },

        upcomingContent: (state) => {
            // Since viewingWindowStart is now a Day.js object, use Day.js methods directly
            const start = dayjs(state.viewingWindowStart).subtract(1, 'hour') // 1 hour earlier
            const end = start.add(7, 'hours') // 6 hours later from the start

            // Group shows by start time using Day.js
            const showsGroupedByStartTime = state.weeklyContent.reduce((acc, item) => {
                const itemStart = dayjs(item.startTime).valueOf() // Use .valueOf() to get the timestamp
                if (!acc[itemStart]) {
                    acc[itemStart] = []
                }
                acc[itemStart].push(item)
                return acc
            }, {})

            // Select the show with the lowest priority for each start time
            const selectedShows = Object.values(showsGroupedByStartTime).map(group => {
                return group.reduce((selected, item) => {
                    return !selected || item.priority < selected.priority ? item : selected
                }, null)
            })

            // Filter shows that fall within the next 6-hour window and sort them
            return selectedShows
                .filter(item => {
                    const itemStart = dayjs(item.startTime)
                    return itemStart.isSameOrAfter(start) && itemStart.isBefore(end)
                })
                .sort((a, b) => dayjs(a.startTime).unix() - dayjs(b.startTime).unix()) // Sorting by Unix timestamp
        },


        nextSixHours: (state) => {
            // Assuming state.viewingWindowStart is already a Day.js object.
            // If it's still a native Date, convert it first:
            let adjustedStart = dayjs(state.viewingWindowStart)

            // Add 6 hours to the adjusted start time
            const end = adjustedStart.add(6, 'hours')

            // Generate each hour of the interval between adjustedStart and end
            const hours = []
            let hour = adjustedStart

            while (hour.isBefore(end)) {
                hours.push(hour.toDate()) // Convert back to Date if necessary; otherwise, just use `hour` if you can use Day.js objects directly
                hour = hour.add(1, 'hour')
            }

            return hours
        },

        dateMessage: (state) => {
            const startDay = startOfDay(state.viewingWindowStart)
            const formattedDate = format(startDay, 'EEEE MMMM do, yyyy')
            if (isToday(startDay)) {
                return `Today - ${formattedDate}`
            } else if (isYesterday(startDay)) {
                return `Yesterday - ${formattedDate}`
            } else if (isTomorrow(startDay)) {
                return `Tomorrow - ${formattedDate}`
            } else {
                return formattedDate
            }
        },

        currentMonthIndex: (state) => {
            // Ensure state.currentMonth is converted to a Day.js object
            const month = dayjs(state.currentMonth)
            // Day.js months are 0-indexed just like JavaScript Date, returns the month (0-11)
            return month.month()
        },

        currentMonthName: (state) => {
            // Ensure state.currentMonth is converted to a Day.js object
            const month = dayjs(state.currentMonth)
            // Returns the full name of the month, e.g., 'January', 'February', etc.
            return month.format('MMMM')
        },

        currentYear: (state) => {
            // Ensure state.currentMonth is converted to a Day.js object
            const month = dayjs(state.currentMonth)
            // Returns the year
            return month.year()
        },
        isToday: (state) => {
            const today = dayjs()  // Gets today's date as a Day.js object
            const viewingStart = dayjs(state.viewingWindowStart)  // Convert to Day.js object if not already

            // Compare if both dates are the same calendar day
            return today.isSame(viewingStart, 'day')
        },

        daysInMonth: (state) => {
            // Assuming state.currentMonth is a Day.js object; if it's a Date, convert it:
            const currentMonth = dayjs(state.currentMonth)

            const startOfCurrentMonth = currentMonth.startOf('month')
            const endOfCurrentMonth = currentMonth.endOf('month')

            // Adjust these to ensure the grid starts on Sunday and ends on Saturday
            const startOfGrid = startOfCurrentMonth.startOf('week')  // Assumes the week starts on Sunday by default
            const endOfGrid = endOfCurrentMonth.endOf('week')        // Assumes the week ends on Saturday by default

            // Generate the days for the calendar grid
            const days = []
            let day = startOfGrid

            while (day.isBefore(endOfGrid) || day.isSame(endOfGrid, 'day')) {
                days.push(day.toDate()) // Collect days as Date objects; remove toDate() if you can use Day.js objects directly
                day = day.add(1, 'day')
            }

            return days
        },

    },
})
