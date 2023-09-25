<template>
    <div>
        <p class="mb-2">
            Server Time: {{ serverTime }}
        </p>
        <p class="mb-2">
            Local Time: {{localTime}}
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
})

</script>
