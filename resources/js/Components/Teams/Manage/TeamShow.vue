<template>
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <img :src="'/storage/images/' + show.poster" class="h-20 w-20 object-cover">
        </td>

        <td class="px-6 py-4 whitespace-nowrap">
            <Link :href="`/shows/${show.slug}/manage`" class="text-indigo-600 hover:text-indigo-900">{{ show.name }}</Link>
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            {{  show.notes }} NOTES GO HERE
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm text-right">
            {{  show.showRunnerName }}
        </td>

    </tr>
</template>

<script setup>
defineProps({
    show: Object,
});
</script>
