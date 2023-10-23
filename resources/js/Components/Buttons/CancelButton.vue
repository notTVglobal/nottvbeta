<template>
    <div>
        <button
            @click="cancel"
            class="ml-2 px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
        >Cancel
        </button>
    </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { useUserStore } from "@/Stores/UserStore"

let userStore = useUserStore()

function cancel() {
    if (userStore.prevUrl) {
        Inertia.visit(userStore.prevUrl)
    }
}
</script>
