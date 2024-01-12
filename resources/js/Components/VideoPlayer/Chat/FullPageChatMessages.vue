    <template>
    <div>
        <div class="chatFullPageMessages hide-scrollbar">
            <div id="scrollToMe"></div>
            <div class="chatFullPageMessages hide-scrollbar">
                <div id="newMessages" v-for="(newMessage, index) in chatStore.newMessages.slice().reverse()" :key="newMessage.id">
                    <message-item :id="newMessage.id" :message="newMessage"/>
                </div>

                <div id="oldMessages" v-for="(oldMessage, index) in chatStore.oldMessages.slice()" :key="index">
                    <message-item :id="oldMessage.id" :message="oldMessage"/>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, onBeforeUnmount, onUpdated, ref, watch } from "vue";
import MessageItem from "@/Components/VideoPlayer/Chat/ChatMessage.vue"
import { useAppSettingStore } from '@/Stores/AppSettingStore';
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
// import dayjs from 'dayjs'
// import relativeTime from "dayjs/plugin/relativeTime"

const appSettingStore = useAppSettingStore();
let chatStore = useChatStore()
let userStore = useUserStore()

// let formattedTime = '';

let props = defineProps({
    message: Object,
})

let channels = ref([])

const channel = Echo.private('chat.' + '1')
channel.subscribed(() => {
}).listen('.chat', (event) => {
    const tempId = Date.now(); // or another method to generate a unique temporary ID
    const newMessage = { ...event.message, id: tempId };
    chatStore.newMessages.push(newMessage)
})

onBeforeMount(async() => {
    await connect();
});

onUpdated(() => {
    scrollTo('#scrollToMe')
    // if (chatStore.newMessages[0]) {
    //     document.getElementById(chatStore.newMessages[0].id).scrollIntoView({behavior: "smooth"})
    // }
})

onBeforeUnmount(() => {
    chatStore.newMessages = [];
    chatStore.oldMessages = [];
    disconnect();
});

// dayjs.extend(relativeTime)

function connect() {
    console.log('STREAM CHAT CONNECTED');
    getChannels();
}

function getChannels() {
    axios.get('/chat/channels')
        .then(response => {
            channels = response;
            setChannel(channels.data[0]);
        })
        .catch(error => {
            console.log(error);
        })
}

function setChannel ( channel ){
    chatStore.currentChannel = channel;
    console.log('Joined Chat Channel: ' + chatStore.currentChannel.name);
    getMessages();
}

function getMessages() {
    axios.get('/chat/channel/' + chatStore.currentChannel.id + '/messages')
        .then( response => {
            chatStore.oldMessages = response.data;
        })
        .catch(error => {
            console.log(error);
        })
    console.log('LOAD MESSAGES');
}

function disconnect() {
    window.Echo.leave("chat." + chatStore.currentChannel.id );
    // chatStore.oldMessages = null;

    console.log('STREAM CHAT DISCONNECTED');
}

// scrollToMe button:
function scrollTo(selector) {
    document.querySelector(selector).scrollIntoView({ behavior: 'smooth'});
}

// Watch for changes in pipChatMode
watch(() => appSettingStore.pipChatMode, (newVal, oldVal) => {
    if (oldVal === true && newVal === false) {
        // If pipChatMode was just turned off, scroll to the bottom
        scrollTo('#scrollToMe');
    }
});

</script>

<script>
export default {
    name: 'messages',
    data() {
        return { messages: [] };
    },
    mounted() {
        this.getMessages();
        this.$nextTick(() => this.scrollToEnd());

    },
    updated() {
        // whenever data changes and the component re-renders, this is called.
        this.$nextTick(() => this.scrollToEnd());
    },
    methods: {
        async getMessages () {
            // ...snip...
        },
        scrollToEnd: function () {
            // scroll to the start of the last message
            this.$el.scrollTop = this.$el.lastElementChild.offsetTop;
        }
    }
}
</script>

<style scoped>
.isMobile {
    left: 1rem;
    width: 10rem;
}
</style>
