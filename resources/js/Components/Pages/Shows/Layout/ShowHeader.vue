<template>
  <div>
    <Link :href="`/shows/${show.slug}`" class="uppercase">
      <div class="flex flex-row">
        <!--                <img :src="'/storage/images/' + show.poster" alt="" class="w-20 mr-2 justify-left">-->
        <SingleImage :image="show.image" alt="'show poster'" class="w-60 h-80 object-cover mr-2 justify-left"/>
        <div class="flex flex-col pl-4">
          <span class="mb-3 inline-flex items-center text-3xl font-semibold relative">{{ show.name }}</span>
            <div><span class="text-xs  font-semibold uppercase">Team: </span>
              <Link :href="`/teams/${team.slug}/manage`" class="text-blue-500 ml-2 uppercase font-bold"> {{
                  team.name
                }}
              </Link>
            </div>
            <div><span class="text-xs  font-semibold mr-2 uppercase">Show Runner: </span><span
                class="font-bold"> {{ show.showRunner }} </span></div>
            <div><span class="text-xs  font-semibold mr-2 uppercase">Category: </span><span
                class="font-bold"> {{ show.categoryName }} </span></div>
            <div><span class="text-xs  font-semibold mr-2 uppercase">Sub-category: </span><span
                class="font-bold"> {{ show.subCategoryName }} </span></div>

        </div>
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
  show: Object,
  team: Object,
})

</script>
