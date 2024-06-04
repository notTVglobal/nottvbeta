<template>
  <div @click.self="socialShareStore.hideSocialSharing"
       class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-999">
    <div class="relative w-full max-w-md xl:max-w-2xl xl:w-1/3">
      <!-- Outer Border -->
      <div class="absolute -inset-2 rounded-lg opacity-75 blur-sm bg-gradient-to-br from-blue-600 to-blue-400"></div>
      <!-- Inner Border -->
      <div class="absolute -inset-1 rounded-lg opacity-100 blur bg-gradient-to-br from-blue-500 to-blue-300"></div>
      <!-- Modal Content -->
      <div class="relative bg-darkgray px-6 pt-6 pb-16 rounded-lg">
        <button @click="socialShareStore.hideSocialSharing"
                class="absolute bottom-5 right-5 bg-red-500 text-white py-1 px-3 rounded">
          Close
        </button>
        <div class="flex flex-col">
          <div class="flex flex-col items-center space-y-2 xl:flex-row xl:space-x-4 xl:space-y-0 pb-4">
            <img src="/storage/images/Ping.png" alt="notTV Ping" class="max-h-10"/>
            <div class="text-center xl:text-left">
              <h3 class="text-blue-400">Let's Get Social</h3>
              <h4 class="text-blue-300">Help us spread the word and build our community!</h4>
            </div>
          </div>
          <div
              class="flex flex-col max-w-full items-center space-y-4 mb-6 p-4 rounded-lg bg-darkgray-light xl:flex-row xl:items-start xl:space-x-3 xl:space-y-0">
            <img :src="socialShareStore.media" :alt="`image`" class="max-h-48 w-24 object-contain"/>
            <div class="flex flex-col px-6 xl:px-0 text-center xl:text-left space-y-2">
              <h4 class="text-orange-500 text-lg">{{ socialShareStore.title }}</h4>
              <h5 class="font-light text-white">{{ shortDescription }}</h5>
              <h5 class="text-xs tracking-wider text-gray-400">{{ socialShareStore.hashtags }}</h5>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap gap-2 justify-center xl:justify-start xl:gap-4">
          <ShareNetwork
              v-for="network in networks"
              :key="network.network"
              :network="network.network"
              :title="network.title"
              :url="network.url"
              :description="network.description"
              :quote="network.quote"
              :hashtags="network.hashtags"
              :twitterUser="network.twitterUser"
              :media="network.media"
              v-slot="{ share }"
          >
            <div @click="handleShare(network, share)"
                 :style="{ backgroundColor: network.color }"
                 class="social-network flex items-center justify-center p-2 rounded-lg text-white cursor-pointer">
              <font-awesome-icon :icon="[network.iconPrefix, network.iconName]"/>
              <span class="ml-2">{{ network.name }}</span>
            </div>
          </ShareNetwork>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { computed, onBeforeUnmount } from 'vue'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useSocialShareStore } from '@/Stores/SocialShareStore'
import { ShareNetwork } from 'vue3-social-sharing'

const notificationStore = useNotificationStore()
const socialShareStore = useSocialShareStore()

function decodeHTMLEntities(text) {
  const textArea = document.createElement('textarea');
  textArea.innerHTML = text;
  return textArea.value;
}

function stripHTMLTags(text) {
  // Replace HTML tags with a space
  const noTags = text.replace(/<\/?[^>]+(>|$)/g, " ");
  // Replace multiple spaces with a single space
  return noTags.replace(/\s\s+/g, ' ').trim();
}


const shortDescription = computed(() => {
  const description = stripHTMLTags(decodeHTMLEntities(socialShareStore.description));
  const maxLength = 300
  return description.length > maxLength ? `${description.substring(0, maxLength)}...` : description
})

const networks = [
  {
    network: 'email',
    name: 'Email',
    iconPrefix: 'fas',
    iconName: 'envelope',
    color: '#333333',
    title: socialShareStore.title, // shows up as subject line
    url: socialShareStore.url, // appears in the body
    description: socialShareStore.decodedDescription, // appears in the body
    // quote: props.quote,
    // hashtags: props.hashtags,
    // twitterUser: props.twitterUser,
    // media: props.media
  },
  {
    network: 'facebook',
    name: 'Facebook',
    iconPrefix: 'fab',
    iconName: 'facebook-f',
    color: '#1877f2',
    url: socialShareStore.url,
    hashtags: socialShareStore.hashtags, // Facebook shows this.
    media: socialShareStore.media, // TODO: not showing up... needs to be troubleshooted
    // title: props.title,
    // description: props.description,
    // quote: props.quote,
    // twitterUser: props.twitterUser,
  },
  {
    network: 'messenger',
    name: 'Messenger',
    iconPrefix: 'fab',
    iconName: 'facebook-messenger',
    color: '#0084ff',
    title: socialShareStore.title,
    url: socialShareStore.url,
    // description: props.description,
    // quote: props.quote,
    // hashtags: props.hashtags,
    // twitterUser: props.twitterUser,
    // media: props.media
  },
  // {
  //   network: 'pinterest',
  //   name: 'Pinterest',
  //   iconPrefix: 'fab',
  //   iconName: 'pinterest',
  //   color: '#bd081c',
  //   title: props.title,
  //   url: props.url,
  //   // description: props.description,
  //   // quote: props.quote,
  //   // hashtags: props.hashtags,
  //   // twitterUser: props.twitterUser,
  //   // media: props.media
  // },
  {
    network: 'pocket',
    name: 'Pocket',
    iconPrefix: 'fab',
    iconName: 'get-pocket',
    color: '#ef4056',
    url: socialShareStore.url,
    // title: props.title,
    // description: props.description,
    // quote: props.quote,
    // hashtags: props.hashtags,
    // twitterUser: props.twitterUser,
    // media: props.media
  },
  {
    network: 'sms',
    name: 'SMS',
    iconPrefix: 'fas',
    iconName: 'comment-dots',
    color: '#333333',
    title: socialShareStore.title,
    url: socialShareStore.url,
    description: socialShareStore.decodedDescription,
    quote: socialShareStore.quote,
    hashtags: socialShareStore.hashtags,
    twitterUser: socialShareStore.twitterUser,
    media: socialShareStore.media,
  },
  {
    network: 'telegram',
    name: 'Telegram',
    iconPrefix: 'fab',
    iconName: 'telegram-plane',
    color: '#0088cc',
    title: socialShareStore.title,
    url: socialShareStore.url,
    description: socialShareStore.decodedDescription,
    // quote: props.quote,
    // hashtags: props.hashtags,
    // twitterUser: props.twitterUser,
    // media: props.media
  },
  // {
  //   network: 'tumblr',
  //   name: 'Tumblr',
  //   iconPrefix: 'fab',
  //   iconName: 'tumblr',
  //   color: '#35465c',
  //   title: props.title,
  //   url: props.url,
  //   description: props.description,
  //   quote: props.quote,
  //   hashtags: props.hashtags,
  //   twitterUser: props.twitterUser,
  //   media: props.media
  // },
  {
    network: 'twitter',
    name: 'Twitter',
    iconPrefix: 'fab',
    iconName: 'x-twitter',
    color: '#1da1f2',
    title: socialShareStore.title,
    url: socialShareStore.url,
    description: socialShareStore.decodedDescription,
    quote: socialShareStore.quote,
    hashtags: socialShareStore.hashtags,
    twitterUser: socialShareStore.twitterUser,
    media: socialShareStore.media,
  },
  {
    network: 'whatsapp',
    name: 'Whatsapp',
    iconPrefix: 'fab',
    iconName: 'whatsapp',
    color: '#25d366',
    title: socialShareStore.title,
    url: socialShareStore.url,
    description: socialShareStore.decodedDescription,
    quote: socialShareStore.quote,
    hashtags: socialShareStore.hashtags,
    twitterUser: socialShareStore.twitterUser,
    media: socialShareStore.media,
  },
  {
    network: 'wordpress',
    name: 'Wordpress',
    iconPrefix: 'fab',
    iconName: 'wordpress',
    color: '#21759b',
    title: socialShareStore.title,
    url: socialShareStore.url,
    description: socialShareStore.decodedDescription,
    quote: socialShareStore.quote,
    hashtags: socialShareStore.hashtags,
    twitterUser: socialShareStore.twitterUser,
    media: socialShareStore.media,
  },

  {
    network: 'copy', // No network for copy button
    name: 'Copy Link',
    iconPrefix: 'fas',
    iconName: 'copy',
    color: '#4a4a4a', // Gray color for copy button
    url: socialShareStore.url,
    copy: true,
  },

]

function shareBroadcast(url) {
  // console.log('shareBroadcast triggered with URL:', url)
  navigator.clipboard.writeText(url).then(() => {
    notificationStore.setGeneralServiceNotification('Success!', 'The share link has been copied to your clipboard.')
  }, () => {
    notificationStore.setGeneralServiceNotification('Oops!', 'Failed to copy the link. Please try again.')
  })
}

function handleShare(network, share) {
  // console.log('handleShare triggered for network:', network)
  if (network.copy) {
    shareBroadcast(network.url)
  } else {
    share()
  }
}

onBeforeUnmount(() => {
  socialShareStore.reset()
})

</script>
<style scoped>
.social-network {
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  padding: 0.5rem;
  border-radius: 0.25rem;
  cursor: pointer;
  transition: transform 0.3s ease-in-out;
}

.social-network:hover {
  transform: scale(1.1);
  filter: brightness(1.2);
}

.bg-darkgray {
  background-color: #1e1e1e;
}

.shadow-lg {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 10px 15px rgba(0, 0, 0, 0.1), 0 20px 25px rgba(0, 0, 0, 0.1);
}
</style>