import { reactive } from 'vue'

export const eventBus = reactive({
    tick: 0,
    intervalId: null, // Store the interval ID
    start() {
        const now = new Date();
        const secondsUntilNextMinute = 60 - now.getSeconds();

        // Start the first update exactly at the next minute
        setTimeout(() => {
            this.tick++;

            // Set an interval to continue updating every 60 seconds
            this.intervalId = setInterval(() => {
                this.tick++;
            }, 60000); // Update every minute

        }, secondsUntilNextMinute * 1000);
    },
    stop() {
        if (this.intervalId) {
            clearInterval(this.intervalId)
            this.intervalId = null
        }
    },
})
