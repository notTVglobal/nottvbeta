<template>
    <div>
        <p class="mb-2">
            Server Time: {{ serverTime }}
        </p>
        <p class="mb-2">
            Local Time: {{localTime}}
        </p>
        <p class="mb-2">
            User's Timezone: {{userTimezone}}
        </p>
    </div>
</template>

<script setup>

import {onMounted, ref} from "vue";

let serverTime = ref('')
let localTime = ref('');

const updateLocalTime = () => {
    const userLocalDate = new Date();
    localTime.value = userLocalDate.toLocaleString(); // Format as desired
};

async function fetchServerTime() {
    try {
        const response = await axios.get('/admin/server-time'); // Replace with your actual API endpoint
         // Assuming your server returns the time as a string
        serverTime.value = response.data.serverTime;
        console.log(serverTime.value)
    } catch (error) {
        console.error('Error fetching server time', error);
    }
}

onMounted( () => {
    fetchServerTime();
    updateLocalTime();
    getUserTimezone()
})

const userTimezone = ref('');

const getUserTimezone = () => {
    // Use the Intl object to get the user's timezone
    userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

</script>
