<template>

    <Head :title="props.show.name" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white rounded text-black p-5 mb-10">

            <ShowHeader />

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <ShowEpisodesList />

<!--                            <table class="min-w-full divide-y divide-gray-200">-->
<!--                                <tbody class="bg-white divide-y divide-gray-200">-->
<!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
<!--                                    <td class="px-6 py-4 whitespace-nowrap">-->
<!--                                        <div class="flex items-center">-->
<!--                                            <div>-->
<!--                                                <div class="text-sm font-medium text-gray-900">-->
<!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </td>-->

<!--                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">-->
<!--                                        <Link :href="`/admin/users/edit/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">Edit</Link>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                                </tbody>-->
<!--                            </table>-->

<!--                            &lt;!&ndash; Paginator &ndash;&gt;-->
<!--                            <Pagination :links="episode.links" class="mt-6"/>-->

                        </div>
                        <ShowFooter />
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import ShowHeader from "@/Components/Shows/ShowHeader"
import ShowEpisodesList from "@/Components/Shows/ShowEpisodesList"
import ShowFooter from "@/Components/Shows/ShowFooter"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let props = defineProps({
    show: Object,
    episodes: Object
});

let show = useShowStore();
show.fill();

</script>


