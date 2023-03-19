<template>
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <img :src="'/storage/images/' + show.poster" class="h-20 w-20 object-cover">
        </td>

        <td class="px-6 py-4 whitespace-nowrap">
            <Link :href="`/shows/${show.slug}/manage`" class="uppercase text-xl font-semibold light:text-blue-600 light:hover:text-blue-900 dark:text-blue-200 dark:hover:text-blue-400">{{ show.name }}</Link>
        </td>

        <td class="light:text-gray-600 dark:text-gray-100 px-6 py-4 text-sm w-full min-w-[16rem]" @click="editNote">
            <span v-if="!show.notes" v-show="teamStore.noteEdit !== props.show.id" class="italic">Click here to add/edit a note.</span>
            <span v-if="show.notes" v-show="teamStore.noteEdit !== props.show.id" :key="componentKey">{{ show.notes }}</span>
            <div v-show="teamStore.noteEdit === props.show.id">

                <ShowNoteEdit :show="props.show" v-on:saveNoteProcessing="reloadNote" />
                <div v-show="teamStore.saveNoteProcessing">Saving...</div>
            </div>

        </td>

        <td class="light:text-gray-600 dark:text-gray-100 px-6 py-4 text-sm text-right">
            {{ show.status }}
        </td>

        <td class="light:text-gray-600 dark:text-gray-100 px-6 py-4 text-sm text-right">
            {{  show.showRunnerName }}
        </td>

    </tr>
</template>

<script setup>
import { useTeamStore } from "@/Stores/TeamStore";
import ShowNoteEdit from "@/Components/Teams/Manage/ShowNoteEdit";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

let teamStore = useTeamStore();

let props = defineProps({
    show: Object,
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
