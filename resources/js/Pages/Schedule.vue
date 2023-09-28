<template>
    <Head title="Schedule" />

    <div class="place-self-center flex flex-col gap-y-3 w-full overscroll-x-none pb-8">
        <div id="topDiv" class="flex justify-end px-5">
            <div class="text-3xl font-semibold pt-4">Schedule</div>
        </div>
        <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>
        <div class="mx-6">
            <div class="alert alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>This section is in development. Not currently working.</span>
            </div>
        </div>
        <ul class="w-64 ml-12 my-8">
            <li class="p-2 bg-green-800">Scheduled Shows</li>
            <li class="p-2 bg-purple-800">New Releases</li>
            <li class="p-2 bg-blue-800">Live Events</li>
            <li class="p-2 bg-yellow-800">News</li>
<!--            <li class="p-2"><font-awesome-icon icon="fa-leaf" class="text-red-600 text-3xl pr-2"/> Canadian Content</li>-->
<!--            <li class="p-2"><font-awesome-icon icon="fa-flag-usa" class="text-red-600 text-3xl pr-2"/> American Content</li>-->

        </ul>

        <div class="ml-5 px-2">
            <span class="text-sm uppercase text-purple-500">All times are listed in your timezone.</span>
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
                    <td class="p-2 bg-gray-900 text-center">LIVE</td>
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
                        <span>New Release</span>
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
                    <td class="p-2 bg-gray-900 text-center">AMBIENT</td>
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
                    <td class="p-2 bg-gray-900 text-center">TALK</td>
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
                    <td colspan="2" class="p-2 hidden lg:table-cell bg-yellow-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-yellow-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>News Program</span>
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
                    <td class="p-2 bg-gray-900 text-center">DOCUMENTARY</td>
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
                    <td class="p-2 bg-gray-900 text-center">ANIMATED</td>
                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Scheduled Show</span>
                        </div>
                    </td>
                    <td class="p-2 bg-blue-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
                        <div class="flex flex-col">
                            <span>Live Event</span>
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

userStore.currentPage = 'schedule';
userStore.showFlashMessage = true;

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

let props = defineProps({
    can: Object,
})

// chat.class = "chatSmall"


</script>

