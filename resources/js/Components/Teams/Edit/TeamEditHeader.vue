<template>
    <header>
    <div class="flex justify-between mb-6">
        <h1 class="text-3xl">
            <Link :href="`/teams/${team.slug}`" class="text-indigo-600">{{ team.name }}</Link>
        </h1>
        <span class="text-xs font-semibold text-red-700">Edit Mode</span>
        <div>
            <Link :href="`/teams/${team.slug}/manage`">
                <button
                    class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                >Cancel
                </button>
            </Link>

        </div>
    </div>
    </header>

    <div>
        <div class=""><span class="text-xs uppercase font-semibold">Team ID: </span>{{ team.id }}
        </div>
        <div class="mb-6"><span class="text-xs uppercase font-semibold">Team Leader: </span>{{ teamLeaderName }}
<!--     Insert into span when ready:       -->
        </div>
    </div>


</template>

<script setup>
defineProps({
    team: Object,
    teamLeaderName: String,
})

</script>
