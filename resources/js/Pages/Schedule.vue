<template>
    <Head title="Schedule" />

    <div class="place-self-center flex flex-col gap-y-3 w-full overscroll-x-none pb-8">
        <div id="topDiv" class="flex justify-between p-5 mb-5">



            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div class="text-3xl font-semibold pt-4">Schedule</div>

        </div>
        <div class="ml-5 mb-5 text-red-600">This section is in development. Not currently working.</div>
        <ul class="w-64 ml-12 mb-8">
            <li class="p-2 bg-green-800">Scheduled Shows</li>
            <li class="p-2 bg-purple-800">New Releases</li>
            <li class="p-2 bg-blue-800">Live Events</li>
            <li class="p-2 bg-yellow-800">News</li>
<!--            <li class="p-2"><font-awesome-icon icon="fa-leaf" class="text-red-600 text-3xl pr-2"/> Canadian Content</li>-->
<!--            <li class="p-2"><font-awesome-icon icon="fa-flag-usa" class="text-red-600 text-3xl pr-2"/> American Content</li>-->

        </ul>

        <div class="ml-5 mb-5 p-2">
            <span class="text-sm uppercase">All times are listed in your timezone.</span><br>
                <span>If you use a VPN you can set your preferred timezone in the settings menu.</span>
        </div>

        <table class="table-fixed mx-5">
            <thead class="bg-gray-900">
                <tr class="border-b border-0.5 border-white">
                    <th class="p-2">CHANNEL</th>
                    <th class="p-2 border-x border-0.5 border-white">10:00</th>
                    <th class="p-2 border-x border-0.5 border-white">10:30</th>
                    <th class="p-2 border-x border-0.5 border-white">11:00</th>
                    <th class="p-2 hidden lg:table-cell border-x border-0.5 border-white">11:30</th>
                    <th class="p-2 hidden xl:table-cell border-x border-0.5 border-white">12:00</th>
                    <th class="p-2 hidden xl:table-cell">12:30</th>
                    <th class="p-2 hidden 2xl:table-cell border-x border-0.5 border-white">1:00</th>
                    <th class="p-2 hidden 2xl:table-cell">1:30</th>

                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-0.5 border-white">
                    <td class="p-2 bg-gray-900">ONE</td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
<!--                            <span class="text-right"><font-awesome-icon icon="fa-leaf" class="text-red-600 text-xs text-right"/></span>-->
                        </div>
                    </td>
                    <td colspan="2" class="p-2 mx-4 bg-yellow-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-yellow-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>News Program</span>
                        </div>
                    </td>

                    <td class="p-2 hidden lg:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>

                </tr>
                <tr class="border-b border-0.5 border-white">
                    <td class="p-2 bg-gray-900">PACIFIC</td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-purple-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>New Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden lg:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>

                </tr>
                <tr class="border-b border-0.5 border-white">
                    <td class="p-2 bg-gray-900">WEST</td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden lg:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                        <span>Scheduled Show</span>
                        </div>
                    </td>

                </tr>
                <tr class="border-b border-0.5 border-white">
                    <td class="p-2 bg-gray-900">CENTRAL</td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden lg:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>

                </tr>
                <tr class="border-b border-0.5 border-white">
                    <td class="p-2 bg-gray-900">ONTARIO</td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden lg:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>

                </tr>
                <tr class="border-b border-0.5 border-white">
                    <td class="p-2 bg-gray-900">EAST</td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden lg:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>

                </tr>
                <tr class="border-b border-0.5 border-white">
                    <td class="p-2 bg-gray-900">U.S.A.</td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden lg:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>

                </tr>
                <tr class="border-b border-0.5 border-white">
                    <td class="p-2 bg-gray-900">WORLD</td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden lg:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>

                </tr>

            </tbody>

        </table>
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore.js"
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'schedule';

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
    }
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    can: Object,
    message: String,
})

// chat.class = "chatSmall"

let showMessage = ref(true);

</script>

