<template>
    <header>
    <div class="flex justify-between mb-6">
        <h1 class="text-3xl">
            <Link :href="`/shows/${show.id}`" class="text-indigo-600">{{ show.name }}</Link>
        </h1>
        <span class="text-xs font-semibold text-red-700">Edit Mode</span>
        <div>
            <Link :href="`/shows/${show.id}/manage`">
                <button
                    class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                >Cancel
                </button>
            </Link>

        </div>
    </div>
    </header>

    <div>
        <div class=""><span class="text-xs uppercase font-semibold">Show ID: </span>{{ show.id }}
        </div>
        <div class="mb-6"><span class="text-xs uppercase font-semibold">Team: </span>{{ team.name }}
        </div>
    </div>


</template>

<script setup>
defineProps({
    show: Object,
    team: {
        name: String,
        id: Number,
    }
})

</script>
