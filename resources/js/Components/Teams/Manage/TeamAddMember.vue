<template>
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
                    <div v-show="props.creatorFilters != ''" class="">
                        <div v-for="creator in creators.data"
                             :key="creator.id">
                            <div>
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

</template>

<script setup>
import { ref, watch } from "vue"
import Modal from "@/Components/Modal"
import throttle from "lodash/throttle";
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import { useTeamStore } from "@/Stores/TeamStore";

let teamStore = useTeamStore();

let props = defineProps({
    creators: Object,
    creatorFilters: Object,
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
    search = '';
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
