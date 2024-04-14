<template>
  <tr>
    <td class="px-6 py-4 w-20 whitespace-nowrap">
      <!--            <img :src="'/storage/images/' + show.poster" class="h-20 w-20 object-cover">-->

      <div @click="Inertia.visit(`/shows/${show.slug}/manage`)" class="h-20 w-20 min-w-[2.5rem] hover:cursor-pointer">
        <SingleImage :image="show.image" :alt="'show cover'"
                     class="w-full h-full object-cover"/>
      </div>


    </td>

    <td @click="Inertia.visit(`/shows/${show.slug}/manage`)"
        class="px-6 py-4 whitespace-nowrap uppercase text-xl font-semibold text-blue-600 hover:text-blue-900 hover:cursor-pointer">
      {{ show.name }}
    </td>

    <td class="px-6 py-4 text-sm w-full min-w-[16rem]">
      <button v-if="!show.notes" v-show="teamStore.noteEdit !== props.show.id" class="italic" @click="editNote">Click
        here to add/edit a note.
      </button>
      <button v-if="show.notes" v-show="teamStore.noteEdit !== props.show.id" :key="componentKey" @click="editNote">
        {{ show.notes }}
      </button>
      <div v-if="teamStore.noteEdit === props.show.id">

        <ShowNoteEdit :show="show" v-on:saveNoteProcessing="reloadNote"/>
        <div v-if="teamStore.saveNoteProcessing">Saving...</div>
      </div>

    </td>

    <td class="text-left text-gray-600 px-6 py-4 text-sm text-right">
      {{ show.category.name }}
    </td>

    <td class="text-gray-600 px-6 py-4 text-sm text-right">
      {{ show.status }}
    </td>

    <td class="text-gray-600 px-6 py-4 text-sm text-right">
      {{ show?.showRunner?.name }}
    </td>

    <td v-if="can.manageTeam || can.editTeam" class="text-gray-600 px-6 py-4 text-sm text-right">
      <button
          @click="appSettingStore.btnRedirect(`/shows/${show.slug}/edit`)"
          class="bg-blue-500 hover:bg-blue-600 text-white font-semibold ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
      >Edit
      </button>
    </td>

  </tr>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useTeamStore } from '@/Stores/TeamStore'
import ShowNoteEdit from '@/Components/Pages/Teams/Manage/Elements/ShowNoteEdit'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import { Inertia } from '@inertiajs/inertia'

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()

let props = defineProps({
  show: Object,
  can: Object,
})

let form = useForm({
  note: '',
})

const componentKey = ref(0)
teamStore.noteEdit = 0

function editNote() {
  teamStore.noteEdit = props.show.id
}

function reloadNote() {
  props.show.notes = teamStore.note
  componentKey.value += 1
}

</script>
