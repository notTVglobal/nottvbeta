<template>
  <div>
    <div class="mb-6 w-fit">
      <label class="block mb-2 uppercase text-sm font-bold dark:text-gray-200" for="creative_commons">
        Creative Commons / Copyright <span :class="errors.description ? 'text-red-500' : 'text-indigo-500'">* REQUIRED</span>
      </label>

      <div v-if="loading" class="text-gray-500">Loading...</div>

      <select v-else
              class="border border-gray-400 text-gray-800 py-2 pl-2 pr-8 w-fit rounded-lg block mb-2 uppercase font-bold text-xs"
              v-model="selectedCreativeCommons"
              @change="handleCreativeCommonsChange">
        <option v-for="cc in showEpisodeStore.creativeCommons" :key="cc.id" :value="cc.id">{{ cc.name }}</option>
      </select>

      <div>{{ selectedCreativeCommonsDescription }}</div>

      <div v-if="errors.creative_commons_id" v-text="errors.creative_commons_id" class="text-xs text-red-600 mt-1"></div>
    </div>

    <div v-show="selectedCreativeCommons" class="mb-6 w-64">
      <div v-if="selectedCreativeCommons === 8">
        <input class="hidden border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
               type="hidden"
               v-model="selectedCopyrightYear"
               value="null">
      </div>
      <div v-else>
        <label class="block mb-2 uppercase text-sm font-bold dark:text-gray-200" for="copyrightYear">
          Copyright Year
        </label>
        <input class="border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
               type="number"
               minlength="4"
               maxlength="4"
               v-model="selectedCopyrightYear">
      </div>

      <div v-if="errors.copyright_year" v-text="errors.copyright_year" class="text-xs text-red-600 mt-1"></div>
    </div>
  </div>
</template>

<script setup>
import { useShowEpisodeStore } from "@/Stores/ShowEpisodeStore"
import { ref, computed, watch } from 'vue'

const showEpisodeStore = useShowEpisodeStore()
const loading = ref(true)

const props = defineProps({
  errors: Object,
})

const selectedCreativeCommons = ref(showEpisodeStore.episode?.creative_commons?.id || 7)
const selectedCopyrightYear = ref(showEpisodeStore.episode?.copyrightYear || new Date().getFullYear())

watch(() => showEpisodeStore.creativeCommons, (newVal) => {
  loading.value = !newVal.length
})

watch(() => props.episode?.creative_commons?.id, (newVal) => {
  selectedCreativeCommons.value = newVal !== null ? newVal : 7
  handleCreativeCommonsChange()
})

const handleCreativeCommonsChange = () => {
  if (selectedCreativeCommons.value === 8) {
    selectedCopyrightYear.value = null
  } else if (selectedCopyrightYear.value === null) {
    selectedCopyrightYear.value = new Date().getFullYear()
  }
}

const selectedCreativeCommonsDescription = computed(() => {
  if (Array.isArray(showEpisodeStore.creativeCommons)) {
    const selectedCC = showEpisodeStore.creativeCommons.find((cc) => cc.id === selectedCreativeCommons.value)
    return selectedCC ? selectedCC.description : ''
  }
  return ''
})

</script>
