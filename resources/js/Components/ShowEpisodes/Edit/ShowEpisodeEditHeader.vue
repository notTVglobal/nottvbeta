<template>

    <div class="flex justify-between mb-6">
        <div>
            <div class="font-bold mb-4 text-red-700">EDIT EPISODE</div>
            <h1 class="text-3xl">
                <Link :href="`/shows/${show.slug}/episode/${episode.slug}`" class="text-red-700 font-bold">{{ episode.name }}</Link>
            </h1>
        </div>
        <div>
            <Link :href="`/shows/${show.slug}/episode/${episode.slug}/manage`">
                <button
                    class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                >Cancel
                </button>
            </Link>

        </div>
    </div>


    <div>
        <div class=""><span class="text-xs uppercase font-semibold">Show: </span>
            <Link :href="`/shows/${show.slug}/manage`">
            {{ show.name }}
            </Link>
        </div>
        <div class=""><span class="text-xs uppercase font-semibold">Team: </span>
            <Link :href="`/teams/${team.slug}/manage`">
            {{ team.name }}
            </Link>
        </div>
        <div class="mb-6"><span class="text-xs uppercase font-semibold">Episode #: </span>
                <span v-if="episode.episode_number">{{ episode.episode_number }}</span>
                <span v-if="!episode.episode_number">{{ episode.id }}</span>
        </div>
    </div>


</template>

<script setup>
defineProps({
    show: Object,
    team: Object,
    episode: Object,
})

</script>
