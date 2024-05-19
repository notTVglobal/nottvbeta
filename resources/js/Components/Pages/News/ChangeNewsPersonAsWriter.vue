<template>
  <div class="flex flex-col py-4 px-6">
    <div class="font-semibold text-xs uppercase mb-2">Select News Person as author</div>
    <select v-model="selectedPersonId" class="py-2">
      <option value="" disabled>Select News Person</option>
      <option v-for="person in options" :key="person.id" :value="person.id">
        {{ person.name }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useNewsStore } from '@/Stores/NewsStore'

const newsStore = useNewsStore()

const options = ref([])

// Store only the id in the select element
const selectedPersonId = ref(newsStore.newsPerson?.id || [])

// Computed property for the selected news person
const selectedNewsPerson = computed(() => {
  return options.value.find(option => option.id === selectedPersonId.value) || null
})

// Watcher to update newsStore when the selection changes
watch(selectedPersonId, (newValue) => {
  const selectedPerson = options.value.find(option => option.id === newValue)
  if (selectedPerson) {
    newsStore.setNewsPerson({
      id: selectedPerson.id,
      name: selectedPerson.name
    })
    newsStore.toggleNewsPersonSelector()
  }
})

onMounted(async () => {
  await newsStore.fetchNewsPersons()

  // Map the newsPersons to the desired format
  options.value = newsStore.newsPersons.map(person => ({
    id: person.id,
    name: person.name,
    roles: person.roles,
    profile_photo_path: person.profile_photo_path,
    profile_photo_url: person.profile_photo_url
  }))

  if (newsStore.newsPerson) {
    selectedPersonId.value = newsStore.newsPerson.id
  }
})


// watch(selectedPersonId, onSelect)
</script>

<style scoped>
/* Add any scoped styles here */
</style>
