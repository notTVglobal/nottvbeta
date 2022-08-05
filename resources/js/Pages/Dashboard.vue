<template>
    <Head title="Dashboard" />

    <div class="place-self-center flex flex-col gap-y-3 mr-96">
        <div class="bg-white text-black p-5 mb-10">

            <h1 class="text-3xl font-semibold pb-3">Dashboard</h1>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 my-3 m-auto p-1 bg-gray-200 text-black">
                <div class="p-1">
                    <h2 class="font-semibold text-xl">Assignments</h2>
                    <p class="mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
                <div class="p-1">
                    <h2 class="font-semibold text-xl">Shows</h2>
                    <p class="mt-1">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
                <div class="p-1">
                    <h2 class="font-semibold text-xl">Teams</h2>
                    <p class="mt-1">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
                </div>
                <div class="p-1">
                    <h2 class="font-semibold text-xl">Upcoming Events</h2>
                    <p class="mt-1">"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                </div>
                <div class="p-1">
                    <h2 class="font-semibold text-xl">Messages</h2>
                    <p class="mt-1">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                </div>
                <div class="p-1">
                    <h2 class="font-semibold text-xl">Accounts</h2>
                    <p class="mt-1">Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
                </div>
            </section>

            <section class="grid grid-cols-1 mt-6 gap-2">
                <div class="font-semibold text-2xl text-blue-400 px-2">
                    Account Summary
                </div>
                <div class="px-2">
                    Account: 000000
                </div>
                <div class="border-2 pb-3">
                    <h2 class="bg-gray-800 text-white text-sm p-2">Membership: 000000</h2>
                    <h2 class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">Account Name</h2>
                    <p class="px-2">Chequing</p>
                    <p class="px-2">Equity Shares</p>
                    <p class="px-2">Savings</p>

                    <h2 class="bg-blue-400 font-semibold text-sm text-black my-3 px-2">Team Accounts</h2>
                    <p class="px-2">notTV Founders Shares</p>
                </div>
            </section>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"
// onload(videoPlayer.class = "videoTopRight")
</script>

<script>
export default {
    window:onload = function() {
        videoPlayer.class = "videoTopRight";
    }
}
</script>

