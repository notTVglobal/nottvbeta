import { defineStore } from 'pinia'
import { useUserStore } from '@/Stores/UserStore'
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
import relativeTime from 'dayjs/plugin/relativeTime'
import timezone from 'dayjs/plugin/timezone'
import utc from 'dayjs/plugin/utc'

// Extend dayjs with the plugins
dayjs.extend(relativeTime)
dayjs.extend(timezone)
dayjs.extend(utc)

function convertScheduleToTimezone(scheduleData) {
    const userStore = useUserStore(); // Access the UserStore

    return scheduleData.data.map(item => {
        // Convert top-level start_time and end_time using UserStore methods
        const startTimeInUserTz = item.start_time ? userStore.formatDateTimeFromUtcToUserTimezone(item.start_time, 'YYYY-MM-DD HH:mm:ss') : null;
        const endTimeInUserTz = item.end_time ? userStore.formatDateTimeFromUtcToUserTimezone(item.end_time, 'YYYY-MM-DD HH:mm:ss') : null;

        // Check and convert recurrenceDetails if present
        let recurrenceDetailsInUserTz = null;
        if (item.recurrenceDetails) {
            const { start_time, start_date, end_date } = item.recurrenceDetails;
            recurrenceDetailsInUserTz = {
                ...item.recurrenceDetails,
                start_time: start_time ? userStore.formatTimeInUserTimezone(start_time, 'HH:mm:ss') : null,
                start_date: start_date ? userStore.formatDateTimeFromUtcToUserTimezone(start_date, 'YYYY-MM-DD') : null,
                end_date: end_date ? userStore.formatDateTimeFromUtcToUserTimezone(end_date, 'YYYY-MM-DD') : null,
            };
        }

        return {
            ...item,
            start_time: startTimeInUserTz,
            end_time: endTimeInUserTz,
            recurrenceDetails: recurrenceDetailsInUserTz,
        };
    });
}

// Helper function to get dates within the next 6 hours from viewingWindowStart
function getUpcomingContentDates(viewingWindowStart) {
    let dates = [];
    let start = new Date(viewingWindowStart);
    for (let i = 0; i < 6; i++) {
        dates.push(new Date(start.setHours(start.getHours() + i)));
    }
    return dates;
}

const initialState = () => ({
    viewingWindowStart: new Date(),
    currentMonth: new Date(),
    selectedDay: new Date(),
    // viewingMode: 'automatic', // or 'userSelected'
    fiveDaySixHourSchedule: [], // Holds the schedule shows 5 day / 6 hour structured data
    todaysContent: [],
    weeklyContent: [],
    dataFetchLog: [],
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

            // Check if the week of the selected day is already loaded
            const weekStart = startOfWeek(day, {weekStartsOn: 0});
            const weekEnd = endOfWeek(day, {weekStartsOn: 0});

            // First, check if we need to load data for the new week
            if (this.needsDataForWeek(weekStart, weekEnd)) {
                // If new data is needed for the week, load it
                await this.loadWeekFromDate(day).catch(error => {
                    console.error("Failed to load data for the new week:", error);
                });
            }

            // Then, check and fetch for any missing upcoming content
            // This is necessary in case the week data is present but specific upcoming content within the week is missing
            await this.checkAndFetchForUpcomingContent();
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

            // Check if the week of the new day is already loaded
            const weekStart = startOfWeek(newDay, {weekStartsOn: 0});
            const weekEnd = endOfWeek(newDay, {weekStartsOn: 0});

            // Check for the need to load data for the new week
            if (this.needsDataForWeek(weekStart, weekEnd)) {
                await this.loadWeekFromDate(newDay).catch(error => {
                    console.error("Failed to load data for the new week:", error);
                });
            }

            // Then, check and fetch for any missing upcoming content
            await this.checkAndFetchForUpcomingContent();
        },
        async shiftHours(hours) {
            // Shift the viewing window
            this.viewingWindowStart = addHours(this.viewingWindowStart, hours);

            // Update selectedDay if the day has changed
            if (!isSameDay(this.viewingWindowStart, this.selectedDay)) {
                this.selectedDay = startOfDay(this.viewingWindowStart);
            }

            // Check if the week of the new viewing window is already loaded
            const weekStart = startOfWeek(this.viewingWindowStart, { weekStartsOn: 0 });
            const weekEnd = endOfWeek(this.viewingWindowStart, { weekStartsOn: 0 });

            // Check for the need to load data for the new week
            if (this.needsDataForWeek(weekStart, weekEnd)) {
                await this.loadWeekFromDate(this.viewingWindowStart).catch(error => {
                    console.error("Failed to load data for the new week:", error);
                });
            }

            // Then, check and fetch for any missing upcoming content
            await this.checkAndFetchForUpcomingContent();
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
                console.log("Set selected day based on current month:", this.currentMonth);
            } catch (error) {
                console.error(`Failed to set selected day based on current month ${this.currentMonth}:`, error);
            }
        },
        async addMonth() {
            this.currentMonth = addMonths(this.currentMonth, 1)
            try {
                await this.setSelectedDay(this.currentMonth)
                console.log("Set selected day based on current month:", this.currentMonth);
            } catch (error) {
                console.error(`Failed to set selected day based on current month ${this.currentMonth}:`, error);
            }
        },
        async fetchFiveDaySixHourSchedule() {
            try {
                const userStore = useUserStore()
                const response = await axios.get('/api/schedule')

                // Fallback to response timezone if userStore.timezone is not set
                const timezone = userStore.timezone || response.data.userTimezone || 'UTC'; // Additional fallback to 'UTC'

                // Convert the entire schedule, including nested recurrenceDetails, to the user's timezone
                this.fiveDaySixHourSchedule = convertScheduleToTimezone(response.data, timezone)
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
                const timezone = userStore.timezone || response.data.userTimezone || 'UTC'; // Additional fallback to 'UTC'

                this.todaysContent = convertScheduleToTimezone(response.data, timezone)
            } catch (error) {
                console.error('Failed to fetch today\'s content:', error)
            }
        },
        async preloadWeeklyContent() {
            const userStore = useUserStore()
            try {
                const response = await axios.get('/api/schedule/week')
                if (!userStore.timezone) {
                    console.error("Timezone is not set.");
                    return; // Or handle this case as appropriate for your app
                }

                // Fallback to response timezone if userStore.timezone is not set
                const timezone = userStore.timezone || response.data.userTimezone; // Additional fallback to 'UTC'

                this.weeklyContent = convertScheduleToTimezone(response.data, timezone)
            } catch (error) {
                console.error('Failed to preload weekly content:', error)
            }
        },
        async loadWeekFromDate(date) {
            let formattedDate; // Declare formattedDate outside of the try block
            try {
                const userStore = useUserStore();
                // Ensure the date is in UTC format for the request
                const formattedDate = date.toISOString().split('T')[0];
                console.log(`Loading week data for date: ${formattedDate}`); // Log the date being requested

                const response = await axios.get(`/api/schedule/week/${formattedDate}`);
                console.log('Received response:', response.data); // Log the raw response data

                // Fallback to response timezone if userStore.timezone is not set
                const timezone = userStore.timezone || response.data.userTimezone || 'UTC'; // Additional fallback to 'UTC'
                console.log(`Using timezone: ${timezone}`); // Log the timezone being used

                const newData = convertScheduleToTimezone(response.data, timezone); // Ensure you are accessing the correct data property from the response

                // Update the fetch log with the current fetch
                const weekStart = startOfWeek(new Date(date), { weekStartsOn: 0 }).toISOString();
                const weekEnd = endOfWeek(new Date(date), { weekStartsOn: 0 }).toISOString();
                const fetchTime = new Date().toISOString();

                const existingLogIndex = this.dataFetchLog.findIndex(log => log.weekStart === weekStart && log.weekEnd === weekEnd);
                if (existingLogIndex !== -1) {
                    this.dataFetchLog[existingLogIndex].lastFetch = fetchTime;
                } else {
                    this.dataFetchLog.push({ weekStart, weekEnd, lastFetch: fetchTime });
                }

                // Merge newData into weeklyContent, avoiding duplicates
                this.weeklyContent = [...this.weeklyContent, ...newData].filter((value, index, self) =>
                        index === self.findIndex((t) => (
                            t.id === value.id && t.start_time === value.start_time
                        ))
                );
            } catch (error) {
                console.error(`Failed to load content for week starting ${formattedDate}:`, error);
            }
        },

        needsDataForWeek(weekStart, weekEnd) {
            // Extend weekEnd to cover the span of upcoming content from viewingWindowStart
            const extendedEnd = this.calculateExtendedEndForUpcomingContent();

            // Adjust weekEnd if the extended end is beyond the original weekEnd
            if (extendedEnd > weekEnd) {
                weekEnd = extendedEnd;
            }

            // Now weekEnd includes any additional day(s) that might be displayed
            weekEnd.setHours(23, 59, 59, 999);

            const hasDataForExtendedWeek = this.weeklyContent.some(content => {
                const contentDate = new Date(content.start_time);
                return contentDate >= weekStart && contentDate <= weekEnd;
            });

            console.log('Has data for extended week range:', hasDataForExtendedWeek);
            return !hasDataForExtendedWeek;
        },
        calculateExtendedEndForUpcomingContent() {
            // Assuming viewingWindowStart is the reference start time for upcoming content
            const baseStartTime = new Date(this.viewingWindowStart);
            // Extend by 6 hours to cover the upcoming content span
            // Return the extended end time, potentially adjusting into the next day
            return new Date(baseStartTime.getTime() + (6 * 60 * 60 * 1000));
        },
        // Function to check for and fetch missing upcoming content
        async checkAndFetchForUpcomingContent() {
            const upcomingDates = getUpcomingContentDates(this.viewingWindowStart);
            const now = new Date();
            const fifteenMinutesAgo = new Date(now.getTime() - 15 * 60000);

            for (const date of upcomingDates) {
                const dateString = date.toISOString().split('T')[0];
                const contentCoverageAndFreshness = this.weeklyContent.some(content => {
                    const contentDate = new Date(content.start_time).toDateString();
                    const lastFetchedTime = this.dataFetchLog[dateString];
                    const isFresh = lastFetchedTime && new Date(lastFetchedTime) > fifteenMinutesAgo;
                    return date.toDateString() === contentDate && isFresh;
                });

                if (!contentCoverageAndFreshness) {
                    // This date needs data fetching
                    await this.fetchDataAndUpdateLog(dateString, date);
                    break; // Assuming you fetch data for the week, so no need to check further dates within the same week
                }
            }
        },
        async fetchDataAndUpdateLog(dateString, date) {
            try {
                await this.loadWeekFromDate(date);
                // Successfully fetched, so update the log
                this.dataFetchLog[dateString] = new Date().toISOString();
                console.log("Data fetched successfully for date:", dateString);
            } catch (error) {
                console.error(`Failed to fetch data for date ${dateString}:`, error);
            }
        }

    },

    getters: {
        upcomingContent: (state) => {
            const start = new Date(state.viewingWindowStart.getTime() - 60 * 60 * 1000); // 1 hour earlier
            const end = new Date(start.getTime() + 7 * 60 * 60 * 1000); // 6 hours later

            // Filter weeklyContent for the next 6 hours window
            return state.weeklyContent.filter(item => {
                const itemStart = new Date(item.start_time);
                return itemStart >= start && itemStart < end;
            }).sort((a, b) => new Date(a.start_time) - new Date(b.start_time));
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
            const startOfCurrentMonth = startOfMonth(state.currentMonth);
            const endOfCurrentMonth = endOfMonth(state.currentMonth);

            // Adjust these to ensure the grid starts on Sunday and ends on Saturday
            const startOfGrid = startOfWeek(startOfCurrentMonth, { weekStartsOn: 0 });
            const endOfGrid = endOfWeek(endOfCurrentMonth, { weekStartsOn: 0 });

            // Generate the days for the calendar grid
            return eachDayOfInterval({ start: startOfGrid, end: endOfGrid });
        },
        isToday: (state) => {
            const today = new Date()
            const viewingStart = new Date(state.viewingWindowStart)

            return today.toDateString() === viewingStart.toDateString()
        },
    },
})
