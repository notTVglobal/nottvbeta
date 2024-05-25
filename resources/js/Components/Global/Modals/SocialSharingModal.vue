<template>
  <div v-if="socialShareStore.socialSharingModal" @click.self="socialShareStore.hideSocialSharing"
       class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-999">
    <div class="relative w-full max-w-md lg:max-w-2xl lg:w-1/3">
      <!-- Outer Border -->
      <div class="absolute -inset-2 rounded-lg opacity-75 blur-sm bg-gradient-to-br from-blue-600 to-blue-400"></div>
      <!-- Inner Border -->
      <div class="absolute -inset-1 rounded-lg opacity-100 blur bg-gradient-to-br from-blue-500 to-blue-300"></div>
      <!-- Modal Content -->
      <div class="relative bg-darkgray px-6 pt-6 pb-16 rounded-lg">
        <button @click="socialShareStore.hideSocialSharing" class="absolute bottom-5 right-5 bg-red-500 text-white py-1 px-3 rounded">
          Close
        </button>
        <div class="flex flex-col">
          <div class="flex flex-col items-center space-y-2 lg:flex-row lg:space-x-4 lg:space-y-0 pb-4">
            <img src="/storage/images/Ping.png" alt="notTV Ping" class="max-h-10"/>
            <div class="text-center lg:text-left">
              <h3 class="text-blue-400">Let's Get Social</h3>
              <h4 class="text-blue-300">Help us spread the word and build our community!</h4>
            </div>
          </div>
          <div class="flex flex-col items-center space-y-4 mb-6 p-3 rounded-lg bg-darkgray-light lg:flex-row lg:items-start lg:space-x-8 lg:space-y-0">
            <SingleImage :image="socialShareStore.image" :alt="`image`" class="max-h-10 max-w-20 object-contain"/>
            <div class="flex flex-col py-2 text-center lg:text-left">
              <h4 class="font-thin">{{ socialShareStore.title }}</h4>
              <h5>{{ shortDescription }}</h5>
              <h5 class="text-xs tracking-wider text-gray-200">{{ socialShareStore.hashtags }}</h5>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap gap-2 justify-center lg:justify-start lg:gap-4">
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
              <font-awesome-icon :icon="[network.iconPrefix, network.iconName]" />
              <span class="ml-2">{{ network.name }}</span>
            </div>
          </ShareNetwork>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { computed, ref, watch } from 'vue'
import { useSocialShareStore } from '@/Stores/SocialShareStore'
import { ShareNetwork, useShareLink } from 'vue3-social-sharing'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const socialShareStore = useSocialShareStore()
const { shareLink } = useShareLink()

const showModal = ref(false)

// const props = defineProps({
//   showButton: {
//     type: Boolean,
//     default: true,
//   },
//   title: {
//     type: String,
//     default: 'notTV',
//   },
//   url: {
//     type: String,
//     // default: 'https://not.tv/teams/bc-townhalls-2024'
//     default: 'https://not.tv',
//   },
//   description: {
//     type: String,
//     default: 'Share this!',
//   },
//   quote: {
//     type: String,
//     default: 'Community Television Re-invented',
//   },
//   hashtags: {
//     type: String,
//     default: 'notTV, mediaForABetterWorld',
//   },
//   twitterUser: {
//     type: String,
//     default: 'notTV',
//   },
//   media: {
//     type: String,
//     // default: 'https://not.tv/storage/images/logo_black_311.png'
//     default: 'https://not.tv/storage/images/Ping.png',
//   },
//   image: {
//     type: Object,
//     default: {
//       placeholder_url: 'https://not.tv/storage/images/logo_white_512.png'
//     }
//   }
// })

const shortDescription = computed(() => {
  const description = socialShareStore.description;
  const maxLength = 300;
  return description.length > maxLength ? `${description.substring(0, maxLength)}...` : description;
});

const networks = [
  {
    network: 'email',
    name: 'Email',
    iconPrefix: 'fas',
    iconName: 'envelope',
    color: '#333333',
    title: socialShareStore.title, // shows up as subject line
    url: socialShareStore.url, // appears in the body
    description: socialShareStore.description, // appears in the body
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
    description: socialShareStore.description,
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
    description: socialShareStore.description,
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
    iconName: 'twitter',
    color: '#1da1f2',
    title: socialShareStore.title,
    url: socialShareStore.url,
    description: socialShareStore.description,
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
    description: socialShareStore.description,
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
    description: socialShareStore.description,
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

// function shareBroadcast() {
//   console.log('click')
//   navigator.clipboard.writeText(props.url).then(() => {
//     notificationStore.setGeneralServiceNotification('Success!', 'The share link has been copied to your clipboard.');
//   }, () => {
//     notificationStore.setGeneralServiceNotification('Oops!', 'Failed to copy the link. Please try again.');
//   });
// }


function shareBroadcast(url) {
  console.log('shareBroadcast triggered with URL:', url)
  navigator.clipboard.writeText(url).then(() => {
    notificationStore.setGeneralServiceNotification('Success!', 'The share link has been copied to your clipboard.')
  }, () => {
    notificationStore.setGeneralServiceNotification('Oops!', 'Failed to copy the link. Please try again.')
  })
}

function handleShare(network, share) {
  console.log('handleShare triggered for network:', network)
  if (network.copy) {
    shareBroadcast(network.url)
  } else {
    share()
  }
}

// const networks = [
//   { network: 'email', name: 'Email', iconPrefix: 'fas', iconName: 'envelope', color: '#333333' },
//   { network: 'facebook', name: 'Facebook', iconPrefix: 'fab', iconName: 'facebook-f', color: '#1877f2' },
//   { network: 'messenger', name: 'Messenger', iconPrefix: 'fab', iconName: 'facebook-messenger', color: '#0084ff' },
//   { network: 'pocket', name: 'Pocket', iconPrefix: 'fab', iconName: 'get-pocket', color: '#ef4056' },
//   { network: 'sms', name: 'SMS', iconPrefix: 'fas', iconName: 'comment-dots', color: '#333333' },
//   { network: 'telegram', name: 'Telegram', iconPrefix: 'fab', iconName: 'telegram-plane', color: '#0088cc' },
//   { network: 'tumblr', name: 'Tumblr', iconPrefix: 'fab', iconName: 'tumblr', color: '#35465c' },
//   { network: 'twitter', name: 'Twitter', iconPrefix: 'fab', iconName: 'twitter', color: '#1da1f2' },
//   { network: 'whatsapp', name: 'Whatsapp', iconPrefix: 'fab', iconName: 'whatsapp', color: '#25d366' },
//   { network: 'wordpress', name: 'Wordpress', iconPrefix: 'fab', iconName: 'wordpress', color: '#21759b' },
// { network: 'buffer', name: 'Buffer', iconPrefix: 'fab', iconName: 'buffer', color: '#323b43' },
// { network: 'evernote', name: 'Evernote', iconPrefix: 'fab', iconName: 'evernote', color: '#2dbe60' },
// { network: 'flipboard', name: 'Flipboard', iconPrefix: 'fab', iconName: 'flipboard', color: '#e12828' },
// { network: 'hackernews', name: 'HackerNews', iconPrefix: 'fab', iconName: 'hacker-news', color: '#ff4000' },
// { network: 'linkedin', name: 'LinkedIn', iconPrefix: 'fab', iconName: 'linkedin', color: '#007bb5' },
// { network: 'pinterest', name: 'Pinterest', iconPrefix: 'fab', iconName: 'pinterest', color: '#bd081c' },
// { network: 'quora', name: 'Quora', iconPrefix: 'fab', iconName: 'quora', color: '#a82400' },
// { network: 'reddit', name: 'Reddit', iconPrefix: 'fab', iconName: 'reddit-alien', color: '#ff4500' },
// { network: 'skype', name: 'Skype', iconPrefix: 'fab', iconName: 'skype', color: '#00aff0' },
// { network: 'stumbleupon', name: 'StumbleUpon', iconPrefix: 'fab', iconName: 'stumbleupon', color: '#eb4924' },
// { network: 'viber', name: 'Viber', iconPrefix: 'fab', iconName: 'viber', color: '#59267c' },
// { network: 'vk', name: 'Vk', iconPrefix: 'fab', iconName: 'vk', color: '#4a76a8' },
// { network: 'yammer', name: 'Yammer', iconPrefix: 'fab', iconName: 'yammer', color: '#0072c6' },
// ];

// const sharingInfo = {
//   title: 'My perfect test title',
//   url: 'https://example.com',
//   description: 'My perfect description',
//   quote: 'My perfect quote',
//   hashtags: 'tag1,tag2',
//   twitterUser: 'POTUS',
//   media: 'https://images.unsplash.com/photo-1708283508253-337621680a84?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
// };

// const share = (network) => {
//   shareLink({
//     network,
//     url: props.url,
//     title: props.title,
//     description: props.description,
//     quote: props.quote,
//     hashtags: props.hashtags,
//     twitterUser: props.twitterUser,
//     media: props.media,
//   })
// }

// Watch for changes in showButton and update showModal accordingly
// watch(
//     () => props.showButton,
//     (newVal) => {
//       if (!newVal) {
//         showModal.value = true;
//       }
//     },
//     { immediate: true }
// );
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