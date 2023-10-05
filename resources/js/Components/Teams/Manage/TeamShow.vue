<template>
    <tr>
        <td class="px-6 py-4 w-20 whitespace-nowrap">
<!--            <img :src="'/storage/images/' + show.poster" class="h-20 w-20 object-cover">-->

            <SingleImage :image="show.image" :poster="show.poster" :alt="'show cover'" class="h-20 w-20 min-w-[2.5rem] object-cover"/>

        </td>

        <td class="px-6 py-4 whitespace-nowrap">
            <Link :href="`/shows/${show.slug}/manage`" class="uppercase text-xl font-semibold text-blue-600 hover:text-blue-900">{{ show.name }}</Link>
        </td>

        <td class="px-6 py-4 text-sm w-full min-w-[16rem]">
            <button v-if="!show.notes" v-show="teamStore.noteEdit !== props.show.id" class="italic" @click="editNote">Click here to add/edit a note.</button>
            <button v-if="show.notes" v-show="teamStore.noteEdit !== props.show.id" :key="componentKey" @click="editNote">{{ show.notes }}</button>
            <div v-if="teamStore.noteEdit === props.show.id">

                <ShowNoteEdit :show="props.show" v-on:saveNoteProcessing="reloadNote" />
                <div v-if="teamStore.saveNoteProcessing">Saving...</div>
            </div>

        </td>

        <td class="text-left text-gray-600 px-6 py-4 text-sm text-right">
            {{ show.categoryName[0] }}
        </td>

        <td class="text-gray-600 px-6 py-4 text-sm text-right">
            {{ show.status }}
        </td>

        <td class="text-gray-600 px-6 py-4 text-sm text-right">
            {{  show.showRunnerName }}
        </td>

        <td v-if="can.manageTeam || can.editTeam" class="text-gray-600 px-6 py-4 text-sm text-right">
            <Link
                v-if="can.editTeam" :href="`/shows/${show.slug}/edit`">
                <button
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                >Edit
                </button>
            </Link>
        </td>

    </tr>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { useTeamStore } from "@/Stores/TeamStore";
import ShowNoteEdit from "@/Components/Teams/Manage/ShowNoteEdit";

import SingleImage from "@/Components/Multimedia/SingleImage";

let teamStore = useTeamStore();

let props = defineProps({
    show: Object,
    can: Object,
});

teamStore.noteEdit = 0

const componentKey = ref(0);

function reloadNote () {
    props.show.notes = teamStore.note;
    componentKey.value += 1;
}

let form = useForm({
    note: '',
});

function editNote() {
    teamStore.noteEdit = props.show.id
}



</script>
