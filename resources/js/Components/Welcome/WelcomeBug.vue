<template>
    <Transition
        enter-from-class="opacity-0 scale-125"
        enter-to-class="opacity-100 scale-100"
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-125"
    >
        <div v-if="welcomeStore.showOverlay===false">
            <div class="absolute top-4 left-20">
                <img :src="`/storage/images/logo_white_512.png`" class="w-20 md:w-40 pt-2">
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { useWelcomeStore } from "@/Stores/WelcomeStore.js"

let welcomeStore = useWelcomeStore()

</script>
