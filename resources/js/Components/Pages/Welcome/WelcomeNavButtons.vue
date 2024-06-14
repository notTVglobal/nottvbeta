<template>
  <div class="flex flex-row flex-wrap items-center justify-center md:justify-end pt-2 lg:pt-8 lg:pr-6 w-full gap-2">
    <Button
        class="h-fit py-2 px-4 md:py-4 md:px-6 bg-opacity-50 hover:bg-opacity-75 text-lg md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md"
        v-if="!$page.props.auth.user" @click="router.visit('/teams')">
      Browse
    </Button>
    <Button
        class="h-fit py-2 px-4 md:py-4 md:px-6 bg-opacity-50 hover:bg-opacity-75 text-lg md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md"
        v-if="!$page.props.auth.user" @click="router.visit('/news')">
      News
    </Button>
    <Button
        class="h-fit py-2 px-4 md:py-4 md:px-6 bg-opacity-50 hover:bg-opacity-75 text-lg md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md"
        v-if="!$page.props.auth.user" @click="router.visit('/schedule')">
      Schedule
    </Button>
    <Button
        class="h-fit py-2 px-4 md:py-4 md:px-6 bg-opacity-50 hover:bg-opacity-75 text-lg md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md"
        v-if="!$page.props.auth.user" @click="welcomeStore.showLogin = true">
      Log in
    </Button>
    <Button v-if="!$page.props.auth.user"
            class="h-fit py-2 px-4 md:py-4 md:px-6 bg-opacity-50 hover:bg-opacity-75 text-lg mr-2 md:mr-0 md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md"
            @click="welcomeStore.showRegister = true">Register
    </Button>
  </div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import { useWelcomeStore } from '@/Stores/WelcomeStore'
import Button from '@/Jetstream/Button'

const welcomeStore = useWelcomeStore()
</script>