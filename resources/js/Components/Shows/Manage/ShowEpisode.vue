<template>
    <tr>
        <td class="text-gray-500 px-6 py-4 text-sm">

            <!-- If there is no episode number set by the user
                   the episode number defaults to the episode id           -->
            <div v-if="!episode.episode_number">{{ episode.id }}</div>
            <div v-if="episode.episode_number">{{ episode.episode_number }}</div>

        </td>
        <td class="text-xl font-medium flex items-center gap-x-4 px-6 py-4">
<!--            <img :src="`/storage/images/${episode.poster}`" alt="" class="rounded-xl w-10">-->
            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
            <Link :href="`/shows/${showSlug}/episode/${episode.slug}/manage`" class="font-semibold text-blue-800 hover:text-blue-600">

                {{  episode.name }}

            </Link>
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            {{episode.notes}}
        </td>

        <td class="px-6 py-4 text-right">
            <button class="text-orange-400 text-xl font-semibold">Development</button>
        </td>
    </tr>
</template>

<script setup>
defineProps({
    episode: Object,
    showSlug: String,
});
</script>
