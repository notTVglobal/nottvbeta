<template>
    <div>
        <button
            @click="back"
            class="ml-2 px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
        >Back
        </button>
    </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { useUserStore } from "@/Stores/UserStore"

let userStore = useUserStore()

function back() {
    if (userStore.prevUrl) {
        Inertia.visit(userStore.prevUrl)
    }
}
</script>
