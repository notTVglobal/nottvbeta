<template>
    <div class="shadow overflow-hidden bg-gray-50 border-b border-gray-200 sm:rounded-lg p-5 my-8">

        <div class="my-6 ml-10 md:w-3/4">
            <div class="text-sm font-semibold uppercase mb-2">
                <button onclick="editDescriptionNotice.showModal()"
                        class="bg-blue-600 hover:bg-blue-500 text-white px-2 py-1 rounded-lg">
                    <font-awesome-icon icon="fa-pencil" />
                </button>
                Episode Description
            </div>
            {{ episode.description }}
        </div>

    </div>

</template>

<script setup>

defineProps({
    episode: Object,
})

</script>
