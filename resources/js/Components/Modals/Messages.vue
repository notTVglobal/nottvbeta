<template>
    <div class="flex justify-between p-4 m-4 text-sm rounded-lg"
         :class="messageType"
         role="alert"
         v-if="props.flash.success || props.flash.message || props.flash.warning || props.flash.error">
                <span class="font-medium">
                    {{ props.flash.success }} {{ props.flash.message }} {{ props.flash.warning }} {{ props.flash.error }}
                </span>
<!--        <button class="text-xs ml-12" @click="$emit('close')"> Close</button>-->
        <button class="text-xs ml-12" @click="userStore.showFlashMessage = false"> Close</button>
    </div>
</template>

<script setup>
import {useUserStore} from "@/Stores/UserStore";
import {computed} from "vue";

let userStore = useUserStore()

userStore.showFlashMessage = true;

let props = defineProps({
    flash: Object,

})

const messageType = computed (() => ({
    'text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800': props.flash.success,
    'text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800': props.flash.message,
    'text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800': props.flash.warning,
    'text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800': props.flash.error,
}))
</script>
