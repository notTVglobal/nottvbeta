// import { reactive } from "vue";
import {defineStore } from "pinia";

export let useCounterStore = defineStore('counter', {
    state() {
        return {
            count: 0
        };
    }
})


// this is the Vue way to use reactive stores without using pinia.
// export let counter = reactive({
//     count: 0,
//
//     increment() {
//         if (this.count > 10) {
//             return;
//         }
//         this.count++;
//     }
// });
