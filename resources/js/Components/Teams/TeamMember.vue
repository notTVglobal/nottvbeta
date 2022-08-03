<template>
    <tr>
        <td class="text-xl font-medium flex items-center gap-x-4 px-6 py-4">
            <img :src="`https://i.pravatar.cc/50?u=${email}`" alt="" class="rounded-xl w-10">
            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
            {{  name }}
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            {{  email }}
        </td>

        <td class="px-6 py-4">
            <button class="text-green-400 text-xl font-semibold">{{ status }}</button>
        </td>
    </tr>
</template>

<script setup>
defineProps({
   name: String,
   email: String,
   status: String,
});
</script>
