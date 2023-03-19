<template>

    <div class="flex justify-between mb-6">
        <div>
            <div class="font-bold mb-4 text-red-700">EDIT EPISODE</div>
            <h1 class="text-3xl">
                <Link :href="`/shows/${show.slug}/episode/${episode.slug}`" class="text-red-700 font-bold uppercase">{{ episode.name }}</Link>
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


    <div class="space-y-1">
        <div class="mb-6"><span class="text-xs uppercase font-semibold">Episode #: </span>
            <span v-if="episode.episode_number" class="font-bold uppercase">{{ episode.episode_number }}</span>
            <span v-if="!episode.episode_number" class="font-bold uppercase">{{ episode.id }}</span>
        </div>
        <div class=""><span class="text-xs uppercase font-semibold">Show: </span>
            <Link :href="`/shows/${show.slug}/manage`">
                <span class="font-bold uppercase text-blue-300 hover:text-blue-500">{{ show.name }}</span>
            </Link>
        </div>
        <div class=""><span class="text-xs uppercase font-semibold">Category: </span>
                <span class="font-bold uppercase">{{ show.showCategoryName }}</span>
            <span class="text-xs"> (change the category on the
                <Link :href="`/shows/${show.slug}/edit`" class="text-blue-300 hover:text-blue-500">show edit page</Link>)</span>
        </div>
        <div class=""><span class="text-xs uppercase font-semibold">Sub-category: </span>
                <span class="font-bold uppercase">{{ show.subCategoryName }}</span>
        </div>
        <div class=""><span class="text-xs uppercase font-semibold">Team: </span>
            <Link :href="`/teams/${team.slug}/manage`">
            <span class="font-bold uppercase text-blue-300 hover:text-blue-500">{{ team.name }}</span>
            </Link>
        </div>

    </div>


</template>

<script setup>
defineProps({
    show: Object,
    team: Object,
    episode: Object,
})

function back() {
    window.history.back()
}

</script>
