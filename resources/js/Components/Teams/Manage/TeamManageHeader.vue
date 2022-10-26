<template>
    <div class="flex flex-wrap-reverse justify-end gap-2">
        <Link
            v-if="can.editTeam" :href="`/teams/${team.slug}/edit`">
            <button
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
            >Edit
            </button>
        </Link>
        <Link :href="`/dashboard`">
            <button
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
            >Dashboard
            </button>
        </Link>
    </div>
    <header class="flex justify-between mb-3">
        <div>
            <h3 class="inline-flex items-center text-3xl font-semibold relative">
                <img :src="'/storage/images/' + logo" alt="" class="w-20 mr-2">
                <Link :href="`/teams/${team.slug}`" class="">{{ team.name }}</Link>
                <div
                    class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -right-4 -top-0.5">
                    {{ team.memberSpots }}
                </div>
            </h3>

        </div>
    </header>

    <p class="mb-6 p-5">
        {{ team.description }}
    </p>

    <div class="flex justify-end mt-6">
        <div class="flex flex-col">
            <div><span class="text-xs capitalize font-semibold mr-2">Team Leader: </span> {{ teamLeader }} </div>
        </div>
    </div>

    <div
        class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
        role="alert"
        v-if="message"
    >
        <span class="font-medium">
            {{ message }}
        </span>
    </div>
</template>

<script setup>
defineProps({
    team: Object,
    teamLeader: String,
    logo: String,
    can: Object,
    message: String,
})
</script>
