<template>
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <img :src="'/storage/images/' + show.poster" class="h-20 w-20 object-cover">
        </td>

        <td class="px-6 py-4 whitespace-nowrap">
            <Link :href="`/shows/${show.slug}/manage`" class="uppercase text-xl light:text-blue-600 light:hover:text-blue-900 dark:text-blue-200 dark:hover:text-blue-400">{{ show.name }}</Link>
        </td>

        <td class="light:text-gray-600 dark:text-gray-100 px-6 py-4 text-sm">
            {{  show.notes }} NOTES GO HERE
        </td>

        <td class="light:text-gray-600 dark:text-gray-100 px-6 py-4 text-sm text-right">
            {{  show.showRunnerName }}
        </td>

    </tr>
</template>

<script setup>
defineProps({
    show: Object,
});
</script>
