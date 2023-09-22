<template>
    <tr>
        <td class="px-6 py-4 text-sm">

            <!-- If there is no episode number set by the user
                   the episode number defaults to the episode id           -->
            <div v-if="!episode.episodeNumber">{{ episode.id }}</div>
            <div v-if="episode.episodeNumber">{{ episode.episodeNumber }}</div>

        </td>
        <td class="text-xl font-medium flex items-center gap-x-4 px-6 py-4 uppercase w-fit">
<!--            <img :src="`/storage/images/${episode.poster}`" alt="" class="rounded-xl w-10">-->
            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
            <Link :href="`/shows/${showSlug}/episode/${episode.slug}/`" class="font-semibold light:text-blue-800 light:hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-200">

                {{  episode.name }}

            </Link>
        </td>

<!--        <td class="text-gray-500 px-6 py-4 text-sm">-->
<!--            {{episode.notes}}-->
<!--        </td>-->
        <td class="light:text-gray-600 dark:text-gray-100 px-6 py-4 text-sm w-full min-w-[16rem]">
            <span v-if="!episode.notes" v-show="showStore.noteEdit !== props.episode.id" class="italic" @click="editNote">Click here to add/edit a note.</span>
            <span v-if="episode.notes" v-show="showStore.noteEdit !== props.episode.id" :key="componentKey" @click="editNote">{{ episode.notes }}</span>
            <div v-if="showStore.noteEdit === props.episode.id">

                <EpisodeNoteEdit :episode="props.episode" v-on:saveNoteProcessing="reloadNote" />
                <div v-if="showStore.saveNoteProcessing">Saving...</div>
            </div>

        </td>
        <td class="px-6 py-4 text-right">
            <div class="dropdown dropdown-left">
<!--                <button tabindex="0" :class="episodeStatus" @click="openEpisodeStatuses">{{ episode.episodeStatus }}</button>-->
                <ShowEpisodeStatuses :episodeStatus="props.episode.episodeStatus"
                                     :episodeStatusId="props.episode.episodeStatusId"
                                     :episodeStatuses="props.episodeStatuses"
                                     :episodeId="props.episode.id"/>
            </div>
<!--            <button v-if="episode.episodeStatusId===1" class="font-semibold text-xl text-orange-400">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->
<!--            <button v-if="episode.episodeStatusId===2" class="text-xl text-green-400">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->
<!--            <button v-if="episode.episodeStatusId===3" class="font-semibold text-xl text-green-600">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->
<!--            <button v-if="episode.episodeStatusId===4" class="font-bold text-xl text-green-600">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->
<!--            <button v-if="episode.episodeStatusId===5" class="font-semibold text-xl text-purple-700">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->
<!--            <button v-if="episode.episodeStatusId===6" class="font-italic text-xl text-pink-500">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->
<!--            <button v-if="episode.episodeStatusId===7" class="font-bold text-xl text-black">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->
<!--            <button v-if="episode.episodeStatusId===8" class="font-medium text-xl text-gray-500">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->
<!--            <button v-if="episode.episodeStatusId===9" class="font-semibold font-italic text-xl text-red-700">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->
<!--            <button v-if="episode.episodeStatusId===10" class="font-bold text-xl text-red-800">-->
<!--                {{ episode.episodeStatus }}-->
<!--            </button>-->


        </td>
        <td>
            <div class="">
                <Link
                    :href="`/shows/${showSlug}/episode/${episode.slug}/edit`"
                    v-if="teamStore.can.editShow">
                    <button
                        class="px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"
                    >Edit
                    </button>
                </Link>
            </div>
        </td>
    </tr>
</template>

<script setup>
import { useTeamStore } from "@/Stores/TeamStore";
import { useShowStore } from "@/Stores/ShowStore";
import {computed, ref} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import EpisodeNoteEdit from "@/Components/Shows/Manage/EpisodeNoteEdit";
import ShowEpisodeStatuses from "@/Components/Shows/Manage/ShowEpisodeStatuses.vue";

let teamStore = useTeamStore();
let showStore = useShowStore();

let props = defineProps({
    episode: Object,
    episodeStatuses: Object,
    showSlug: String,
});

let showEpisodeStatuses = ref(false)
showStore.noteEdit = 0
const componentKey = ref(0);

function reloadNote () {
    props.episode.notes = showStore.note;
    componentKey.value += 1;
}

let form = useForm({
    note: '',
});

function editNote() {
    showStore.noteEdit = props.episode.id
}

function openEpisodeStatuses() {
    document.getElementById('episodeStatuses').showModal()
}






</script>
