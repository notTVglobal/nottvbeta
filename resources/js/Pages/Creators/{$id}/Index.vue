<template>
  <Head :title="creator.name"/>

  <div class="flex flex-col items-center gap-y-3 p-5 min-h-screen bg-white text-black pb-24">
    <div class="flex flex-col w-full max-w-7xl mx-auto">
      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-end items-center w-full mb-6">
        <BackButton />
      </div>

      <div class="flex flex-wrap items-center justify-center xl:justify-start gap-8 mb-6">
        <img v-if="creator.profile_photo_path"
             :src="'/storage/' + creator.profile_photo_path"
             class="rounded-full h-64 w-64 object-cover">
        <img v-else
             :src="creator.profile_photo_url"
             class="rounded-full h-64 w-64 object-cover bg-gray-300">
        <span class="text-5xl">{{ creator.name }}</span>
      </div>

      <!-- Teams Section -->
      <div class="w-full mb-6">
        <h2 class="text-xl pb-3">Teams</h2>
        <swiper
            :slides-per-view="3"
            space-between="15"
            navigation
            class="mySwiper"
        >
          <swiper-slide v-for="team in teams" :key="team.id" class="p-4">
            <div @click="toggleTeam(team.id)" class="cursor-pointer">
              <SingleImage :image="team.image" :alt="`Team Logo`" class="rounded-full h-20 w-20 object-cover mb-2"/>
              <h3 class="text-lg font-semibold text-center">{{ team.name }}</h3>
            </div>
            <div v-if="expandedTeam === team.id" class="mt-4">
              <div class="shows">
                <p>Shows:</p>
                <div class="flex flex-col gap-2">
                  <div v-for="show in team.shows" @click.prevent="appSettingStore.btnRedirect(`/shows/${show.slug}`)" :key="show.id" class="flex gap-2 items-center group hover:bg-gray-100 hover:cursor-pointer p-2 rounded-md transition duration-300 ease-in-out">
                    <SingleImage :image="show.image" :alt="`Show Image`" class="rounded-full h-10 w-10 object-cover group-hover:shadow-lg transition duration-300 ease-in-out"/>
                    <span class="group-hover:text-blue-500">{{ show.name }}</span>
                  </div>
                </div>
              </div>
              <Link :href="`/teams/${team.slug}`" class="text-blue-500 hover:text-blue-400 hover:underline hover:cursor-pointer text-sm mt-2 block text-center">Go to Team Page</Link>
            </div>
          </swiper-slide>
        </swiper>
      </div>

      <!-- News Stories Section -->
      <div v-if="newsStories && newsStories.length" class="w-full mb-6">
        <h2 class="text-xl pb-3">News Stories</h2>
        <div>
          <ul class="list-disc ml-8">
            <li v-for="story in newsStories" :key="story.id">
              <Link :href="`/news/story/${story.slug}`" class="text-blue-500 hover:text-blue-400 hover:cursor-pointer hover:underline">{{ story.title }}</Link>
              <p>{{ story.summary }}</p>
              <p class="text-sm text-gray-500">{{ formatDate(story.published_at) }}</p>
            </li>
          </ul>
        </div>
      </div>

      <!-- Fundraising Goal Section -->
      <div class="w-full bg-gray-100 p-6 rounded-lg shadow-md">
        <h2 class="text-xl pb-3">Fundraising Goal</h2>
        <p class="text-lg mb-2">Help {{ creator.name }} reach their goal!</p>
        <div class="w-full bg-gray-300 rounded-full h-6 mb-4 overflow-hidden">
          <div class="bg-blue-500 h-6 rounded-full" :style="{ width: fundraisingProgress + '%' }"></div>
        </div>
        <p class="text-md">{{ fundraisingAmountRaised }} raised of {{ fundraisingGoal }}</p>
        <button class="mt-4 bg-blue-500 text-white py-2 px-4 rounded-full">Donate Now</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import { usePageSetup } from '@/Utilities/PageSetup';
import { useAppSettingStore } from '@/Stores/AppSettingStore';
import Message from '@/Components/Global/Modals/Messages';
import BackButton from '@/Components/Global/Buttons/BackButton.vue';
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue';

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';

// Import Swiper core and required modules
import { Navigation } from 'swiper/modules';

// Install Swiper modules
import SwiperCore from 'swiper';
SwiperCore.use([Navigation]);

const appSettingStore = useAppSettingStore();

const props = defineProps({
  creator: Object,
  teams: Array,
  newsStories: Array,
});

const expandedTeam = ref(null);

const toggleTeam = (id) => {
  expandedTeam.value = expandedTeam.value === id ? null : id;
};

usePageSetup('creator.' + props.creator.id);

// Sample data for fundraising
const fundraisingGoal = 10000; // Example goal amount
const fundraisingAmountRaised = 5000; // Example raised amount
const fundraisingProgress = (fundraisingAmountRaised / fundraisingGoal) * 100;

const onSwiper = (swiper) => {
  console.log(swiper);
};

const onSlideChange = () => {
  console.log('slide change');
};

const modules = [Navigation];
</script>

<style scoped>
/* Tailwind CSS styles */
.mySwiper {
  padding: 10px 0;
}

.swiper-button-prev,
.swiper-button-next {
  color: #000; /* Adjust color if necessary */
}

.swiper-slide {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.swiper-slide-active {
  transform: scale(1.05);
}
</style>
