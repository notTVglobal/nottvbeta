<template>
    <Head title="Dashboard" />

    <div class="place-self-center flex flex-col gap-y-3 bg-blue-500 w-full">
        <div id="topDiv" class="bg-white rounded text-black dark:text-white dark:bg-gray-900 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div class="flex justify-between mb-3 pt-4">


                <h1 class="text-3xl font-semibold pb-3">Dashboard (Creators only)</h1>

            </div>

                <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 mb-4">
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

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-16 mb-8 mx-2 m-auto text-black">
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
                        class="border-b bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"
                    >
                        <Link
                            @click="videoPlayer.makeVideoTopRight()"
                            :href="`/teams/${team.slug}/manage`"

                            class="text-blue-800 hover:text-blue-900 dark:text-blue-100 dark:hover:text-white"><p class="px-2 py-1">
                            {{ team.name }}
                        </p></Link>
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
                    </div>

                    <div v-if="props.shows.data == 0" class="italic dark:text-gray-50 light:text-black"> You have no shows.
                    </div>
                    <div
                        v-for="show in shows.data"
                        :key="show.id"
                        class="border-b bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"
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

            <div class="bg-gray-300 dark:bg-gray-900 rounded pb-8 p-3 mb-16 mx-2 border-b border-2">
                <div class="font-semibold text-xl pb-2">Stats</div>
                <div class="grid grid-cols-2 xl:grid-cols-3 justify-center gap-2">

                    <div class="px-4 py-4 w-100 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Total Video Storage Used
                        </div>
                        <div class="">
                            <div class="flex-wrap">
                                <div class="mt-8 text-3xl text-yellow-400 font-bold">{{ notTvTotalStorageUsed }}</div>
                                <div class="mt-10">My total: {{ myTotalStorageUsed }}</div>

                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-4 w-100 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Total Subscribers
                        </div>
                        <div>
                            Graph here
                            <div class="mt-4">
                                <ul>
                                    <li>Display total # of premium subscribers"</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-4 w-100 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Total Creators
                        </div>
                        <div>
                            Graph here
                            <div class="mt-4">
                                <ul>
                                    <li>Display total # of creators"</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-4 w-100 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Avg. View Time
                        </div>
                        <div>
                            Graph here
                            <div class="mt-4">
                                <ul>
                                    <li>Display total view time.</li>
                                    <li>Will need a table to keep track of this.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-4 w-100 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Daily Peak Bandwidth
                        </div>
                        <div>
                            Graph here
                            <div class="mt-4">
                                <ul>
                                    <li>* Display total peak bandwidth.</li>
                                    <li>* This can be captured from Mist logs</li>
                                    <li>* What about <a href="https://www.digitalocean.com/blog/its-all-about-the-bandwidth-why-many-network-intensive-services-select-digitalocean-as-their-cloud" target="_blank" class="text-blue-800">Digital Ocean?</a></li>


                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-4 w-100 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Today's Top Show
                        </div>
                        <div>
                            Display poster here
                            <div class="mt-4">
                                <ul>
                                    <li>Will need a table to track this.</li>
                                </ul>
                            </div>
                        </div>
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
                            <td class="px-2 text-right">0.00</td>
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
import { onBeforeMount, onMounted, ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Pagination from "@/Components/Pagination"
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'dashboard'

onBeforeMount(() => {
    updateUserStore()
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {
    //
    //     userStore.scrollToTopCounter ++;
    // }
});

videoPlayerStore.loggedIn = true

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
    isAdmin: null,
    isCreator: null,
    isNewsPerson: null,
    isSubscriber: null,
    hasAccount: null,
    isVip: null,
    shows: Object,
    teams: Object,
    myTotalStorageUsed: String,
    notTvTotalStorageUsed: String,
    can: Object,
    message: String
});

let showMessage = ref(true);

function updateUserStore() {
    userStore.isAdmin = props.isAdmin
    userStore.isCreator = props.isCreator
    userStore.isNewsPerson = props.isNewsPerson
    userStore.isVip = props.isVip
    userStore.isSubscriber = props.isSubscriber
    userStore.hasAccount = props.hasAccount
    userStore.getUserDataCompleted = true
    console.log('get user data on Dashboard')
}

</script>


