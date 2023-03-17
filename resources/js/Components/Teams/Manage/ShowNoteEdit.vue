<template>
    <div>
        <form @submit.prevent="">
            <input
                class="text-black p-1 w-3/4"
                placeholder="Write a note..."
                type="text"
                v-model="form.note"
                @keyup.enter="saveNote">
        </form>
    </div>

</template>

<script setup>
import { useTeamStore } from "@/Stores/TeamStore";
import {useForm} from "@inertiajs/inertia-vue3";

let teamStore = useTeamStore();

teamStore.saveNoteProcessing = false;

let props = defineProps({
    show: Object,
});

let form = useForm({
    note: '',
});
form.note = props.show.notes;
const emit = defineEmits(['saveNoteProcessing'])

function saveNote() {

    // axios.post('/shows/notes', {
    //     notes: form.note,
    //     showId: props.show.id,
    // })
    //     teamStore.noteEdit = 0;
    //     updateNote++;
    //     props.show.note = 'update';

// Why does this not work? We are not
// getting a response.
//
    teamStore.saveNoteProcessing = true;
    teamStore.note = form.note;
    emit('saveNoteProcessing');
    axios.post('/shows/notes', {
        notes: form.note,
        showId: props.show.id,
    }).then(response => {
        teamStore.noteEdit = 0;
        console.log('note saved');
        teamStore.saveNoteProcessing = false;
    })
        .catch( error => {
            console.log( error );
        })
}

</script>
