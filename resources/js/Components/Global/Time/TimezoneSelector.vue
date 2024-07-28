<template>
  <div>
    <div class="w-full max-w-3xl">
      <p class="mb-4">All Timezones</p>
      <p v-if="loadingTimezones" class="loading loading-dots"/>
      <p v-if="loadingError" class="text-red-600">{{ loadingError }}</p>
      <div @focusin="handleFocus" @focusout="handleBlur">
        <vSelect
            v-model="selectedTimezone"
            :options="timezones"
            :reduce="option => option.value"
            :group-select="true"
            :group-label="group => group"
            :group-values="group => groupTimezones(timezones.value)[group]"
            :group-selectable="false"
            :placeholder="timezonePlaceholder"
            searchable
            class="style-chooser"
        />
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import 'vue-select/dist/vue-select.css'

const selectedTimezone = ref(null)
const timezones = ref([])
const timezonePlaceholder = ref('Select a timezone...')
const loadingTimezones = ref(false)
const loadingError = ref('')

const emits = defineEmits(['update-timezone'])

const loadTimezones = async () => {
  if (timezones.value.length === 0) {
    try {
      loadingTimezones.value = true
      const response = await axios.get('/api/get-timezones');
      timezones.value = response.data.map(timezone => ({
        value: timezone,
        label: timezone,
      }));
      console.log(timezones.value.slice(0, 5)); // Log the first 5 timezones
      loadingTimezones.value = false
    } catch (error) {
      loadingTimezones.value = false
      loadingError.value = 'Error fetching timezones'
      console.error('Error fetching timezones:', error);
    }
  }
};

onMounted(() => {
  loadTimezones();
});

const groupTimezones = (options) => {
  return options.reduce((groups, item) => {
    const group = item.value.split('/')[0]
    if (!groups[group]) {
      groups[group] = []
    }
    groups[group].push(item)
    return groups
  }, {})
}

watch(selectedTimezone, (newTimezone) => {
  emits('update-timezone', newTimezone)
})

function handleFocus() {
  timezonePlaceholder.value = 'Type to Search...'
}

function handleBlur() {
  timezonePlaceholder.value = 'Select a timezone...'
}
</script>

<style scoped>
/* Override styles for v-select */
.style-chooser {
  width: 100%;
  padding: 0.5rem;
}

/* Override the max-height of the dropdown using the CSS variable */
.style-chooser {
  --vs-dropdown-max-height: 150px; /* Adjust this value based on your modal's height */
}

/* Override styles for v-select */
.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {
  background: #dfe5fb;
  border: none;
  color: #394066;
  text-transform: lowercase;
  font-variant: small-caps;
  width: 500px;
}

.style-chooser .vs__clear,
.style-chooser .vs__open-indicator {
  fill: #394066;
}
</style>