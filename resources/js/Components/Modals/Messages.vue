<template>
    <div class="flex justify-between p-4 m-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
         role="alert"
         v-if="message">
                <span class="font-medium">
                    {{ message }}
                </span>
        <button class="text-xs ml-12" @click="$emit('close')"> Close</button>
    </div>
</template>

<script setup>
defineProps({
    message: String,
})
</script>
