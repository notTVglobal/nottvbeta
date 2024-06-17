<template>
  <dialog id="copyDestinationsModal" class="modal">
    <div class="modal-box bg-white dark:bg-gray-800 dark:text-white">
      <form method="dialog">
        <button @click="closeModal" :disabled="loading" class="btn btn-sm btn-circle btn-ghost hover:text-blue-300 absolute right-2 top-2">
          ✕
        </button>
      </form>
      <h3 class="font-bold text-lg">Copy Destinations</h3>
      <div v-if="filteredDestinations.length">
        <div class="flex flex-wrap justify-start items-center my-2">
          <button @click="selectAllDestinations" :disabled="loading || allSelected" class="btn btn-sm btn-secondary text-white">
            <font-awesome-icon icon="check-square" class="mr-2" />
            Select All
          </button>
          <button @click="deselectAllDestinations" :disabled="loading || noneSelected" class="btn btn-sm btn-secondary text-white ml-2">
            <font-awesome-icon icon="minus-square" class="mr-2" />
            Deselect All
          </button>
        </div>
        <div v-for="destination in filteredDestinations" :key="destination.id" class="my-3 cursor-pointer" @click="toggleSelection(destination.id)">
          <div class="flex items-center">
            <input
                type="checkbox"
                :value="destination.id"
                v-model="selectedDestinations"
                :disabled="loading"
                class="checkbox mr-2"
                @click.stop
            />
            <div class="flex flex-col">
              <span class="font-bold text-blue-600">{{ destination.show_name }}</span>
              <span>{{ destination.comment }} - ({{ destination.rtmp_url }}{{ destination.rtmp_key }})</span>
            </div>
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button
              @click="copySelectedDestinations"
              class="btn btn-primary text-white mr-2"
              :disabled="loading || selectedDestinations.length === 0"
          >
            <font-awesome-icon icon="copy" class="mr-2" />
            Copy Selected
            <span v-if="loading" class="loading loading-spinner loading-md ml-2"></span>
          </button>
          <button @click="closeModal" :disabled="loading" class="btn btn-ghost">
            Cancel
          </button>
        </div>
      </div>
      <div v-else>
        <p>No destinations to copy.</p>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useGoLiveStore } from '@/Stores/GoLiveStore';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const goLiveStore = useGoLiveStore();

const otherShowDestinations = computed(() => goLiveStore.otherShowDestinations);
const selectedDestinations = ref([]);
const loading = ref(false);

const filteredDestinations = computed(() => {
  const currentDestinations = goLiveStore.destinations;
  return otherShowDestinations.value.filter(destination =>
      !currentDestinations.some(d => d.rtmp_url === destination.rtmp_url && d.rtmp_key === destination.rtmp_key)
  );
});

const allSelected = computed(() => {
  return filteredDestinations.value.length > 0 && selectedDestinations.value.length === filteredDestinations.value.length;
});

const noneSelected = computed(() => {
  return selectedDestinations.value.length === 0;
});

const selectAllDestinations = () => {
  selectedDestinations.value = filteredDestinations.value.map(destination => destination.id);
};

const deselectAllDestinations = () => {
  selectedDestinations.value = [];
};

const toggleSelection = (id) => {
  if (selectedDestinations.value.includes(id)) {
    selectedDestinations.value = selectedDestinations.value.filter(destId => destId !== id);
  } else {
    selectedDestinations.value.push(id);
  }
};

const copySelectedDestinations = async () => {
  loading.value = true;
  const success = await goLiveStore.copyDestinations(selectedDestinations.value);
  loading.value = false;
  if (success) {
    closeModal();
  }
};

const closeModal = () => {
  document.getElementById('copyDestinationsModal').close();
};
</script>

<style scoped>
.btn-ghost:hover {
  color: #1d4ed8; /* Tailwind blue-700 */
}
.cursor-pointer {
  cursor: pointer;
}
</style>
