<template>
    <div class="messageBox flex justify-between p-4 m-4 text-sm rounded-lg"
         :class="messageType"
         role="alert"
         v-if="message">
                <span class="font-medium">
                    {{ message }}
                </span>
        <button class="text-xs ml-12" @click="$emit('close')"> Close</button>
    </div>
</template>

<script setup>
import {computed} from "vue";

let props = defineProps({
    message: String,
    messageType: String,
})

let messageStyle = '';

if (props.messageType) {
    messageStyle = props.messageType
} else messageStyle = 'message'

const messageType = computed (() => ({
    'text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800': messageStyle === "success",
    'text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800': messageStyle === "message",
    'text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800': messageStyle === "warning",
    'text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800': messageStyle === "error",
}))
</script>

<style scoped>

.messageBox {
    z-index: 999;
}
</style>
