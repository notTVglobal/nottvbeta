<template>
    <header>
    <div class="flex justify-between mb-6">

        <div>
            <div class="font-bold mb-4 text-red-700">EDIT TEAM</div>
            <h1 class="text-3xl">
                <Link :href="`/teams/${team.slug}`" class="text-red-700 font-bold uppercase">{{ team.name }}</Link>
            </h1>
        </div>
        <div>
            <button
                @click="back"
                class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
            >Cancel
            </button>
        </div>

    </div>
    </header>

    <div>
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

function back() {
    window.history.back()
}

</script>
