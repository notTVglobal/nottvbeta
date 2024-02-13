<template>
  <div class="h-1/2 scrollbar-hide w-full">
    <div class="break-all" :class="[pipChatModeChangeHeight, !appSettingStore.fullPage ? 'chatTopRightContainer' : '']">

      <div class="oldMessage" v-for="(oldMessage, index) in chatStore.oldMessages.slice().reverse()" :key="index">
        <message-item :id="oldMessage.id" :message="oldMessage"/>
      </div>
      <div class="newMessage" v-for="(newMessage, index) in chatStore.newMessages.slice()" :key="newMessage.id">
        <message-item :id="newMessage.id" :message="newMessage"/>
      </div>
      <div id="scrollToMe"></div>

    </div>
  </div>
</template>

<script setup>
import { computed, nextTick, onBeforeMount, onBeforeUnmount, onMounted, onUpdated, ref } from "vue"
import dayjs from 'dayjs'
import relativeTime from "dayjs/plugin/relativeTime"
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import MessageItem from "@/Components/Global/Chat/ChatMessage"

const appSettingStore = useAppSettingStore()
const chatStore = useChatStore()
const userStore = useUserStore()

let props = defineProps({
  message: Object,
})

let channels = ref([])

const channel = Echo.private('chat.' + '1')
channel.subscribed(() => {
}).listen('.chat', (event) => {
  const tempId = Date.now(); // or another method to generate a unique temporary ID
  const newMessage = {...event.message, id: tempId};
  chatStore.newMessages.push(newMessage)
})


onBeforeMount(async () => {
  await connect();
});

onMounted(() => {

})
//
// onUpdated(() => {
//     scrollTo('#scrollToMe')
//     // if (chatStore.newMessages[0]) {
//     //     document.getElementById(chatStore.newMessages[0].id).scrollIntoView({behavior: "smooth"})
//     // }
// })

// nextTick(() => {
//     scrollTo('#scrollToMe')
// })

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

function setChannel(channel) {
  chatStore.currentChannel = channel;
  console.log('Joined Chat Channel: ' + chatStore.currentChannel.name);
  getMessages();
}

function getMessages() {
  axios.get('/chat/channel/' + chatStore.currentChannel.id + '/messages')
      .then(response => {
        chatStore.oldMessages = response.data;
      })
      .catch(error => {
        console.log(error);
      })
  console.log('LOAD MESSAGES');
}

function disconnect() {
  window.Echo.leave("chat." + chatStore.currentChannel.id);
  console.log('STREAM CHAT DISCONNECTED');
}


// add a WatchEffect here to update the time stamps
// every few minutes.

// scrollToMe button:
function scrollTo(selector) {
  document.querySelector(selector).scrollIntoView({behavior: 'smooth'});
}

const pipChatModeChangeHeight = computed(() => {
  return appSettingStore.pipChatMode ? 'videoOTTChatMessagesChangeHeightForPipChatMode' : '';
});


// tec21: this is for setting a timer to update the timestamps on the messages.
// onUnmounted( () => {
//     clearInterval(this.timer)
// })

</script>

<script>
export default {
  name: 'messages',
  data() {
    return {messages: []};
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
    async getMessages() {
      // ...snip...
    },
    scrollToEnd: function () {
      // scroll to the start of the last message
      this.$el.scrollTop = this.$el.lastElementChild.offsetTop;
    }
  }
}
</script>
