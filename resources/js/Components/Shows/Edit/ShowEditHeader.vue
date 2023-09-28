<template>
    <header>
    <div class="flex justify-between pt-6 mb-6">

            <div>
                <div class="font-bold mb-4 text-red-700">EDIT SHOW</div>
                <h1 class="text-3xl">
                    <Link :href="`/shows/${show.slug}`" class="text-red-700 font-bold uppercase">{{ show.name }}</Link>
                </h1>
            </div>
            <div>
                <button
                    type="submit"
                    class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                    :disabled="form.processing"
                    @click="emitSubmit"
                >
                    Save
                </button>
                <button
                    @click="back"
                    class="ml-2 px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                >Cancel
                </button>
            </div>

    </div>
    </header>

    <div>
        <div class="mb-6"><span class="text-xs uppercase font-semibold">Team: </span>
            <Link :href="`/teams/${team.slug}`">
            <span class="font-bold uppercase text-blue-700 dark:text-blue-300 hover:text-blue-500">{{ team.name }}</span>
            </Link>
        </div>
    </div>


</template>

<script setup>
defineProps({
    show: Object,
    team: Object,
    form: Object,
})

const emits = defineEmits(['submit']);

// Emit the "submit" event when the button is clicked.
const emitSubmit = () => {
    emits('submit');
};

function back() {
    window.history.back()
}

</script>
