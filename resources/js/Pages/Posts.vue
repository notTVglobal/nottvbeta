<template>

    <Head title="Posts" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 pageWidth">
        <div class="bg-white text-black p-5 mb-10">

            <h1 class="text-3xl font-semibold pb-3">Posts</h1>
            <p class="mb-8">
                Display our posts here of new events, blog items, etc.
            </p>
            <div class="bg-orange-300 px-2">
                Display posts here.
            </div>


        </div>

    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import { ref, onMounted } from "vue"
import Pusher from "pusher-js"

let videoPlayer = useVideoPlayerStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false

const props = defineProps({
    user: Object,
});

const username = ref([])
const messages = ref([])
const message = ref('')

onMounted(() => {
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    const pusher = new Pusher('679608fe1b2e6a2bf76b', {
        cluster: 'us3'
    });

    const channel = pusher.subscribe('chat');
    channel.bind('message', data => {
        messages.value.push(data);
    });
})

const submit = async () => {
    await fetch('http://beta.local:8080/api/messages', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            username: props.user.name,
            message: message.value
        })
    })

    message.value = '';
}




</script>
