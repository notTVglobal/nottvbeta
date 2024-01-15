<template>
            <form @submit.prevent="submit">
                <input v-model="search"
                       type="search"
                       placeholder="Enter Creator's Name"
                       class="border px-2 my-2"
                />
                <div class="transfer-request-section">

                        <div v-for="creator in creators.data"
                             :key="creator.id">
                            <div>
                                <button @click.prevent="selectCreator(creator)"
                                        :class="{'selected-creator': selectedCreatorId === creator.id}"
                                        class="flex items-center justify-center pb-2 px-4 cursor-pointer disabled:cursor-not-allowed"
                                        :disabled="isThisUser(creator)"
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

                <button @click="submit" class="request-button">Send Request</button>

            </form>
</template>

<script setup>
import { ref, watch } from "vue"
import throttle from "lodash/throttle";
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import { useTeamStore } from "@/Stores/TeamStore";
import { useUserStore } from "@/Stores/UserStore";

const teamStore = useTeamStore();
const userStore = useUserStore();

let props = defineProps({
    creators: Object,
    creatorFilters: Object,
})

let search = ref(props.creatorFilters.search);


const isThisUser = (creator) => {
    console.log('is this user...')
    if (creator.user_id === userStore.id) {
        return true
    }
}

watch(search, throttle(function (value) {
    Inertia.get('/teams/' + teamStore.slug + '/manage', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

let form = useForm({
    user_id: '',
    team_id: teamStore.id,
    team_slug: teamStore.slug,
});

const selectedCreatorId = ref(null);

async function selectCreator(creator) {
    console.log('select creator')
    // highlight selected creator and store their data in the form.
    selectedCreatorId.value = creator.id;
    form.user_id = creator.id;
    console.log(form.user_id);
}

// Define props and emits if needed
// const props = defineProps({});
const emits = defineEmits(['transferInitiated']);

// Method to handle the initiation of the transfer
const initiateTransfer = () => {
    console.log('initiate transfer')
    // Implement your logic here
    // For example, emitting an event or routing to a different page
    emits('transferInitiated');
};

function submit() {
    console.log('submit form')
    form.post(route('teams.sendTransferRequest', form.team_slug), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            initiateTransfer()
        },
        onError: (errors) => {
            // Handle the error response here
            console.log('Error', errors);
            // You can update the state or show an error message to the user
        }
    })
}

</script>
<style scoped>

.selected-creator {
    outline: 2px solid blue; /* Change the color as needed */
    border-radius: 0.75rem; /* This is equivalent to 'rounded-lg' in Tailwind CSS */
    padding: 4px 16px;
}

.team-transfer-container {
    margin: 20px;
}

.team-transfer-section, .transfer-request-section {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
}

.transfer-button, .request-button {
    /* Shared button styles */
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
}

.description {
    margin-top: 10px;
    color: #555;
}

.confirmation-section {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
}
</style>
