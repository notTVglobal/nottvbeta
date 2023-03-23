<template>
<div>

    <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>


    Invite Codes


</div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { useForm } from "@inertiajs/inertia-vue3";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'admin'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(async () => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    message: String,
})

let form = useForm({
    //
})

let submit = () => {
    //
};

let showMessage = ref(true);

</script>
