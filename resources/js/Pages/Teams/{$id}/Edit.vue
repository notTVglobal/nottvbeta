<template>

    <Head :title="`Edit Team: ${props.team.name}`"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <TeamEditHeader :team="props.team" :teamLeaderName="props.teamLeaderName" />


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

<!--                            <TeamEditBody-->
<!--                                :team="props.team"-->
<!--                                :logo="props.logo"-->
<!--                                :images="props.images"-->
<!--                            />-->


                            <div v-if="form.errors.name" v-text="form.errors.name"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.description" v-text="form.errors.description"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.totalSpots" v-text="form.errors.totalSpots"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

                            <!-- Begin grid 2-col -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">

                                <!--Left Column-->
                                <div>
                                    <div class="flex space-y-3">
                                        <div class="mb-6">
                                            <SingleImage :image="props.image" :key="props.image" :alt="'team logo'" class=""/>
<!--                                            <img :src="'/storage/images/' + props.logo" :key="logo"/>-->
                                        </div>
                                    </div>

                                    <div>

                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                               for="name"
                                        >
                                            Change Team Logo
                                        </label>
                                        <ImageUpload :image="props.image"
                                                     :server="'/teamsUploadLogo'"
                                                     :name="'Upload Team Logo'"
                                                     :maxSize="'10MB'"
                                                     :fileTypes="'image/jpg, image/jpeg, image/png'"
                                                     @reloadImage="reloadImage"
                                        />

                                    </div>

                                </div>

                                <!--Right Column-->
                                <div>
                                    <!--            Replace this with TeamLogoUpload -->
<!--                                    <TeamLogoUpload-->
<!--                                        :team="props.team"-->
<!--                                        :images="props.images"-->
<!--                                    />-->





                                    <form @submit.prevent="submit">
                                        <div class="mb-6">
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                                   for="name"
                                            >
                                                Team Name
                                            </label>

                                            <input v-model="form.name"
                                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                                   type="text"
                                                   name="name"
                                                   id="name"
                                                   required
                                            >
                                            <div v-if="form.errors.name" v-text="form.errors.name"
                                                 class="text-xs text-red-600 mt-1"></div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                                   for="description"
                                            >
                                                Description
                                            </label>
                                            <TabbableTextarea v-model="form.description"
                                                              class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                                              name="description"
                                                              id="description"
                                                              rows="10" cols="30"
                                                              required
                                            />
                                            <div v-if="form.errors.description" v-text="form.errors.description"
                                                 class="text-xs text-red-600 mt-1"></div>
                                        </div>

<!--                                        tec21: this is to become a searchable list to select a team leader -->
<!--                                        <div class="mb-6">-->
<!--                                            <label class="block mb-2 uppercase font-bold text-xs text-gray-700"-->
<!--                                                   for="teamLeader"-->
<!--                                            >-->
<!--                                                Team Leader-->
<!--                                            </label>-->
<!--                                            <input v-model="form.teamLeader"-->
<!--                                                   class="border border-gray-400 p-2 w-full rounded-lg"-->
<!--                                                   type="text"-->
<!--                                                   name="teamLeader"-->
<!--                                                   id="teamLeader"-->
<!--                                            />-->
<!--                                            <div v-if="form.errors.teamLeader" v-text="form.errors.teamLeader"-->
<!--                                                 class="text-xs text-red-600 mt-1"></div>-->
<!--                                        </div>-->

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                                   for="description"
                                            >
                                                Maximum # of Team Members
                                            </label>
                                            <input v-model="form.totalSpots"
                                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                                   type="text"
                                                   name="totalSpots"
                                                   id="totalSpots"
                                            />
                                            <div v-if="form.errors.totalSpots" v-text="form.errors.totalSpots"
                                                 class="text-xs text-red-600 mt-1"></div>
                                        </div>
                                        <div v-if="props.message" class="text-sm text-red-600 mt-1 mb-2">
                                            {{ props.message }}
                                        </div>
                                        <div class="flex justify-between mb-6">
                                            <JetValidationErrors class="mr-4" />
                                            <button
                                                type="submit"
                                                class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4 "
                                                :disabled="form.processing"
                                            >
                                                Save
                                            </button>
                                        </div>
                                    </form>

                                </div>
                                <!-- End Right Column -->
                            </div>
                            <!-- End grid 2-col -->



                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import SingleImage from "@/Components/Multimedia/SingleImage";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import TeamEditHeader from "@/Components/Teams/Edit/TeamEditHeader";
import TeamEditBody from "@/Components/Teams/Edit/TeamEditBody";
import TeamLogoUpload from "@/Components/FilePond/TeamLogoUpload";
import TabbableTextarea from "@/Components/TabbableTextarea"
import ImageUpload from "@/Components/Uploaders/ImageUpload";


let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let userStore = useUserStore()

userStore.currentPage = 'teams'
userStore.showFlashMessage = true;
teamStore.setActiveTeam(props.team);
teamStore.logoName = props.image.name;

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

let props = defineProps({
    user: Object,
    team: Object,
    teamLeaderName: String,
    image: Object,
});

let form = useForm({
    id: props.team.id,
    name: props.team.name,
    description: props.team.description,
    totalSpots: props.team.totalSpots,
});

let reloadImage = () => {
    Inertia.reload({
        only: ['image'],
    });
};

let submit = () => {
    form.put(route('teams.update', props.team.slug));
};

</script>


