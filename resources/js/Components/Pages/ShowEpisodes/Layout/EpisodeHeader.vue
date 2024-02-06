<template>

  <div class="inline-flex items-center text-3xl font-semibold relative">
    <Link :href="`/shows/${show.slug}/episode/${episode.slug}`" class="uppercase">
      <div class="flex flex-row space-x-2">
        <!--                <img :src="'/storage/images/' + show.poster" alt="" class="w-20 mr-2 justify-left">-->
        <SingleImage :image="episode.image" alt="'episode poster'"
                     class="h-50 w-80 object-cover"/>
        <span class="hover:text-blue-700 pl-4 inline-flex items-center text-3xl font-semibold relative">{{ episode.name }}</span>
      </div>
    </Link>

  </div>


</template>

<script setup>
import { useShowStore } from "@/Stores/ShowStore"
import { useTeamStore } from "@/Stores/TeamStore"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"

const showStore = useShowStore()
const teamStore = useTeamStore()

defineProps({
  episode: Object,
  show: Object,
  team: Object,
})

</script>
