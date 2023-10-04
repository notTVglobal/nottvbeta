<template>
    <Head title="Dashboard" />

    <div class="place-self-center flex flex-col gap-y-3 bg-blue-500 w-full">
        <div id="topDiv" class="rounded light:bg-white light:text-black dark:text-white dark:bg-gray-900 p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <div class="flex justify-between mb-6 pt-4">

                <h1 class="text-3xl font-semibold">Dashboard for Creators</h1>
                <p>Your timezone: {{userStore.timezone}}</p>

            </div>

                <div class="w-full flex flex-wrap-reverse mx-auto gap-2 mb-6">
                    <Link
                        :href="`/admin/settings`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                        v-if="props.can.viewAdmin"
                    >Admin Settings</button>
                    </Link>
                    <Link
                        :href="`/newsroom`"><button
                        class="bg-yellow-600 hover:bg-yellow-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                        v-if="props.can.viewNewsroom"
                    >Newsroom</button>
                    </Link>
                    <Link
                        :href="`/news/upload`"><button
                        class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400 cursor-not-allowed"
                        disabled
                    >Share News</button>
                    </Link>
                    <Link
                        :href="`/invite`"><button
                        class="bg-orange-600 hover:bg-orange-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    >Invite Creator</button>
                    </Link>
                    <Link :href="`/videoupload`"><button
                        class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    >Upload Video</button>
                    </Link>
                    <Link :href="`/golive`"><button
                        class="bg-red-600 hover:bg-red-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"

                    >Go Live</button>
                    </Link>

                </div>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8 mx-2 m-auto text-black">
                <div class="p-5 bg-gray-200 dark:bg-gray-800 rounded">
                    <div class="mb-3 grid grid-cols-1">
                        <div class="mb-1 font-semibold text-xl justify-self-start dark:text-gray-50">Open Assignments</div>
                    </div>
                    <div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
                        In development. Not currently working.
                    </div>
                    <div class="ml-3 text-center">
                        <button disabled class="text-blue-800 hover:text-blue-400 dark:text-blue-100 dark:hover:text-blue-400 disabled:text-gray-500">
                            Assignments list goes here
                        </button>

                    </div>
                    <div class="text-center text-gray-500">(coming soon)</div>
                </div>

                <div class="p-5 bg-gray-200 dark:bg-gray-800 rounded relative">

                    <div class="mb-3 flex justify-between">
                        <div class="mb-1 font-semibold text-xl dark:text-gray-50">My Teams
                        </div>

                        <div v-if="can.createTeam" class="">
                            <Link :href="`/teams/create`"><button
                                class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                            >New Team</button>
                            </Link>
                        </div>
                    </div>
                    <div v-if="props.teams.data == 0" class="italic dark:text-gray-50 light:text-black"> You have no teams.
                    </div>
                    <div
                        v-for="team in teams.data"
                        :key="team.id"
                        class="border-b light:bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"
                    >
                        <button
                            @click="visitTeamManagePage(team.slug)"
                            class="text-blue-800 hover:text-blue-900 dark:text-blue-100 dark:hover:text-white"><p class="px-2 py-1">
                            {{ team.name }}
                        </p></button>
                    </div>
                    <div class="w-full text-center mb-12">
                        <Popper
                            hover openDelay="50" closeDelay="50" offset-skid="0" offset-distance="0" placement="top"
                            arrow
                        ><template #content>
                            <p class="text-xl font-semibold mb-2">🎉 Start a new team</p>
                            <p class="">Teams manage shows, </p>
                            <p class="">even if you're a solo creator</p>
                        </template>
                            <button><font-awesome-icon
                                icon="fa-solid fa-question"
                                class="text-3xl text-pink-600 dark:text-pink-300 dark:hover:text-blue-400 hover:text-blue-400 mt-6 py-2"
                            /></button>
                        </Popper>
                    </div>
                    <div class="mt-24">
                        <!-- Paginator -->
                        <div><Pagination :data="props.teams" class="mt-6 absolute inset-x-0 bottom-0 py-2 px-2"/></div>

                    </div>

                </div>

                <div class="p-5 bg-gray-200 dark:bg-gray-800 rounded relative">
                    <div class="mb-3 flex justify-between">
                        <div class="mb-1 font-semibold text-xl dark:text-gray-50">My Shows</div>
                        <div v-if="can.createShow" class="">

                            <div v-if="props.teams.data.length > 0">
                                <Link :href="`/shows/create`"><button
                                    class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                                >Create Show</button>
                                </Link>
                            </div>

                            <div v-else>
                                <button class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400" @click="createShowWithNoTeamButton">Create Show</button>
                                <dialog id="dashboardNoTeams" class="modal">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg mb-3">You don't have any teams!</h3>
                                        <button class="btn btn-primary" @click="navigateToCreateTeam">Create a Team</button>
                                        <p class="py-4">Press ESC key or click outside to close</p>
                                    </div>
                                    <form method="dialog" class="modal-backdrop">
                                        <button>close</button>
                                    </form>
                                </dialog>
                            </div>

                        </div>
                    </div>

                    <div v-if="props.shows.data == 0" class="italic dark:text-gray-50 light:text-black"> You have no shows.
                    </div>
                    <div
                        v-for="show in shows.data"
                        :key="show.id"
                        class="border-b light:bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"
                    >
                        <Link
                            @click="videoPlayer.makeVideoTopRight()"
                            :href="`/shows/${show.slug}/manage`"
                            class="text-blue-800 hover:text-blue-900 dark:text-blue-100 dark:hover:text-white"><p class="px-2 py-1">
                            {{ show.name }}
                        </p></Link>
                    </div>
                    <div class="w-full text-center mb-12">
                        <Popper
                            hover openDelay="50" closeDelay="50" offset-skid="0" offset-distance="0" placement="top"
                            arrow
                        ><template #content>
                            <p class="text-xl font-semibold mb-2">🍿 These are your shows </p>
                            <p class="">Join or create a team to start a show.</p>
                        </template>
                            <button><font-awesome-icon
                                icon="fa-solid fa-question"
                                class="text-3xl text-pink-600 dark:text-pink-300 dark:hover:text-blue-400 hover:text-blue-400 mt-6 py-2"
                            /></button>
                        </Popper>
                    </div>
                    <div class="mt-24">
                        <Pagination :data="shows" class="mt-6 absolute inset-x-0 bottom-0 py-2 px-2"/>
                    </div>

                </div>


            </section>

            <div class="w-full light:bg-gray-300 dark:bg-gray-900 rounded p-3 my-8 mx-2 border-b border-2">
                <div class="stat place-items-center mb-4">
                    <div class="stat-title light:text-black dark:text-white mb-2">Yesterday's Top Show</div>
                    <div class="stat-value text-accent md:text-lg text-sm">Down The Rabbit Hole</div>
                    <div class="stat-desc">︎Episode 2</div>
                </div>
            </div>

            <div class="w-full bg-gray-300 dark:bg-gray-900 rounded pb-8 p-3 mb-16 mx-2 border-b border-2">

                <div class="font-semibold text-xl pb-2">Stats</div>

                <div class="w-full mx-auto stats shadow stats-vertical lg:stats-horizontal">



                    <div class="stat place-items-center">
                        <div class="stat-title">Avg. Daily View Time</div>
                        <div class="stat-value">~ minutes</div>
                        <div class="stat-desc">From January 1st to February 1st</div>
                    </div>

                    <div class="stat place-items-center">
                        <div class="stat-title">Premium subscribers</div>
                        <div class="stat-value text-secondary">{{ subscriptionCount }}</div>
                        <div class="stat-desc">↗︎ ~ (~%)</div>
                    </div>

                    <div class="stat place-items-center">
                        <div class="stat-title">New Creators</div>
                        <div class="stat-value">~</div>
                        <div class="stat-desc">↘︎ ~ (~%)</div>
                    </div>

                </div>

                <div class="w-full mt-4 mx-auto stats shadow stats-vertical lg:stats-horizontal">
                    <div class="stat place-items-center">
                        <div class="stat-title">Total Shows</div>
                        <div class="stat-value">{{ showCount }}</div>
                        <div class="stat-desc">↗︎ ~ (~%)</div>
                    </div>
                    <div class="stat place-items-center">
                        <div class="stat-title">Total Users</div>
                        <div class="stat-value">{{ userCount }}</div>
                        <div class="stat-desc">↗︎ ~ (~%)</div>
                    </div>
                    <div class="stat place-items-center">
                        <div class="stat-title">Total Creators</div>
                        <div class="stat-value">{{ creatorCount }}</div>
                        <div class="stat-desc">︎↗︎ ~ (~%)</div>
                    </div>
                </div>

                <div class="w-full mt-4 mx-auto stats shadow stats-vertical lg:stats-horizontal">
                    <div class="stat place-items-center">
                        <div class="stat-title">Total Video Storage Used</div>
                        <div class="stat-value text-secondary">{{ notTvTotalStorageUsed }}</div>
                        <div class="stat-desc">My total: {{ myTotalStorageUsed }} ({{ myTotalStoragePercentage }}%)</div>
                    </div>
                    <div class="stat place-items-center">
                        <div class="stat-title">Daily Peak Bandwidth</div>
                        <div class="stat-value text-secondary">~ Gbps</div>
                        <div class="stat-desc">︎↘︎ ~ (~%)</div>
                    </div>
                    <div class="stat place-items-center">
                        <div class="stat-title">Average Bandwidth per User</div>
                        <div class="stat-value text-secondary">~ Mbps</div>
                        <div class="stat-desc">︎↘︎ ~ (~%)</div>
                    </div>

                </div>
            </div>


            <div class="mt-6 h-0.5 bg-gray-800"></div>

            <section class="grid grid-cols-1 mt-6 gap-2">
                <div class="font-semibold text-2xl text-gray-800 dark:text-white px-2">
                    Account Summary
                </div>

                <div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
                    In development. Not currently working.
                </div>

                <div class="w-fit mx-auto stats stats-vertical sm:stats-horizontal bg-primary text-primary-content">

                    <div class="stat my-2">
                        <div class="stat-title text-white">Total balance</div>
                        <div class="stat-value">$89,410</div>
                        <div class="stat-actions">
                            <button class="btn btn-sm btn-success">Add funds</button>
                        </div>
                    </div>

                    <div class="stat my-2">
                        <div class="stat-title text-white">Available balance</div>
                        <div class="stat-value">$89,400</div>
                        <div class="stat-actions">
                            <button class="btn btn-sm mr-2">Withdrawal</button>
                            <button class="btn btn-sm">deposit</button>
                        </div>
                    </div>

                </div>

                <div class="border-2">
                    <div class="grid justify-items-stretch grid-cols-3 ">
                        <div class="bg-gray-800 text-white text-sm p-2 col-span-3">Membership: 000000</div>
                    </div>
                    <table class="w-full">
                        <thead class="">
                        <td class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">My Account Name</td>
                        <td class="bg-blue-400 px-2 mb-3 text-right font-semibold text-sm text-black">Balance</td>
                        </thead>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">Chequing</td>
                            <td class="px-2 text-right">89,400.00</td>
                        </tr>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">Equity Shares</td>
                            <td class="px-2 text-right">10.00</td>
                        </tr>
                    </table>
                    <table class="w-full mt-6">
                        <thead class="">
                            <td class="bg-blue-400 font-semibold text-sm text-black px-2">Team Shares</td>
                            <td class="bg-blue-400 px-2 text-right font-semibold text-sm text-black">Balance</td>
                        </thead>
                        <tr v-for="team in teams.data"
                            :key="team.id"
                            class="border-b border-1 border-gray-100">
                            <td class="px-2 py-2">{{ team.name }}</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                    </table>
                    <table class="w-full mt-6">
                        <thead class="">
                        <td class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">Community Account Name</td>
                        <td class="bg-blue-400 px-2 mb-3 text-right font-semibold text-sm text-black">Balance</td>
                        </thead>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">Public Good Fund</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">Production Fund for Members</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">News Fund</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                    </table>

                    </div>


            </section>
            <section class="mt-16 space-y-4 w-fit">
                <div class="text-sm uppercase mb-4 border-b border-blue-500">
                    External Links
                </div>
                <a href="https://www.cbsc.ca/" target="_blank">
                    <div class="hover:bg-blue-500 p-2">
                    Canadian Broadcast Standards Council</div></a>

                <a href="https://rtdnacanada.com/" target="_blank">
                    <div class="hover:bg-blue-500 p-2">
                        RTNDA</div></a>

                <a href="https://adstandards.ca/" target="_blank">
                    <div class="hover:bg-blue-500 p-2">
                    Ad Standards</div></a>

                <a href="https://www.cybertip.ca/en/" target="_blank">
                    <div class="hover:bg-blue-500 p-2">
                    Cybertip</div></a>

            </section>

        </div>
    </div>

</template>

<script setup>
import {computed, inject, onBeforeMount, onMounted, ref} from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Pagination from "@/Components/Pagination"
import Message from "@/Components/Modals/Messages";
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

const getUserData = inject('getUserData', null)

videoPlayerStore.loggedIn = true
userStore.currentPage = 'dashboard'
userStore.showFlashMessage = true;

onBeforeMount( () => {

})

onMounted(() => {
    if (!getUserData) {
        getUserTimezone()
        updateUserStore()
    }
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    Inertia.reload()
});


// isCreator, isNewsPerson, isVip, isSubscriber
// need to go in the UserStore. Inertia won't
// update the initialPage.props, so the solution
// is to send this data in 3 places the user
// will land after logging in.
// 1) The Dashboard
// 2) Stream
// 3) any other page from the /login page
// (AppLayout will be our catch all.)
// Only the Dashboard has a controller,
// the others will use Axios to get the data
// and save it in the UserStore.
let props = defineProps({
    id: null,
    isAdmin: null,
    isCreator: null,
    isNewsPerson: null,
    isSubscriber: null,
    hasAccount: null,
    isVip: null,
    shows: Object,
    teams: Object,
    myTotalStorageUsed: ref(String),
    notTvTotalStorageUsed: ref(String),
    showCount: Number,
    userCount: Number,
    creatorCount: Number,
    subscriptionCount: Number,
    can: Object,
});

const navigateToCreateTeam = () => {
    Inertia.visit('teams/create');
};

// Function to extract the numeric value from a string with "MB" suffix
const extractNumericValue = (str) => {
    const numericValue = parseFloat(str);
    return isNaN(numericValue) ? 0 : numericValue;
};

// Convert x and n to numeric values
const myTotalStorageUsedNumeric = extractNumericValue(props.myTotalStorageUsed);
const notTvTotalStorageUsedNumeric = extractNumericValue(props.notTvTotalStorageUsed);

// Create a computed property to calculate the percentage
const myTotalStoragePercentage = computed(() => {
    // return Math.round((myTotalStorageUsedNumeric / notTvTotalStorageUsedNumeric) * 100);
    return ((myTotalStorageUsedNumeric / notTvTotalStorageUsedNumeric) * 100).toFixed(2);
});

// Round the percentage to have no decimal places
const myTotalStorageRoundedPercentage = computed(() => {
    return Math.round(myTotalStoragePercentage);
});

function createShowWithNoTeamButton() {
    document.getElementById('dashboardNoTeams').showModal()
}

function visitTeamManagePage(team) {
    videoPlayerStore.makeVideoTopRight()
    Inertia.visit(`/teams/${team}/manage`)
}

async function updateUserStore() {
    userStore.id = props.id
    userStore.isAdmin = props.isAdmin
    userStore.isCreator = props.isCreator
    userStore.isNewsPerson = props.isNewsPerson
    userStore.isVip = props.isVip
    userStore.isSubscriber = props.isSubscriber
    userStore.hasAccount = props.hasAccount
    userStore.getUserDataCompleted = true
    userStore.timezone = userTimezone
    console.log('get user data on Dashboard')
    if (!userStore.userSubscribedToNotifications) {
        userStore.subscribeToUserNotifications(props.id)
    }
    // save user Timezone
    await updateUserTimezone()

}

const userTimezone = ref('');

const getUserTimezone = () => {
    // Use the Intl object to get the user's timezone
    userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

const updateUserTimezone = async () => {
    try {
        const response = await axios.post('/users/update-timezone', { timezone: userTimezone.value });

        // Handle success response as needed
        console.log(response.data.message);
    } catch (error) {
        // Handle error response or network error
        console.error(error);

        if (error.response) {
            // Handle specific error responses if needed
            console.error(error.response.data);
        }
    }
};

</script>


