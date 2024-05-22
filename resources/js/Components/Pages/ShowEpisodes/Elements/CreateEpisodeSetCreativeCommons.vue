<template>
  <div>
    <div class="mb-6 w-fit">
      <label class="block mb-2 uppercase text-sm font-bold dark:text-gray-200"
             for="creative_commons"
      >
        Creative Commons / Copyright <span :class="errors.description ? 'text-red-500' : 'text-indigo-500'">* REQUIRED</span>
      </label>

      <select
          class="border border-gray-400 text-gray-800 py-2 pl-2 pr-8 w-fit rounded-lg block mb-2 uppercase font-bold text-xs"
          v-model="selectedCreativeCommons" @change="handleCreativeCommonsChange">
        <option v-for="cc in showEpisodeStore.creativeCommons" :key="cc.id" :value="cc.id">{{ cc.name }}</option>
      </select>

      <div class="">{{ selectedCreativeCommonsDescription }}</div>

      <div v-if="errors.creative_commons_id" v-text="errors.creative_commons_id"
           class="text-xs text-red-600 mt-1"></div>

    </div>

    <div v-if="selectedCreativeCommons" class="mb-6 w-64">

      <div v-if="selectedCreativeCommons === 8">
        <input class="hidden border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
               type="hidden"
               v-model="selectedCopyrightYear"
               value="null">
      </div>
      <div v-else>
        <label class="block mb-2 uppercase text-sm font-bold dark:text-gray-200"
               for="copyrightYear"
        >
          Copyright Year
        </label>
        <input class="border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
               type="number"
               minlength="4"
               maxlength="4"
               v-model="selectedCopyrightYear">
      </div>

      <div v-if="errors.copyright_year" v-text="errors.copyright_year"
           class="text-xs text-red-600 mt-1"></div>
    </div>
  </div>
</template>
<script setup>
import { useShowEpisodeStore } from "@/Stores/ShowEpisodeStore"
import { computed, watch } from 'vue'

const showEpisodeStore = useShowEpisodeStore()

const props = defineProps({
  errors: Object,
})

// const selectedCreativeCommons = ref(props.episode?.creative_commons?.id);
// Initialize selectedCreativeCommons with a default value of 7 if the id is null
const selectedCreativeCommons = (showEpisodeStore.episode?.creative_commons?.id || 7)
let selectedCopyrightYear = (showEpisodeStore.episode?.copyrightYear)
const currentYear = new Date().getFullYear()

watch(() => props.episode?.creative_commons?.id, (newVal) => {
  // selectedCreativeCommons.value = newVal;
  // Set selectedCreativeCommons to newVal if it's not null, otherwise default to 7
  selectedCreativeCommons.value = newVal !== null ? newVal : 7
  handleCreativeCommonsChange()
})

const handleCreativeCommonsChange = () => {
  if (selectedCreativeCommons.value === 8) {
    selectedCopyrightYear.value = null
  } else if (selectedCopyrightYear.value === null) {
    // Pre-populate with current year only if copyrightYear is null
    selectedCopyrightYear.value = currentYear
  }
}

// const selectedCreativeCommonsDescription = computed(() => {
//   const selectedCC = showEpisodeStore.creativeCommons.find((cc) => cc.id === selectedCreativeCommons.value)
//   return selectedCC ? selectedCC.description : ''
// })

</script>