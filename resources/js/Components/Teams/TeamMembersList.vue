<template>
<div class="bg-orange-300 p-2 font-bold">Team Members</div>
<!--    change :disabled for Add Member button to: :disabled="! spotsRemaining"-->
    <button
        class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
        @click="openModal"
        :disabled="teamStore.spotsRemaining < 1"
    >Add Member ({{ teamStore.spotsRemaining }} spots left)</button>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <!-- Avatar -->
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">

                        <div class="text-sm font-medium text-gray-900">
                            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
                            Name
                        </div>

                </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Position
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Phone
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Email
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Status
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <!-- Remove -->
            </td>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <TeamMember v-for="member in teamStore.members" :member="member" />
        </tbody>
    </table>
    <div class="text-right px-3 mt-2 text-gray-600 italic w-full"
         v-show="teamStore.memberSpots === teamStore.totalSpots">
        There are no remaining team spots. Edit the team to add more.
    </div>

    <Teleport to="body">
    <Modal :show="teamStore.showModal" @close="teamStore.showModal = false" >

        <template #header>
            Add a new team member
        </template>
        <template #default>

            <form @submit.prevent="submit">
                <input v-model="search"
                       type="search"
                       placeholder="Search by name..."
                       class="border px-2 rounded-lg mb-2"
                />
                <div class="h-32">
                <div v-show="props.creatorFilters.search != ''" class="">
                <div v-for="creator in creators.data"
                     :key="creator.id">

<!--                    <input type="radio" :id="creator.id" :value="creator.id" v-model="picked">-->

                    <button @click.prevent="addTeamMember(creator)"
                            :for="creator.id"
                            class="flex pb-1 cursor-pointer disabled:cursor-not-allowed"
                            :disabled="isUserOnTeam(creator)"
                            >
                        <img :src="creator.profile_photo_url" class="rounded-full mr-3 h-10 w-10 object-cover">
                        <div>{{creator.name}}</div>

                        <div v-if="creator.teams.includes(teamStore.id)"
                             class="text-xs text-white bg-green-800 uppercase justify-center items-center ml-3 top-1.5
                                    font-semibold inline-block h-1/2 py-0.5 px-1 rounded last:mr-0 mr-1">
                            team member
                        </div>

                    </button>

                </div>
                </div>
                </div>
                <div class="pt-4 font-semibold">
                    Can't find the person above? Invite them below!
                </div>
                <div class="pb-2">
                    Send an email invitation to join your team.
                </div>
                <div class="flex gap-2">
                    <input
                        type="email"
                        placeholder="Email Address..."
                        class="rounded flex-1"
                    >
                    <button class="bg-green-500 hover:bg-green-600 text-white rounded w-20 p-2">Invite</button>
                </div>
            </form>
        </template>
        <template #footer>
            <button @click="closeModal" class="text-blue-600 hover:text-gray-500">Cancel</button>
        </template>
    </Modal>
    </Teleport>


</template>

<script setup>
import { onBeforeMount, ref, watch } from "vue"
import TeamMember from "@/Components/Teams/TeamMember.vue";
import { useTeamStore } from "@/Stores/TeamStore";
import Modal from "@/Components/Modal"
import throttle from "lodash/throttle";
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/inertia-vue3";

let teamStore = useTeamStore();

let props = defineProps({
    creators: Object,
    creatorFilters: Object,
    picked: Boolean,
})

onBeforeMount(async () => {
    teamStore.showModal = false;
    props.creatorFilters.search = '';
})

let search = ref(props.creatorFilters.search);

watch(search, throttle(function (value) {
    Inertia.get('/teams/' + teamStore.slug + '/manage', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

function closeModal() {
    teamStore.showModal = false;

    // tec21: this isn't working...
    search = "";
}

function openModal() {
    teamStore.showModal = true;
}

function getCreators() {
    teamStore.showModal = true;
    console.log('GET CREATORS');
}

function isUserOnTeam(creator) {
    if (creator.teams.includes(teamStore.id)) {
        return true;
    }
    return false;
}

let form = useForm({
    user_id: '',
    team_id: teamStore.id,
    team_slug: teamStore.slug,
});

async function addTeamMember(creator) {

    if (creator.teams.includes(teamStore.id)) {
        return alert(creator.name + ' is already on this team.');
    }

    form.user_id = creator.id;
    teamStore.showModal = false;
    await submit();
    form.user_id = '';
}

function submit() {
    form.post(route('teams.addTeamMember'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            Inertia.visit(route('teams.manage', [teamStore.slug]), {
                method: 'get',
                preserveScroll: true,
            })
        }
    })
};


</script>
