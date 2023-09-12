<template>
    <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 mb-4">
        <Link
            :href="`/newsroom`"><button
            class="bg-yellow-600 hover:bg-yellow-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
            v-if="can.viewNewsroom"
        >Newsroom</button>
        </Link>
        <Link
            :href="`/newsroom`"><button
            class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
        >Upload Press Release</button>
        </Link>
        <Link
            :href="`/news/create`"><button
            class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
            v-if="can.createNewsPost"
        >Create News</button>
        </Link>
    </div>
</template>

<script setup>
defineProps({
    can: Object,
})
</script>
