<template>
    <tr>
        <td class="text-xl font-medium flex gap-x-4 px-6 py-4">
            <img :src="'/storage/images/' + poster" class="h-20 w-20 object-cover">
            <Link :href="`/shows/${id}/manage`" class="text-indigo-600 hover:text-indigo-900">{{ name }}</Link>
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            {{  notes }}
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm text-right">
            {{  showRunnerName }}
        </td>

    </tr>
</template>

<script setup>
defineProps({
    id: String,
    name: String,
    description: String,
    showRunnerName: String,
    poster: String,
    notes: String,
    slug: String,
});
</script>
