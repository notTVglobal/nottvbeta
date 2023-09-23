<template>
    <div>
<!--        &lt;!&ndash; Date Picker &ndash;&gt;-->
<!--        <v-date-picker-->
<!--            v-model="selectedDate"-->
<!--            is-inline-->
<!--            :attributes="{-->
<!--        placeholder: 'Select a Date',-->
<!--      }"-->
<!--        />-->

<!--        &lt;!&ndash; Time Picker &ndash;&gt;-->
<!--        <v-time-picker-->
<!--            v-model="selectedTime"-->
<!--            :step-minutes="15"-->
<!--            :format="12"-->
<!--            :min-time="'00:00:00'"-->
<!--            :max-time="'23:59:59'"-->
<!--            :attributes="{-->
<!--        placeholder: 'Select a Time',-->
<!--      }"-->
<!--        />-->


<!--        <template>-->
<!--            <Calendar />-->
<!--&lt;!&ndash;            <DatePicker v-model="date" />&ndash;&gt;-->
<!--            <VDatePicker v-model="date" mode="dateTime" :popover="false" hide-time-header>-->
<!--                <template #default="{ togglePopover, inputValue, inputEvents }">-->
<!--                    <div-->
<!--                        class="flex rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden"-->
<!--                    >-->
<!--                        <button-->
<!--                            class="flex justify-center items-center px-2 bg-accent-100 hover:bg-accent-200 text-accent-700 border-r border-gray-300 dark:bg-gray-700 dark:text-accent-300 dark:border-gray-600 dark:hover:bg-gray-600"-->
<!--                            @click="() => togglePopover()"-->
<!--                        >-->
<!--                            <IconCalendar class="w-5 h-5" />-->
<!--                        </button>-->
<!--                        <input-->
<!--                            :value="inputValue"-->
<!--                            v-on="inputEvents"-->
<!--                            class="flex-grow px-2 py-1 bg-white dark:bg-gray-700"-->
<!--                        />-->
<!--                    </div>-->
<!--                </template>-->
<!--            </VDatePicker>-->
<!--        </template>-->

            <DatePicker v-model="date" mode="dateTime" hide-time-header >
                <template #default="{ togglePopover }">
                    <button
                        class="px-3 py-2 bg-blue-500 text-sm text-white font-semibold rounded-md"
                        @click.prevent="togglePopover"
                    >
                        <slot name="buttonName">Select date</slot>
                    </button>
                </template>
            </DatePicker>

    </div>
</template>

<script setup>
import {ref, defineProps, defineEmits, watch} from 'vue';
import VDatePicker from 'v-calendar';
// import VCalendar from 'v-calendar';
import { Calendar, DatePicker } from 'v-calendar';
import 'v-calendar/style.css';

const props = defineProps({
    // initialDate: Date,
    // initialTime: String,
    inputValue: String,
});

const emits = defineEmits();

// let date = new Date();
let date = ref(new Date());
const calendar = ref(null);
const inputValue = ref(props.inputValue || null);

// Define refs to store selected date and time
// const selectedDate = ref(props.initialDate || null);
// const selectedTime = ref(props.initialTime || null);



// Watch for changes in selected dateTime emit it
watch([date], ([newDate]) => {
    emits('date-time-selected', { date: newDate });
});
</script>
