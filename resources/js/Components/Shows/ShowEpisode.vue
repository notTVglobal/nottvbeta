<template>
    <tr>
        <td class="text-gray-500 px-6 py-4 text-sm">
            {{  number }}
        </td>
        <td class="text-xl font-medium flex items-center gap-x-4 px-6 py-4">
            <img :src="`/storage/images/oeV64kpa339M8tmLEZrERCb7bLfuAy8BIqJ17x8t.png`" alt="" class="rounded-xl w-10">
            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
            {{  name }}
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            Notes go here.
        </td>

        <td class="px-6 py-4 text-right">
            <button class="text-orange-400 text-xl font-semibold">Development</button>
        </td>
    </tr>
</template>

<script setup>
defineProps({
   name: String,
   description: String,
    number: String,
    notes: String,
   poster: String,
    status: String,
});
</script>
