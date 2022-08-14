<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
            <div v-if="chatToggle.show" class="absolute h-full text-white pb-2 px-2 chat-mask overflow-y-auto scroll-smooth hover:scroll-auto">
    <!--            <div class="absolute top-16 left-0 p-5 drop-shadow" v-if="videoPlayer.fullPage"><span class="text-xs uppercase pr-2">CHAT BOX </span><span class="font-semibold">Chat goes here.</span></div>-->
    <!--            <div v-if="!videoPlayer.fullPage" class="bg-gray-800 px-2"><span class="text-xs uppercase pr-2">CHAT BOX </span><span class="font-semibold">Chat goes here</span></div>-->

                    CHAT
<!--                    Set username manually-->
<!--                                <div>-->
<!--                                    <input class="border border-2 text-black font-semibold p-2 mt-2" v-model="username"/>-->
<!--                                </div>-->
<!--                    <div class="h-2/5">-->
                        <div class="list-group list-group-flush border-bottom b-20 w-fit min-h-max max-h-max mb-24">
                            <div class="list-group-item py-3 leading-tight" v-for="message in messages" :key="message">
                                <div class="flex w-100 align-items-center justify-content-between">
                                    <strong class="mb-1">{{ message.username }}</strong>
                                </div>
                                <div class="col-10 mb-1 small">{{ message.message }}</div>
                            </div>

                        </div>
                        <form @submit.prevent="submit" class="w-full">
                            <input class="fixed bottom-0 right-0 w-fit text-black form-control border-2 p-2 m-2" placeholder="Write a message..." v-model="message"/>
                        </form>
<!--                    </div>-->
                <div refs="scrollToMe"></div>
                </div>

    </Transition>
</template>

<script setup>
import { useChatStore } from "@/Stores/ChatStore";
import { ref, onMounted } from "vue"
import Pusher from "pusher-js"

let chatToggle = useChatStore();
chatToggle.show = false;

const props = defineProps({
    user: Object,
});

// set username manually in ChatForm
// const username = ref([])
const messages = ref([])
const message = ref('')

function scrollToElement() {
    const el = this.$refs.scrollToMe;
    if (el) {
        el.scrollIntoView({ behavior: "smooth" });
    }
};

onMounted(() => {
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    // Pusher Key for development: 679608fe1b2e6a2bf76b
    // Pusher Key for staging: d03ec1b33bc0f17392c4
    // Pusher Key for production: f0b385d3a5994dca4741
    const pusher = new Pusher('d03ec1b33bc0f17392c4', {
        cluster: 'us3'
    });

    const channel = pusher.subscribe('chat');
    channel.bind('message', data => {
        messages.value.push(data);

    });
})


// URI for development: http://beta.local:8080/api/messages
// URI for staging: https://beta-staging.not.tv/api/messages
// URI for production: https://beta.not.tv/api/messages
const submit = async () => {
    await fetch('https://beta-staging.not.tv/api/messages', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            username: props.user.name,
            message: message.value
        })
    })

    message.value = '';
    scrollToElement();
}


</script>


<style>


</style>
