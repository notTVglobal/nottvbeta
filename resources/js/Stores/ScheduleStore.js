import { defineStore } from 'pinia'
import { useUserStore } from '@/Stores/UserStore'
import { createTimeSlots } from '@/Utilities/TimeUtils'
import {
    addDays,
    addHours, addMinutes,
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
import relativeTime from 'dayjs/plugin/relativeTime'
import timezone from 'dayjs/plugin/timezone'
import utc from 'dayjs/plugin/utc'

// Extend dayjs with the plugins
dayjs.extend(relativeTime)
dayjs.extend(timezone)
dayjs.extend(utc)

function convertScheduleToTimezone(scheduleData) {
    const userStore = useUserStore() // Access the UserStore

    return scheduleData.data.map(item => {
        // Convert top-level start_time and end_time using UserStore methods
        const startTimeInUserTz = item.start_time ? userStore.formatDateTimeFromUtcToUserTimezone(item.start_time, 'YYYY-MM-DD HH:mm:ss') : null
        const endTimeInUserTz = item.end_time ? userStore.formatDateTimeFromUtcToUserTimezone(item.end_time, 'YYYY-MM-DD HH:mm:ss') : null

        // Check and convert recurrenceDetails if present
        let recurrenceDetailsInUserTz = null
        if (item.recurrenceDetails) {
            const {start_time, start_date, end_date} = item.recurrenceDetails
            recurrenceDetailsInUserTz = {
                ...item.recurrenceDetails,
                start_time: start_time ? userStore.formatTimeInUserTimezone(start_time, 'HH:mm:ss') : null,
                start_date: start_date ? userStore.formatDateTimeFromUtcToUserTimezone(start_date, 'YYYY-MM-DD') : null,
                end_date: end_date ? userStore.formatDateTimeFromUtcToUserTimezone(end_date, 'YYYY-MM-DD') : null,
            }
        }

        return {
            ...item,
            start_time: startTimeInUserTz,
            end_time: endTimeInUserTz,
            recurrenceDetails: recurrenceDetailsInUserTz,
        }
    })
}

// Helper function to get dates within the next 6 hours from viewingWindowStart
function getUpcomingContentDates(viewingWindowStart) {
    let dates = []
    let start = new Date(viewingWindowStart)
    for (let i = 0; i < 6; i++) {
        dates.push(new Date(start.setHours(start.getHours() + i)))
    }
    return dates
}

function fetchShowsScheduledBetween(state, startDateTime, endDateTime) {
    // Convert start and end DateTime to the user's timezone for accurate comparison
    const userStore = useUserStore()
    const startInUserTZ = userStore.convertUtcToUserTimezone(startDateTime.toISOString())
    const endInUserTZ = userStore.convertUtcToUserTimezone(endDateTime.toISOString())

    return state.weeklyContent.filter(show => {
        // Convert show's start time to the same timezone before comparison
        const showStartTimeInUserTZ = userStore.convertUtcToUserTimezone(show.start_time)
        return showStartTimeInUserTZ >= startInUserTZ && showStartTimeInUserTZ < endInUserTZ
    })
}

const initialState = () => ({
    viewingWindowStart: new Date(),
    currentMonth: new Date(),
    selectedDay: new Date(),
    currentWeekStart: null,
    currentWeekEnd: null,
    // viewingMode: 'automatic', // or 'userSelected'
    fiveDaySixHourSchedule: [], // Holds the schedule shows 5 day / 6 hour structured data
    todaysContent: [],
    weeklyContent: [],
    dataFetchLog: [],
    scheduleIsLoading: false,
})

export const useScheduleStore = defineStore('scheduleStore', {
    state: initialState,
    actions: {
        resetAll() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        reset() {
            const now = new Date()
            this.viewingWindowStart = now
            this.currentMonth = now
            this.selectedDay = now
        },
        async setSelectedDay(day) {
            this.selectedDay = day
            // Explicitly set the viewingWindowStart to 4 AM for the selected day
            this.viewingWindowStart = addHours(startOfDay(day), 4)
            this.currentWeekStart = startOfWeek(day, {weekStartsOn: 0})
            this.currentWeekEnd = endOfWeek(day, {weekStartsOn: 0})

            // // Check if the week of the selected day is already loaded
            // this.currentWeekStart = startOfWeek(day, {weekStartsOn: 0});
            // this.currentWeekEnd = endOfWeek(day, {weekStartsOn: 0});

            // Use the updated fetch logic
            await this.fetchWeekDataIfNeeded()

            // // First, check if we need to load data for the new week
            // if (this.needsDataForWeek(weekStart, weekEnd)) {
            //     // If new data is needed for the week, load it
            //     await this.loadWeekFromDate(day).catch(error => {
            //         console.error("Failed to load data for the new week:", error);
            //     });
            // }

            // Then, check and fetch for any missing upcoming content
            // This is necessary in case the week data is present but specific upcoming content within the week is missing
            // await this.checkAndFetchForUpcomingContent();
        },
        setSelectedDayToToday(day) {
            const now = new Date()
            this.selectedDay = now
            this.viewingWindowStart = startOfHour(now)
        },
        async changeDay(days) {
            const currentTime = this.viewingWindowStart.getHours() * 60 + this.viewingWindowStart.getMinutes()
            let newDay = addDays(startOfDay(this.viewingWindowStart), days)
            newDay = new Date(newDay.setMinutes(currentTime)) // Preserve time of day

            this.selectedDay = newDay
            this.viewingWindowStart = newDay

            // Update the week's range based on the new day
            this.currentWeekStart = startOfWeek(newDay, {weekStartsOn: 0})
            this.currentWeekEnd = endOfWeek(newDay, {weekStartsOn: 0})

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
            // Shift the viewing window
            this.viewingWindowStart = addHours(this.viewingWindowStart, hours)
            this.currentWeekStart = startOfWeek(this.viewingWindowStart, {weekStartsOn: 0})
            this.currentWeekEnd = endOfWeek(this.viewingWindowStart, {weekStartsOn: 0})

            // // Update selectedDay if the day has changed
            // if (!isSameDay(this.viewingWindowStart, this.selectedDay)) {
            //     this.selectedDay = startOfDay(this.viewingWindowStart);
            // }

            // If the day has changed, update selectedDay and the week's range
            if (!isSameDay(this.viewingWindowStart, this.selectedDay)) {
                this.selectedDay = startOfDay(this.viewingWindowStart)
            }

            // // Check if the week of the new viewing window is already loaded
            // this.currentWeekStart = startOfWeek(this.viewingWindowStart, { weekStartsOn: 0 });
            // this.currentWeekEnd = endOfWeek(this.viewingWindowStart, { weekStartsOn: 0 });

            // Use the updated fetch logic
            await this.fetchWeekDataIfNeeded()

            // // Check for the need to load data for the new week
            // if (!this.needsDataForWeek()) {
            //     // Data for the current week has already been loaded; skip fetching
            //     console.log("Data for the current week has already been loaded; skipping redundant fetch.");
            //     return;
            // }

            //
            // await this.loadWeekFromDate(this.viewingWindowStart).catch(error => {
            //     console.error("Failed to load data for the new week:", error);
            // });

            // Proceed with data fetching if needed
            // await this.checkAndFetchForUpcomingContent();
        },
        isElevenPM(date) {
            return getHours(date) === 23 // Checks if the hour is 23 (11 PM)
        },
        // Actions to change the month
        async subtractMonth() {
            // Subtract one month from the currentMonth
            this.currentMonth = subMonths(this.currentMonth, 1)

            try {
                await this.setSelectedDay(this.currentMonth)
                console.log('Set selected day based on current month:', this.currentMonth)
            } catch (error) {
                console.error(`Failed to set selected day based on current month ${this.currentMonth}:`, error)
            }
        },
        async addMonth() {
            this.currentMonth = addMonths(this.currentMonth, 1)
            try {
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
            const currentDate = new Date()

            try {
                // Call loadWeekFromDate with the current date
                await this.loadWeekFromDate(currentDate)
            } catch (error) {
                console.error('Failed to preload weekly content:', error)
            }
        },
        async loadWeekFromDate(date) {
            let formattedDate // Declare formattedDate outside of the try block
            this.scheduleIsLoading = true
            try {
                const userStore = useUserStore()
                // Ensure the date is in UTC format for the request
                console.log('Date before formatted: ' + date)
                const fullISODate = date.toISOString()

                console.log(`Loading week data for date in UTC: ${fullISODate}`)

                // Send the dateTime and timezone as a JSON object in a POST request
                const response = await axios.post(`/api/schedule/week/${fullISODate}`)

                // const formattedDate = date.toISOString().split('T')[0];
                // console.log(`Loading week data for date: ${formattedDate}`); // Log the date being requested
                //
                // const response = await axios.get(`/api/schedule/week/${formattedDate}`);
                console.log('Received response:', response.data) // Log the raw response data

                // Fallback to response timezone if userStore.timezone is not set
                const timezone = userStore.timezone || response.data.userTimezone || 'UTC' // Additional fallback to 'UTC'
                console.log(`Using timezone: ${timezone}`) // Log the timezone being used

                const newData = convertScheduleToTimezone(response.data, timezone) // Ensure you are accessing the correct data property from the response

                // Update the fetch log with the current fetch
                const weekStart = startOfWeek(new Date(date), {weekStartsOn: 0}).toISOString()
                const weekEnd = endOfWeek(new Date(date), {weekStartsOn: 0}).toISOString()
                const fetchTime = new Date().toISOString()

                const existingLogIndex = this.dataFetchLog.findIndex(log => log.weekStart === weekStart && log.weekEnd === weekEnd)
                if (existingLogIndex !== -1) {
                    this.dataFetchLog[existingLogIndex].lastFetch = fetchTime
                } else {
                    this.dataFetchLog.push({weekStart, weekEnd, lastFetch: fetchTime})
                }

                // Merge newData into weeklyContent, avoiding duplicates
                this.weeklyContent = [...this.weeklyContent, ...newData].filter((value, index, self) =>
                        index === self.findIndex((t) => (
                            t.id === value.id && t.start_time === value.start_time
                        )),
                )
                this.scheduleIsLoading = false
            } catch (error) {
                console.error(`Failed to load content for week starting ${formattedDate}:`, error)
                this.scheduleIsLoading = false
            }
        },
        needsDataForWeek() {
            // Helper function to format ISO date strings for easier comparison
            const formatISODate = date => date.toISOString().split('T')[0]

            // Current week range in ISO date string format
            const weekStartStr = formatISODate(this.currentWeekStart)
            const weekEndStr = formatISODate(this.currentWeekEnd)

            // Enhanced logging for debugging
            console.log(`Current week range: ${weekStartStr} to ${weekEndStr}`)
            console.log('Existing data fetch log entries:', this.dataFetchLog)

            // Iterate through the fetch log to check if the current week has been fetched
            const weekHasBeenFetched = this.dataFetchLog.some(log => {
                // Convert log dates to ISO string format for comparison
                const logWeekStartStr = formatISODate(new Date(log.weekStart))
                const logWeekEndStr = formatISODate(new Date(log.weekEnd))

                // Log each comparison for insight
                console.log(`Comparing to fetched range: ${logWeekStartStr} to ${logWeekEndStr}`)

                return logWeekStartStr <= weekStartStr && logWeekEndStr >= weekEndStr
            })

            // Log the final determination
            console.log(`Week from ${weekStartStr} to ${weekEndStr} has ${weekHasBeenFetched ? '' : 'not '}been fetched.`)

            return !weekHasBeenFetched
            // // Extend weekEnd to cover the span of upcoming content from viewingWindowStart
            // const extendedEnd = this.calculateExtendedEndForUpcomingContent();
            // let checkWeekEnd = new Date(this.currentWeekEnd); // Work with a copy to avoid side effects
            //
            // if (extendedEnd > checkWeekEnd) {
            //     checkWeekEnd = extendedEnd;
            // }
            //
            // // Now weekEnd includes any additional day(s) that might be displayed
            // // Adjust the checkWeekEnd to include the entire day
            // checkWeekEnd.setHours(23, 59, 59, 999);
            //
            // const hasDataForExtendedWeek = this.weeklyContent.some(content => {
            //     const contentDate = new Date(content.start_time);
            //     return contentDate >= this.currentWeekStart && contentDate <= checkWeekEnd;
            // });
            //
            // console.log('Has data for extended week range:', hasDataForExtendedWeek);
            // return !hasDataForExtendedWeek;
        },
        calculateExtendedEndForUpcomingContent() {
            // Assuming viewingWindowStart is the reference start time for upcoming content
            const baseStartTime = new Date(this.viewingWindowStart)
            // Extend by 6 hours to cover the upcoming content span
            // Return the extended end time, potentially adjusting into the next day
            return new Date(baseStartTime.getTime() + (6 * 60 * 60 * 1000))
        },
        // Function to check for and fetch missing upcoming content
        async checkAndFetchForUpcomingContent() {
            const upcomingDates = getUpcomingContentDates(this.viewingWindowStart)
            const now = new Date()
            const fifteenMinutesAgo = new Date(now.getTime() - 15 * 60000)

            for (const date of upcomingDates) {
                const dateString = date.toISOString().split('T')[0]
                const contentCoverageAndFreshness = this.weeklyContent.some(content => {
                    const contentDate = new Date(content.start_time).toDateString()
                    const lastFetchedTime = this.dataFetchLog[dateString]
                    const isFresh = lastFetchedTime && new Date(lastFetchedTime) > fifteenMinutesAgo
                    return date.toDateString() === contentDate && isFresh
                })

                if (!contentCoverageAndFreshness) {
                    // This date needs data fetching
                    await this.fetchDataAndUpdateLog(dateString, date)
                    break // Assuming you fetch data for the week, so no need to check further dates within the same week
                }
            }
        },
        async fetchDataAndUpdateLog(dateString, date) {
            try {
                await this.loadWeekFromDate(date)
                // Successfully fetched, so update the log
                this.dataFetchLog[dateString] = new Date().toISOString()
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
                // await this.loadWeekFromDate(this.currentWeekStart).catch(error => {
                //     console.error("Failed to load data for the week:", error);
                //     return false; // Indicates failure to fetch when an error occurs
                // });
            } else {
                console.log('Week data already loaded; no need to fetch.')
            }

            // Conditionally check for missing upcoming content within the current week
            // only if new week data hasn't been fetched.
            // if (!dataFetched) {
            //     await this.checkAndFetchForUpcomingContent();
            // }
        },
        // createTimeSlots(start, durationHours = 4, intervalMinutes = 30) {
        //     let slots = [];
        //     for (let i = 0; i < (durationHours * 60) / intervalMinutes; i++) {
        //         let slotTime = new Date(start.getTime() + i * intervalMinutes * 60000);
        //         slots.push(slotTime);
        //     }
        //     return slots;
        // },

        resolveSchedulingConflicts(shows) {
            // Sort shows by start time, then by priority for shows with the same start time
            const sortedShows = shows.sort((a, b) => {
                const startTimeComparison = new Date(a.start_time) - new Date(b.start_time)
                if (startTimeComparison === 0) { // If start times are the same
                    return a.priority - b.priority // Compare by priority
                }
                return startTimeComparison
            })

            const resolvedShows = []
            const showsByStartTime = {}

            // Group shows by their start time
            sortedShows.forEach(show => {
                const startTime = new Date(show.start_time).toISOString()
                if (!showsByStartTime[startTime]) {
                    showsByStartTime[startTime] = []
                }
                showsByStartTime[startTime].push(show)
            })

            // For each start time, select the show with the highest priority (lowest priority number)
            Object.values(showsByStartTime).forEach(group => {
                if (group.length > 1) {
                    // If there are conflicts, push only the show with the highest priority
                    resolvedShows.push(group[0]) // Assuming the group is already sorted by priority
                } else {
                    // No conflict, push the single show
                    resolvedShows.push(group[0])
                }
            })

            return resolvedShows
        },
        adjustShowsForGrid(shows, timeSlots) {
            return shows.map(show => {
                // Your existing logic for calculating show placement
                const showStart = new Date(show.start_time)
                const showEnd = new Date(show.start_time)
                showEnd.setMinutes(showEnd.getMinutes() + show.durationMinutes)
                const slotIndex = timeSlots.findIndex(slot => showStart >= slot && showStart < new Date(slot.getTime() + 30 * 60000))
                let span = Math.ceil(show.durationMinutes / 30)
                if (slotIndex + span > timeSlots.length) {
                    span = timeSlots.length - slotIndex
                }

                return {
                    ...show,
                    gridStart: slotIndex + 1,
                    gridSpan: span,
                }
            })
        },
        mapShowsToTimeSlots(shows, timeSlots) {
            const showsWithAdjustedSpans = shows.map(show => {
                const showStart = new Date(show.start_time)
                const showEnd = new Date(show.start_time)
                showEnd.setMinutes(showEnd.getMinutes() + show.durationMinutes)
                const slotIndex = timeSlots.findIndex(slot => showStart >= slot && showStart < new Date(slot.getTime() + 30 * 60000))

                // Initially set the span based on the show's duration
                let span = Math.ceil(show.durationMinutes / 30)

                // Adjust the span if there's an overlap with the next show's start time
                if (slotIndex + span > timeSlots.length) {
                    span = timeSlots.length - slotIndex // Adjust to not exceed the grid
                }

                // Return the show with adjusted span and calculated start index
                return {
                    ...show,
                    gridStart: slotIndex + 1,
                    gridSpan: span,
                }
            })

            // Now handle placing the shows with adjusted spans in the grid, including placeholders for empty slots
            const gridItems = timeSlots.map((slot, index) => {
                const showForSlot = showsWithAdjustedSpans.find(show => show.gridStart === index + 1)
                if (showForSlot) {
                    return showForSlot
                } else {
                    // If no show for this slot, return a placeholder
                    return {
                        placeholder: true,
                        gridStart: index + 1,
                        gridSpan: 1,
                        content: {show: {name: 'Nothing scheduled.'}},
                    }
                }
            })

            return gridItems
        },
        fillEmptySlotsWithPlaceholders(showsWithPlacement, timeSlots) {
            const gridItems = []

            timeSlots.forEach((slot, index) => {
                const slotStart = slot
                const showExistsInSlot = showsWithPlacement.some(show =>
                    slotStart >= new Date(show.start_time) &&
                    slotStart < new Date(new Date(show.start_time).getTime() + show.durationMinutes * 60000),
                )

                if (!showExistsInSlot) {
                    // Insert a placeholder show for this slot
                    gridItems.push({
                        placeholder: true,
                        start_time: slot.toISOString(),
                        gridStart: index + 1,
                        gridSpan: 1,
                        content: {show: {name: 'Nothing scheduled.'}},
                    })
                }
            })

            // Merge and sort the grid items by their start time/gridStart to maintain chronological order
            return [...showsWithPlacement, ...gridItems].sort((a, b) => a.gridStart - b.gridStart)
        },


    },

    getters: {
        nextFourHoursOfContent: (state) => {
            const now = new Date()
            const startOfCurrentHour = new Date(now.setMinutes(0, 0, 0))
            const fourHoursLater = new Date(startOfCurrentHour.getTime() + 4 * 60 * 60 * 1000)

            const timeSlots = createTimeSlots(startOfCurrentHour, 4, 30)
            let shows = fetchShowsScheduledBetween(state, startOfCurrentHour, fourHoursLater)
            shows = resolveSchedulingConflicts(shows)
            let adjustedShows = adjustShowsForGrid(shows, timeSlots)
            adjustedShows = fillEmptySlotsWithPlaceholders(adjustedShows, timeSlots)

            return adjustedShows
        },
        // nextFourHoursOfContent: (state) => {
        //     const userStore = useUserStore()
        //     const now = new Date() // Current time
        //     // const startOfCurrentHour = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours());
        //     const startOfCurrentHour = new Date(now.setMinutes(0, 0, 0))
        //     const fourHoursLater = new Date(startOfCurrentHour.getTime() + 4 * 60 * 60 * 1000)
        //
        //
        //     // Create time slots for the next four hours, at 30-minute intervals, in UTC
        //     const utcTimeSlots = createTimeSlots(startOfCurrentHour, 4, 30)
        //     // Convert each UTC time slot to the user's local timezone
        //     const timeSlots = utcTimeSlots.map(slot =>
        //         new Date(userStore.convertUtcToUserTimezone(slot)),
        //     )
        //
        //     // Iterate over each time slot to either find a show that matches or insert a placeholder
        //     const filledShows = timeSlots.map((slot, index) => {
        //         const slotStart = dayjs(slot).format('YYYY-MM-DD HH:mm:ss')
        //         const matchingShow = state.weeklyContent.find(show => {
        //             const showStart = dayjs(show.start_time).format('YYYY-MM-DD HH:mm:ss')
        //             return showStart === slotStart
        //         })
        //
        //         if (matchingShow) {
        //             // Calculate grid placement based on the show's start time and duration
        //             return {
        //                 ...matchingShow,
        //                 gridStart: index + 1,
        //                 gridSpan: Math.ceil(matchingShow.durationMinutes / 30),
        //             }
        //         } else {
        //             // Create a placeholder for empty time slots
        //             return {
        //                 placeholder: true,
        //                 start_time: slot.toISOString(),
        //                 durationMinutes: 30,
        //                 gridStart: index + 1,
        //                 gridSpan: 1,
        //                 content: {show: {name: 'Nothing scheduled.'}},
        //             }
        //         }
        //     })
        //
        //     return filledShows
        // },

        // // Group shows by start time
        // const showsGroupedByStartTime = state.weeklyContent.reduce((acc, item) => {
        //     const itemStart = dayjs(item.start_time).format('YYYY-MM-DD HH:mm:ss');
        //     if (!acc[itemStart]) {
        //         acc[itemStart] = [];
        //     }
        //     acc[itemStart].push(item);
        //     return acc;
        // }, {});
        //
        // // Select the show with the lowest priority for each start time
        // const selectedShows = Object.values(showsGroupedByStartTime).map(group => {
        //     return group.reduce((selected, item) => {
        //         return !selected || item.priority < selected.priority ? item : selected;
        //     }, null);
        // });
        //
        // // Sort, adjust for overlaps, and calculate grid placement as before
        // let sortedShows = selectedShows
        //     .filter(item => {
        //         const itemStart = new Date(item.start_time);
        //         return itemStart >= startOfCurrentHour && itemStart < fourHoursLater;
        //     })
        //     .sort((a, b) => {
        //         const startDiff = new Date(a.start_time) - new Date(b.start_time);
        //         return startDiff !== 0 ? startDiff : a.priority - b.priority;
        //     })
        //     .map((item, index, array) => {
        //         // Grid placement logic remains the same as before
        //         // Ensure the span doesn't exceed the grid or become negative
        //         const itemStart = new Date(item.start_time);
        //         const slotIndex = timeSlots.findIndex(slot => new Date(item.start_time) >= slot && new Date(item.start_time) < new Date(slot.getTime() + 30 * 60000));
        //         let durationSlots = Math.ceil(item.durationMinutes / 30);
        //         if (index < array.length - 1) {
        //             // Adjust for overlaps with subsequent shows
        //         }
        //         const adjustedSpan = Math.max(1, Math.min(durationSlots, timeSlots.length - slotIndex));
        //         return {
        //             ...item,
        //             gridStart: slotIndex + 1,
        //             gridSpan: adjustedSpan
        //         };
        //     });

        // Filter, sort, and adjust shows based on start time, duration, and priority
        // let sortedShows = state.weeklyContent
        //     .filter(item => {
        //         const itemStart = new Date(item.start_time);
        //         return itemStart >= startOfCurrentHour && itemStart < fourHoursLater;
        //     })
        //     .sort((a, b) => {
        //         // Sort by start time; if equal, then by priority
        //         const startDiff = new Date(a.start_time) - new Date(b.start_time);
        //         return startDiff !== 0 ? startDiff : a.priority - b.priority;
        //     })
        //     .map((item, index, array) => {
        //         // Convert back to string format matching start_time format
        //         const formattedItemStartTime = dayjs(item.start_time).format('YYYY-MM-DD HH:mm:ss');
        //
        //         console.log('itemStartTimeInUserTZ: ' + formattedItemStartTime)
        //         // Calculate grid placement for each show
        //         const itemStart = new Date(item.start_time);
        //         const itemEnd = new Date(item.start_time);
        //         itemEnd.setMinutes(itemEnd.getMinutes() + item.durationMinutes);
        //
        //         // Find the index of the slot that the item starts in
        //         // const slotIndex = timeSlots.findIndex(slot => itemStart >= slot && itemStart < new Date(slot.getTime() + 30 * 60000));
        //
        //         // Find the index of the slot that the item starts in
        //         const slotIndex = timeSlots.findIndex(slot => {
        //             return formattedItemStartTime >= slot && formattedItemStartTime < new Date(slot.getTime() + 30 * 60000);
        //         });
        //
        //         let durationSlots = Math.ceil(item.durationMinutes / 30);
        //         // Adjust for overlaps with subsequent shows
        //         if (index < array.length - 1) {
        //             const nextItemStart = new Date(array[index + 1].start_time);
        //             if (itemEnd > nextItemStart) {
        //                 // If overlap, reduce durationSlots
        //                 const overlap = Math.ceil((itemEnd - nextItemStart) / (30 * 60000));
        //                 durationSlots -= overlap;
        //             }
        //         }
        //
        //         // Ensure the span doesn't exceed the grid or become negative
        //         const adjustedSpan = Math.max(1, Math.min(durationSlots, timeSlots.length - slotIndex));
        //
        //         // Return the adjusted show with grid placement information
        //         return {
        //             ...item,
        //             gridStart: slotIndex + 1, // Grid is 1-indexed
        //             gridSpan: adjustedSpan
        //         };
        //     });

        // Create placeholders for each time slot if there's no show scheduled
        // const filledShows = timeSlots.map(slot => {
        //     const formattedSlot = dayjs(slot).format('YYYY-MM-DD HH:mm:ss');
        //     const showForSlot = sortedShows.find(show => dayjs(show.start_time).format('YYYY-MM-DD HH:mm:ss') === formattedSlot);
        //
        //     if (showForSlot) {
        //         return showForSlot; // Return the actual show if it exists
        //     } else {
        //         // Return a placeholder show for empty slots
        //         return {
        //             placeholder: true, // Indicate this is a placeholder
        //             start_time: slot.toISOString(),
        //             durationMinutes: 30, // Assuming a standard 30-minute slot
        //             gridStart: timeSlots.indexOf(slot) + 1,
        //             gridSpan: 1,
        //             content: {
        //                 show: {
        //                     name: "Nothing scheduled."
        //                 }
        //             }
        //         };
        //     }
        // });

        // Return the filled array of shows and placeholders
        // return filledShows;
        // return sortedShows;
        // },
        // nextFourHoursOfContent: (state) => {
        //     const now = new Date(); // Get the current date and time
        //     const start = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours()); // Set to the top of the current hour
        //     const end = new Date(start.getTime() + 4 * 60 * 60 * 1000); // 4 hours later from the start
        //
        //     // Filter weeklyContent for the next 6 hours window
        //     return state.weeklyContent.filter(item => {
        //         const itemStart = new Date(item.start_time);
        //         return itemStart >= start && itemStart < end;
        //     }).sort((a, b) => new Date(a.start_time) - new Date(b.start_time));
        // },
        nextFourHoursWithHalfHourIntervals: (state) => {
            const userStore = useUserStore() // Access the user store
            const userTimezone = userStore.timezone // Get the user's timezone

            const intervals = []
            const now = dayjs().tz(userTimezone) // Get the current time in the user's timezone
            // Use dayjs to handle time correctly in the specified timezone
            let current = now.startOf('hour') // Set to the top of the current hour

            // Generate intervals for the next 4 hours, each 30 minutes apart
            for (let i = 0; i < 8; i++) { // 4 hours / 30 minutes = 8 intervals
                // Push an object with both the formatted time for display and the actual DateTime object
                intervals.push({
                    formatted: current.format('hh:mm A'), // Formatted time for display
                    dateTimeString: current.format('YYYY-MM-DD HH:mm:ss'), // Y-m-d H:m:s format for comparisons
                })
                current = current.add(30, 'minute') // Move to the next 30-minute interval
            }

            return intervals
        },
        upcomingContent: (state) => {
            const start = new Date(state.viewingWindowStart.getTime() - 60 * 60 * 1000) // 1 hour earlier
            const end = new Date(start.getTime() + 7 * 60 * 60 * 1000) // 6 hours later

            // Group shows by start time
            const showsGroupedByStartTime = state.weeklyContent.reduce((acc, item) => {
                const itemStart = new Date(item.start_time).getTime()
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

            // Filter, ensuring they fall within the next 6-hour window, and sort
            return selectedShows
                .filter(item => {
                    const itemStart = new Date(item.start_time)
                    return itemStart >= start && itemStart < end
                })
                .sort((a, b) => new Date(a.start_time) - new Date(b.start_time))

            // // Filter weeklyContent for the next 6 hours window
            // return state.weeklyContent.filter(item => {
            //     const itemStart = new Date(item.start_time);
            //     return itemStart >= start && itemStart < end;
            // }).sort((a, b) => new Date(a.start_time) - new Date(b.start_time));
        },
        nextSixHours: (state) => {
            let adjustedStart = state.viewingWindowStart
            const end = addHours(adjustedStart, 5)
            return eachHourOfInterval({start: adjustedStart, end})
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
        currentMonthIndex: (state) => getMonth(state.currentMonth), // Adds a getter to get the current month's index
        currentMonthName: (state) => format(state.currentMonth, 'MMMM'),
        currentYear: (state) => getYear(state.currentMonth),
        daysInMonth: (state) => {
            const startOfCurrentMonth = startOfMonth(state.currentMonth)
            const endOfCurrentMonth = endOfMonth(state.currentMonth)

            // Adjust these to ensure the grid starts on Sunday and ends on Saturday
            const startOfGrid = startOfWeek(startOfCurrentMonth, {weekStartsOn: 0})
            const endOfGrid = endOfWeek(endOfCurrentMonth, {weekStartsOn: 0})

            // Generate the days for the calendar grid
            return eachDayOfInterval({start: startOfGrid, end: endOfGrid})
        },
        isToday: (state) => {
            const today = new Date()
            const viewingStart = new Date(state.viewingWindowStart)

            return today.toDateString() === viewingStart.toDateString()
        },
    },
})
