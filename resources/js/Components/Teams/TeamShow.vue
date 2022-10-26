<template>
    <tr>
        <td class="text-xl font-medium flex gap-x-4 px-6 py-4">
            <img :src="'/storage/images/' + show.poster" class="h-20 w-20 object-cover">
            <Link :href="`/shows/${show.slug}/manage`" class="text-indigo-600 hover:text-indigo-900">{{ show.name }}</Link>
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            {{  show.notes }}
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
