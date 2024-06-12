<template>
  <div class="modal" :class="{ 'modal-open': showModal }">
    <div class="modal-box">
      <form @submit.prevent="handleFetchContent" v-if="!contentFetched">
        <h3 class="font-bold text-lg mb-2">Add Content to Fill Gap</h3>
        <div class="form-control mb-2">
          <label class="label">
            <span class="label-text">Select Content Type</span>
          </label>
          <select v-model="contentType" class="select select-bordered">
            <option value="ShowEpisode">Show Episode</option>
            <option value="Movie">Movie</option>
            <option value="NewsStory">News Story</option>
            <option value="OtherContent">Other Content</option>
          </select>
        </div>
        <div class="form-control mb-4">
          <label class="label">
            <span class="label-text">Gap Duration: {{ gapDuration }} minutes</span>
          </label>
        </div>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          <span v-if="playlistStore.loading" class="loading loading-spinner" />
          <span v-else>Retrieve Content</span>
        </button>
        <button type="button" @click.prevent="closeModal" class="btn btn-secondary ml-2">Cancel</button>
        <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
      </form>

      <div v-else class="mt-6">
        <div class="flex justify-between items-center mb-4">
          <input v-model="searchQuery" @input="handleSearch" type="text" class="input input-bordered w-3/4" placeholder="Search content...">
          <button @click.prevent="resetContent" class="btn btn-secondary">Back</button>
        </div>
        <h4 class="font-semibold text-lg mb-4 text-gray-800">Available Content</h4>
        <ul v-if="contentItems.length" class="space-y-4">
          <li v-for="(item, index) in contentItems" :key="index" class="flex items-center justify-between p-4 border rounded-lg bg-gray-50">
            <div class="flex items-center gap-4">
              <SingleImage :image="item.content.image" :alt="item.content.name" :class="`w-16 h-16 object-cover rounded`"/>
              <div>
                <div class="text-gray-800 font-medium">{{ item.content.name }}</div>
                <div class="text-gray-600">{{ item.duration_minutes }} minutes</div>
              </div>
            </div>
            <button @click.prevent="addContent(item)" class="btn btn-sm btn-success">Add</button>
          </li>
        </ul>
        <div v-else>
          <p class="text-gray-600">No content available.</p>
        </div>
        <div class="flex justify-between mt-6">
          <button @click.prevent="prevPage" :disabled="currentPage <= 1" class="btn btn-outline">Previous</button>
          <button @click.prevent="nextPage" :disabled="currentPage >= totalPages" class="btn btn-outline">Next</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useChannelPlaylistStore } from '@/Stores/ChannelPlaylistStore';
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const props = defineProps({
  showModal: Boolean,
  gapDuration: Number,
  startDateTime: String,
});

const emit = defineEmits(['addContentToGap']);

const contentType = ref('ShowEpisode');
const playlistStore = useChannelPlaylistStore();

const contentItems = computed(() => playlistStore.contentItems);
const contentFetched = computed(() => playlistStore.contentFetched);
const error = computed(() => playlistStore.error);
const loading = computed(() => playlistStore.loading);
const currentPage = computed(() => playlistStore.currentPage);
const totalPages = computed(() => playlistStore.totalPages);
const searchQuery = computed(() => playlistStore.searchQuery);

const handleFetchContent = () => {
  playlistStore.fetchContent(contentType.value, props.gapDuration, props.startDateTime, currentPage.value, searchQuery.value);
};

const handleSearch = (event) => {
  playlistStore.setSearchQuery(event.target.value);
  handleFetchContent();
};

const addContent = (content) => {
  playlistStore.addContentToSchedule(content);
  closeModal();
};

const closeModal = () => {
  playlistStore.closeAddContentModal()
  resetContent();
};

const resetContent = () => {
  playlistStore.resetContent();
};

const prevPage = () => {
  if (currentPage.value > 1) {
    playlistStore.setPage(currentPage.value - 1);
    handleFetchContent();
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    playlistStore.setPage(currentPage.value + 1);
    handleFetchContent();
  }
};
</script>
