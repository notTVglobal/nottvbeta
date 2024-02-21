<template>
  <div>
      <div class="flex flex-row">
        <!--                <img :src="'/storage/images/' + show.poster" alt="" class="w-20 mr-2 justify-left">-->
        <Link :href="`/shows/${show.slug}`" class="uppercase">
          <SingleImage :image="show.image" alt="'show poster'" class="w-60 h-80 object-cover mr-2 justify-left"/>
        </Link>
        <div class="flex flex-col pl-4">
          <Link :href="`/shows/${show.slug}`" class="uppercase"><span class="hover:text-blue-600 mb-3 inline-flex items-center text-3xl font-semibold relative">{{ show.name }}</span></Link>
            <div><span class="text-xs  font-semibold uppercase">Team: </span>
              <Link :href="`/teams/${team.slug}/manage`" class="text-blue-500 hover:text-blue-600 ml-2 uppercase font-bold"> {{
                  team.name
                }}
              </Link>
            </div>
            <div><span class="text-xs font-semibold mr-2 uppercase">Show Runner: </span><span
                class="font-medium font-bold"> {{ show.showRunner }} </span></div>
            <div class="pt-4"><span class="text-xs font-semibold mr-2 uppercase">Category: </span><span
                class="font-medium text-orange-800"> {{ show.category.name }} </span></div>
            <div><span class="text-xs font-semibold mr-2 uppercase">Sub-category: </span><span
                class="font-medium text-orange-800"> {{ show.subCategory.name }} </span></div>

          <div class="mt-12 w-full h-full flex flex-col">
            <div class="flex-grow"></div>
            <button class="btn btn-lg bg-green-500 hover:bg-green-700 border-green-500 text-white text-success-content drop-shadow-lg py-2 flex flex-col">
              <span>Add Show To Schedule</span>
            </button>

          </div>
        </div>

      </div>

  </div>
</template>

<script setup>
import { useShowStore } from "@/Stores/ShowStore"
import { useTeamStore } from "@/Stores/TeamStore"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import Button from '@/Jetstream/Button.vue'

const showStore = useShowStore()
const teamStore = useTeamStore()

defineProps({
  show: Object,
  team: Object,
})

</script>
