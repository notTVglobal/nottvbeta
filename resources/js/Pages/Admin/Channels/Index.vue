<template>
    <Head title="Channels Admin" />

    <div class="place-self-center flex flex-col">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <AdminHeader>Channels</AdminHeader>

            <div class="flex flex-row justify-between gap-x-4 mb-3">
                <Link :href="`/users/create`"><button
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"
                    disabled
                >Add Channel</button>
                </Link>
                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
            </div>

            <div class="bg-orange-300 px-2 text-black mb-3">
                Add a channel: create playlist and add shows.
            </div>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <div
                                    class="table w-full text-sm text-left text-gray-500 dark:text-gray-400"
                                >
                                    <div
                                        class="table-header-group text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                    >
                                        <div class="table-row">
                                            <div scope="col" class="table-cell px-6 py-3">
                                                <font-awesome-icon icon="fa-repeat" class="mr-2 cursor-pointer hover:text-blue-500" @click.prevent="reload()"/>
                                                Name
                                            </div>
                                            <div scope="col" class="hidden md:table-cell px-6 py-3">
                                                Current Video
                                            </div>
                                            <div scope="col" class="table-cell px-6 py-3">
                                                Source
                                            </div>
                                            <div scope="col" class="px-6 py-3">
                                                Stream
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-row-group">
                                        <div
                                            v-for="channel in channels.data"
                                            :key="channel.id"
                                            class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                        >
                                            <div
                                                scope="row"
                                                class="table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                            >
                                                <span>{{ channel.name }}</span>

                                            </div>
                                            <div
                                                scope="row"
                                                class="md:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                            >
                                                <span>{{ channel.video }}</span>
                                            </div>
                                            <div class="table-cell px-6 py-4">
                                                <span>{{ hasChannelSource(channel) }}</span>
                                            </div>
                                            <div class="table-cell px-6 py-4">
                                                <span>{{ channel.stream }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Paginator -->
                                <Pagination :data="channels" class="pb-6"/>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>


<script setup>
import {onBeforeMount, onMounted, ref, watch} from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import Pagination from "@/Components/Pagination.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import AdminHeader from "@/Components/Admin/AdminHeader.vue";
import throttle from "lodash/throttle";
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

let props = defineProps({
    channels: Object,
    filters: Object,
})

userStore.currentPage = 'adminChannels'
userStore.showFlashMessage = true;

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

// function hasChannelSource (channel) {
//     if (channel.source !== null) {
//         return channel.source.name
//     }
// }

function hasChannelSource(channel) {
    if (channel && channel.source && channel.source.name) {
        return channel.source.name;
    }
    return null; // Or return any other default value if needed
}

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/admin/channels', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

</script>
