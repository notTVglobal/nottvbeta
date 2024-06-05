<template>
  <main class="pb-24 mx-auto px-4 border-b border-gray-800">
    <div class="flex bg-gray-200 my-10 mx-auto p-5 w-[calc(100%-96)] rounded justify-center text-gray-900">
      <div class="flex flex-col xl:flex-row my-6">
        <div class="w-full flex flex-col gap-4 items-center justify-center xl:justify-start">
          <div>
            <SingleImage v-if="$page.props.newsPerson.image"
                         :image="$page.props.newsPerson.image"
                         :alt="`Profile Image`"
                         :class="`h-64 w-64 object-cover shadow-lg rounded-lg`"/>
            <img
                alt="News Reporter Profile Picture"
                v-if="$page.props.newsPerson.profile_photo_path && !$page.props.newsPerson.image && !$page.props.newsPerson.profile_photo_url"
                :src="`/storage/${$page.props.newsPerson.profile_photo_path}`"
                class="h-64 w-64 object-cover shadow-lg rounded-lg"
            >
            <img
                alt="News Reporter Profile Picture"
                v-if="$page.props.newsPerson.profile_photo_url && !$page.props.newsPerson.image && !$page.props.newsPerson.profile_photo_path"
                :src="$page.props.newsPerson.profile_photo_url"
                class="h-64 w-64 object-cover shadow-lg rounded-lg"
            >
          </div>
          <div>
            <NewsTipButton :newsPersonId="$page.props.newsPerson.id" :newsPersonName="$page.props.newsPerson.name"/>
          </div>
        </div>

        <div class="w-full mt-4 xl:mt-0 xl:mx-4">
          <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $page.props.newsPerson.name }}
          </h1>
          <div class="space-y-6 mt-4">
            <!-- Sections for Biography, Past Stories, Contact -->
            <section v-if="$page.props.newsPerson.biography" class="bg-white p-4 rounded-lg shadow">
              <h2 class="text-xl font-semibold mb-2">Biography</h2>
              <p class="text-sm italic text-gray-700">
                <span v-html="truncatedBiography"/>
                <span v-if="!showFullBiography && truncatedBiography.length < $page.props.newsPerson.biography.length">
                  ...<button @click="showFullBiography = true" class="text-blue-500">Read more</button>
                </span>
                <span v-if="showFullBiography" v-html="formatText($page.props.newsPerson.biography)"></span>
              </p>
            </section>
            <section v-if="$page.props.newsStories.length > 0" class="bg-white p-4 rounded-lg shadow">
              <h2 class="text-xl font-semibold mb-2">Past Stories</h2>
              <div v-for="(story, index) in displayedNewsStories" :key="story.id"
                   @click.prevent="appSettingStore.btnRedirect(`/news/story/${story.slug}`)"
                   class="text-sm italic text-gray-700 cursor-pointer hover:bg-gray-300 p-2 rounded">
                <div class="flex gap-2 items-start">
                  <SingleImage v-if="story.image" :image="story.image" :alt="story.title"
                               :class="`w-12 h-12 min-w-12 min-h-12 object-cover shadow-lg rounded-lg`"/>
                  <div v-else
                       class="w-12 h-12 min-w-12 min-h-12 bg-gray-300 shadow-lg rounded-lg flex items-center justify-center">
                    <i class="fas fa-image text-gray-500"></i>
                  </div>
                  <div>
                    <span>{{ story.title }}</span>
                    <span><ConvertDateTimeToTimeAgo :dateTime="story.published_at"
                                                    :timezone="userStore.timezone"/></span>
                  </div>
                </div>
              </div>
              <div class="flex justify-center mt-4">
                <button v-if="showSeeMore" @click="showAllStories = true"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-300">
                  See more past stories
                </button>
              </div>
            </section>
            <section v-if="$page.props.newsPerson.contact_info" class="bg-white p-4 rounded-lg shadow">
              <h2 class="text-xl font-semibold mb-2">Contact</h2>
              <p class="text-sm italic text-gray-700" v-html="formatText($page.props.newsPerson.contact_info)"></p>
            </section>
            <section v-if="Object.keys(socialMediaLinks).length" class="bg-white p-4 rounded-lg shadow">
              <h2 class="text-xl font-semibold mb-2">Social Media</h2>
              <div class="flex flex-wrap gap-4">
                <a v-for="(url, platform) in socialMediaLinks"
                   :key="platform"
                   :href="url"
                   target="_blank"
                   class="text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-300"
                   :class="buttonClasses(platform)"
                   :title="platform">
                  <font-awesome-icon :icon="['fab', socialMediaIcons[platform]]" class="text-2xl"
                                     v-if="platform !== 'substack'"/>
                  <div v-if="platform === 'substack'" class="flex items-center space-x-2">
                    <font-awesome-icon :icon="['fas', socialMediaIcons[platform]]" class="text-2xl"/>
                    <span class="text-lg">Substack</span>
                  </div>
                </a>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import NewsTipButton from '@/Components/Global/News/NewsTipButton.vue'

// Import specific icons from Font Awesome
import { faLink } from '@fortawesome/free-solid-svg-icons'
import {
  faFacebookF,
  faTwitter,
  faInstagram,
  faLinkedin,
  faSnapchat,
  faDiscord,
} from '@fortawesome/free-brands-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'

// Add icons to the library
library.add(faFacebookF, faTwitter, faInstagram, faLinkedin, faSnapchat, faDiscord, faLink)

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

const page = usePage()

const showFullBiography = ref(false)
const showAllStories = ref(false)

const formatText = (text) => {
  if (!text) return ''
  return text.replace(/\n/g, '<br>')
}

const truncatedBiography = computed(() => {
  const biography = page.props.newsPerson.biography || ''
  const truncated = biography.length > 300 && !showFullBiography.value ? biography.slice(0, 300) : biography
  return formatText(truncated)
})

const displayedNewsStories = computed(() => {
  return showAllStories.value ? page.props.newsStories : page.props.newsStories.slice(0, 3)
})

const showSeeMore = computed(() => {
  return page.props.newsStories.length > 3 && !showAllStories.value
})

const socialMediaIcons = {
  facebook: 'facebook-f',
  twitter: 'x-twitter',
  instagram: 'instagram',
  linkedin: 'linkedin',
  snapchat: 'snapchat',
  discord: 'discord',
  substack: 'link', // Using a generic link icon for Substack
}

const socialMediaLinks = computed(() => {
  const socialMedia = page.props.newsPerson.social_media || {}
  return Object.entries(socialMedia).reduce((acc, [platform, url]) => {
    if (url && url.trim() !== '' && socialMediaIcons[platform]) {
      acc[platform] = url
    }
    return acc
  }, {})
})


const buttonClasses = (platform) => {
  return {
    'bg-blue-500': platform === 'facebook',
    'bg-black': platform === 'twitter',
    'bg-pink-500': platform === 'instagram',
    'bg-blue-600': platform === 'linkedin',
    'bg-yellow-500': platform === 'snapchat',
    'bg-indigo-500': platform === 'discord',
    'bg-gray-500': platform === 'substack',
  }
}
</script>
