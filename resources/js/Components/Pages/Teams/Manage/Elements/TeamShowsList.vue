<template>
  <div>
    <!--        <div class="bg-orange-300 text-black p-2 font-bold">Shows</div>-->

    <div>
      <button
          v-if="teamStore.can.manageTeam"
          @click="appSettingStore.btnRedirect('/shows/create')"
          class="bg-green-500 hover:bg-green-600 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
      >Create Show
      </button>
    </div>

    <!--    <div v-if="isSmallScreen" class="stacked-table">-->
    <!--      <div v-for="show in shows.data" :key="show.id" class="card">-->
    <!--        <div><b>Show Name:</b> {{ show.name }}</div>-->
    <!--        <div><b>Show Notes:</b> {{ show.notes }}</div>-->
    <!--        &lt;!&ndash; Add other fields similarly &ndash;&gt;-->
    <!--      </div>-->
    <!--    </div>-->


    <div v-if="appSettingStore.isSmallScreen" class="stacked-table">
      <div v-for="show in shows.data" :key="show.id" class="card border-t border-b border-gray-300 my-2 p-2 bg-gray-50">
        <div class="flex justify-between items-center mb-2">
          <Link :href="`/shows/${show.slug}/manage`" class="text-2xl font-semibold text-blue-600 hover:text-blue-900">
            {{ show.name }}
          </Link>
          <button
              v-if="can.manageTeam || can.editTeam"
              @click="appSettingStore.btnRedirect(`/shows/${show.slug}/edit`)"
              class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white font-semibold ml-2 px-4 py-2 rounded disabled:bg-gray-400"
          >
            Edit
          </button>
        </div>
        <div @click.prevent="appSettingStore.btnRedirect(`/shows/${show.slug}/manage`)"
             class="flex w-full justify-center mb-2 hover:cursor-pointer">
          <SingleImage :image="show.image" :poster="show.poster" :alt="'show cover'"
                       class="h-full max-h-32 w-auto object-contain"/>
        </div>
        <div class="flex flex-wrap justify-between text-sm text-gray-600 mb-1">
          <div>
            <b>Category:</b> {{ show?.category?.name }}
          </div>
          <div v-if="show.status">
            <b>Show Status:</b> {{ show.status }}
          </div>
        </div>
        <div class="flex flex-wrap justify-between text-sm text-gray-600 mb-1 break-words w-full custom-word-wrap">
          <div class="text-sm text-gray-600 mb-2">
            <b>Show Runner:</b> {{ show?.showRunner?.name }}
          </div>
          <div>
            <button v-if="!show.notes" v-show="teamStore.noteEdit !== show.id" class="italic" @click="editNote(show)">Click
              here to add/edit a note.
            </button>
            <button v-if="show.notes" v-show="teamStore.noteEdit !== show.id" :key="componentKey" @click="editNote(show)">
              <span class="text-xs uppercase font-semibold">Notes: </span>{{ show.notes }}
            </button>
            <div v-if="teamStore.noteEdit === show.id">
              <ShowNoteEdit :show="show" v-on:saveNoteProcessing="reloadNote(show)"/>
              <div v-if="teamStore.saveNoteProcessing">Saving...</div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div v-else class="overflow-x-auto">
      <table class="table-auto min-w-full divide-y divide-gray-200">
        <thead class="divide-y divide-gray-200">
        <tr>
          <td class="px-6 py-4">
            <!--                Poster-->
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
            Show Name

          </td>

          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium max-w-32">
            Show Notes
          </td>

          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            Category
          </td>

          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            Show Status
          </td>

          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
            Show Runner
          </td>

          <td v-if="can.manageTeam || can.editTeam" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">

          </td>

        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">

        <TeamShow
            v-for="show in shows.data"
            :show="show"
            :can="can"
        />

        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination :data="shows" class="mt-6"/>
  </div>

</template>

<script setup>
import { useShowStore } from '@/Stores/ShowStore'
import { useTeamStore } from '@/Stores/TeamStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import TeamShow from '@/Components/Pages/Teams/Manage/Elements/TeamShow'
import Pagination from '@/Components/Global/Paginators/Pagination'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ShowNoteEdit from '@/Components/Pages/Teams/Manage/Elements/ShowNoteEdit.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const appSettingStore = useAppSettingStore()
const showStore = useShowStore()
const teamStore = useTeamStore()


const props = defineProps({
  shows: Object,
  can: Object,
})

const componentKey = ref(0)
teamStore.noteEdit = 0

const editNote = (show) => {
  teamStore.noteEdit = show.id
}

const reloadNote = (show) => {
  show.notes = teamStore.note
  componentKey.value += 1
}

</script>
<style scoped>
.custom-word-wrap {
  overflow-wrap: break-word;
  word-wrap: break-word; /* For IE */
  word-break: break-word;
  hyphens: auto;
}
</style>

