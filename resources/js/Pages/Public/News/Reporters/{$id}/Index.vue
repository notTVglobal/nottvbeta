<template>

    <div>
        <PublicNavigationMenu class="fixed top-0 w-full nav-mask" />
    </div>

    <div class="px-6 py-4">
        <div class="bg-gray-200 mx-auto p-5 my-10 h-full w-full text-gray-900 rounded">
            <div class="bg-white text-gray-900">Public News Reporter Profile Page</div>

            <div
                class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
            >
                <Link :href="`/users/${$page.props.newsPerson.id}`" class="">
                    <img v-if="$page.props.newsPerson.profile_photo_path" :src="`/storage/${$page.props.newsPerson.profile_photo_path}`" class="rounded-full h-20 w-20 object-cover">
                </Link>
            </div>
            <div class="text2xl">{{$page.props.newsPerson.name}}</div>
        </div>
    </div>



</template>

<script setup>
import PublicNavigationMenu from "@/Components/Navigation/PublicNavigationMenu.vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

userStore.showNavDropdown = false
</script>

<script>
import NoLayout from '@/Layouts/NoLayout';

export default {
    layout: NoLayout,
}
</script>
