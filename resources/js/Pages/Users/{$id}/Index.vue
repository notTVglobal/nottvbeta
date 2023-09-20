<template>

    <Head :title="props.userSelected.name" />

    <div class="flex flex-col gap-y-3">
        <div id="topDiv" class="light:bg-white dark:bg-gray-800 light:text-black dark:text-gray-50 p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <div class="flex justify-end mb-3 gap-2">
                <Link v-if="$page.props.user.isAdmin === 1" :href="`/users`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >All Users</button>
                </Link>
                <Link v-if="$page.props.user.isAdmin === 1" :href="`/users/${props.userSelected.id}/edit`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Edit User</button>
                </Link>
                <Link :href="`/dashboard`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Dashboard</button>
                </Link>
            </div>

            <p class="mb-6">
                <img :src="props.userSelected.profile_photo_url" class="rounded-full h-20 w-20 object-cover"/>
            </p>

            <h2 class="text-2xl pb-2">
                {{ props.userSelected.name }}
            </h2>

            <div class="p-6 light:bg-white dark:bg-gray-800 border-b border-gray-200 space-y-1">
                <div v-if="props.userSelected.isAdmin === 1">
                    <span class="font-bold text-red-600">Administrator</span>
                </div>
                <div>
                    <div class=""><span class="text-xs uppercase">User ID: </span><span class="font-semibold">{{props.userSelected.id}}</span></div>

                    <span class="text-sm font-semibold capitalize">User Type: </span>
                    {{ props.role }}
                </div>
                <div>
                    <span v-if="$page.props.userSelected.role_id === 4" class="text-sm font-semibold capitalize">Creator Number: </span>{{props.userSelected.creatorNumber}}
                </div>
                <div>
                    <span class="text-sm font-semibold capitalize">Subscription Status: </span>{{props.userSelected.subscriptionStatus}}
                </div>
            </div>
            <div class="p-6 light:bg-white dark:bg-gray-800 border-b border-gray-200">
                <div class="">
                    <span class="text-sm font-semibold capitalize">Email: </span>{{ props.userSelected.email }}
                </div>
                <div class="mb-6">
                    <span class="text-sm font-semibold capitalize">Phone: </span>{{ props.userSelected.phone }}
                </div>
                <div>
                    <span class="text-sm font-semibold capitalize">Address 1: </span>{{props.userSelected.address1}}
                </div>
                <div class="">
                    <span class="text-sm font-semibold capitalize">Address 2: </span>{{ props.userSelected.address2 }}
                </div>
                <div class="">
                    <span class="text-sm font-semibold capitalize">City: </span>{{ props.userSelected.city }}
                </div>
                <div class="">
                    <span class="text-sm font-semibold capitalize">Province: </span>{{ props.userSelected.province }}
                </div>
                <div class="">
                    <span class="text-sm font-semibold capitalize">Country: </span>{{ props.userSelected.country }}
                </div>
                <div class="">
                    <span class="text-sm font-semibold capitalize">Postal Code: </span>{{ props.userSelected.postalCode }}
                </div>
            </div>
            <div class="p-6 light:bg-white dark:bg-gray-800 border-b border-gray-200">
                <div class="text-2xl pb-2">
                    Teams this user belongs to:
                </div>
                <div v-for="team in props.teams"
                :key="team.id">
                    <Link :href="`/teams/${team.slug}`" class="light:text-blue-800 light:hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">{{team.name}}</Link>
                </div>
            </div>
        </div>
    </div>




</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

userStore.currentPage = 'users'
userStore.showFlashMessage = true;

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

let props = defineProps({
    userSelected: Object,
    role: String,
    teams: Object,
});

</script>
