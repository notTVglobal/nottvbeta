import { defineStore } from "pinia";
import { getMonth, getYear, startOfMonth, endOfMonth, eachDayOfInterval, addDays, addMinutes, addMonths, subMonths, addHours, eachHourOfInterval, startOfDay, isSameDay, isToday, isYesterday, isTomorrow, format, getHours } from 'date-fns';

const initialState = () => ({
    viewingWindowStart: new Date(),
    currentMonth: new Date(),
    selectedDay: new Date(),
    dayChangedByUser: false,
})

export const useScheduleStore = defineStore('scheduleStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        setSelectedDay(day) {
            this.selectedDay = day;
            this.viewingWindowStart = day;
            this.dayChangedByUser = true; // Indicate that the day was changed by the user
        },
        changeDay(days) {
            // this.viewingWindowStart = addHours(startOfDay(this.viewingWindowStart), days * 24);

            // Calculate the new date based on the input days and current viewingWindowStart
            // const newDay = addHours(startOfDay(this.viewingWindowStart), days * 24);

            // Preserve the current time part of viewingWindowStart while adding days
            const currentTime = this.viewingWindowStart.getHours() * 60 + this.viewingWindowStart.getMinutes();
            let newDay = addDays(startOfDay(this.viewingWindowStart), days);
            newDay = addMinutes(startOfDay(newDay), currentTime); // Add back the preserved time


            // Update viewingWindowStart
            this.viewingWindowStart = newDay;

            // Also update selectedDay to the new day
            this.selectedDay = newDay;

            // this.dayChangedByUser = true; // Indicate that the day was changed by the user
        },
        shiftHours(hours) {
            this.dayChangedByUser = false;
            // this.viewingWindowStart = addHours(this.viewingWindowStart, hours);

            const newViewingWindowStart = addHours(this.viewingWindowStart, hours);

            // Check if the day has changed as a result of the shift
            if (!isSameDay(newViewingWindowStart, this.viewingWindowStart)) {
                // If the day has changed, also update the selectedDay
                this.selectedDay = newViewingWindowStart;
            }

            // Finally, update the viewing window start
            this.viewingWindowStart = newViewingWindowStart;
        },
        isElevenPM(date) {
            return getHours(date) === 23; // Checks if the hour is 23 (11 PM)
        },
        // Actions to change the month
        subtractMonth() {
            this.currentMonth = subMonths(this.currentMonth, 1);
        },
        addMonth() {
            this.currentMonth = addMonths(this.currentMonth, 1);
        },
    },

    getters: {
        nextSixHours: (state) => {
            // const end = addHours(state.viewingWindowStart, 6);
            // return eachHourOfInterval({ start: state.viewingWindowStart, end });

            // Adjust start time if the day has been changed by the user
            const adjustedStart = state.dayChangedByUser
                ? addHours(startOfDay(state.viewingWindowStart), 4) // Start at 4 AM for user-changed days
                : state.viewingWindowStart; // Use current time for initial load

            const end = addHours(adjustedStart, 6); // Calculate end time based on adjusted start
            return eachHourOfInterval({ start: adjustedStart, end });

        },
        dateMessage: (state) => {
            const startDay = startOfDay(state.viewingWindowStart);
            const formattedDate = format(startDay, 'EEEE, MMMM do');
            if (isToday(startDay)) {
                return `Today - ${formattedDate}`;
            } else if (isYesterday(startDay)) {
                return `Yesterday - ${formattedDate}`;
            } else if (isTomorrow(startDay)) {
                return `Tomorrow - ${formattedDate}`;
            } else {
                return formattedDate;
            }
        },
        currentMonthIndex: (state) => getMonth(state.currentMonth), // Adds a getter to get the current month's index
        currentMonthName: (state) => format(state.currentMonth, 'MMMM'),
        currentYear: (state) => getYear(state.currentMonth),
        daysInMonth: (state) => {
            const startDay = startOfMonth(state.currentMonth);
            const endDay = endOfMonth(state.currentMonth);
            return eachDayOfInterval({ start: startDay, end: endDay });
        },
        isToday: (state) => {
            const today = new Date();
            const viewingStart = new Date(state.viewingWindowStart);

            return today.toDateString() === viewingStart.toDateString();
        },
    }
});
