<template>
    <div>
        <form @submit.prevent="">
            <input
                class="text-black p-1 w-3/4"
                placeholder="Write a note..."
                type="text"
                ref="episodeNote"
                v-model="form.note"
                @keyup.enter="saveNote"
                @focusout="closeNote">
        </form>
    </div>

</template>

<script setup>
import { useShowStore } from "@/Stores/ShowStore";
import {useForm} from "@inertiajs/inertia-vue3";
import {onMounted, ref} from "vue";
import note from "lodash/seq";

let showStore = useShowStore();

showStore.saveNoteProcessing = false;

let props = defineProps({
    episode: Object,
});

let form = useForm({
    note: '',
});
form.note = props.episode.notes;
const emit = defineEmits(['saveNoteProcessing'])
const episodeNote = ref(null);

// Focus the input element when the component is mounted
onMounted(() => {
    episodeNote.value.focus();
});

function closeNote() {
    if (form.note === props.episode.notes) {
        showStore.noteEdit = 0;
    } saveNote()
}

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
    showStore.saveNoteProcessing = true;
    showStore.note = form.note;
    emit('saveNoteProcessing');
    axios.post('/shows/episode/notes', {
        notes: form.note,
        episodeId: props.episode.id,
    }).then(response => {
        showStore.noteEdit = 0;
        console.log('note saved');
        showStore.saveNoteProcessing = false;
    })
        .catch( error => {
            console.log( error );
        })
}

</script>
