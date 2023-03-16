<template>
    <tr>
        <td class="text-gray-500 px-6 py-4 text-sm">

            <!-- If there is no episode number set by the user
                   the episode number defaults to the episode id           -->
            <div v-if="!episode.episodeNumber">{{ episode.id }}</div>
            <div v-if="episode.episodeNumber">{{ episode.episodeNumber }}</div>

        </td>
        <td class="text-xl font-medium flex items-center gap-x-4 px-6 py-4 uppercase">
<!--            <img :src="`/storage/images/${episode.poster}`" alt="" class="rounded-xl w-10">-->
            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
            <Link :href="`/shows/${showSlug}/episode/${episode.slug}/manage`" class="font-semibold light:text-blue-800 light:hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-200">

                {{  episode.name }}

            </Link>
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            {{episode.notes}}
        </td>

        <td class="px-6 py-4 text-right">
            <button v-if="episode.episodeStatusId===1" class="font-semibold text-xl text-orange-400">
                {{ episode.episodeStatus }}
            </button>
            <button v-if="episode.episodeStatusId===2" class="text-xl text-green-400">
                {{ episode.episodeStatus }}
            </button>
            <button v-if="episode.episodeStatusId===3" class="font-semibold text-xl text-green-600">
                {{ episode.episodeStatus }}
            </button>
            <button v-if="episode.episodeStatusId===4" class="font-bold text-xl text-green-600">
                {{ episode.episodeStatus }}
            </button>
            <button v-if="episode.episodeStatusId===5" class="font-semibold text-xl text-purple-700">
                {{ episode.episodeStatus }}
            </button>
            <button v-if="episode.episodeStatusId===6" class="font-italic text-xl text-pink-500">
                {{ episode.episodeStatus }}
            </button>
            <button v-if="episode.episodeStatusId===7" class="font-bold text-xl text-black">
                {{ episode.episodeStatus }}
            </button>
            <button v-if="episode.episodeStatusId===8" class="font-medium text-xl text-gray-500">
                {{ episode.episodeStatus }}
            </button>
            <button v-if="episode.episodeStatusId===9" class="font-semibold font-italic text-xl text-red-700">
                {{ episode.episodeStatus }}
            </button>
            <button v-if="episode.episodeStatusId===10" class="font-bold text-xl text-red-800">
                {{ episode.episodeStatus }}
            </button>


        </td>
    </tr>
</template>

<script setup>
defineProps({
    episode: Object,
    showSlug: String,
});
</script>
