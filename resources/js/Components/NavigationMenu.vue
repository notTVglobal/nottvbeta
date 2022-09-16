<template>
    <nav class="sticky top-0 bg-black border-b border-gray-100 z-50">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8 z-50">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link @click="videoPlayer.makeVideoTopRight()" :href="route('dashboard')">
                            <JetApplicationMark class="block h-9 w-auto"/>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex">
                        <JetNavLink @click="videoPlayer.makeVideoFullPage() && videoPlayer.videoContainerClassFullPage()" :href="route('stream')" :active="route().current('stream')">
                            Stream
                        </JetNavLink>
                        <JetNavLink @click="videoPlayer.makeVideoTopRight()" :href="route('schedule')" :active="route().current('schedule')">
                            Schedule
                        </JetNavLink>
                        <JetNavLink @click="videoPlayer.makeVideoTopRight()" :href="route('posts')" :active="route().current('posts')">
                            Posts
                        </JetNavLink>
                        <JetNavLink @click="videoPlayer.makeVideoFullPage()" :href="route('channels')" :active="route().current('channels')">
                            Channels
                        </JetNavLink>
                        <JetNavLink @click="videoPlayer.makeVideoTopRight()" :href="route('movies')" :active="route().current('movies')">
                            Movies
                        </JetNavLink>
                        <JetNavLink @click="videoPlayer.makeVideoTopRight()" :href="route('shows')" :active="route().current('shows')">
                            Shows
                        </JetNavLink>
                        <JetNavLink @click="videoPlayer.makeVideoTopRight()" :href="route('shop')" :active="route().current('shop')">
                            Shop
                        </JetNavLink>
<!--                        <ChatToggle v-model:checked="chat.toggleShowChatOn" label="Chat" />-->
                    </div>
                </div>

                <div class="hidden lg:flex lg:items-center lg:ml-6 z-50">

                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative z-50">
                        <JetDropdown align="right" width="48">
                            <template #trigger>
                                <button v-if="$page.props.jetstream.managesProfilePhotos"
                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                         :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                                </button>

                                <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                            </template>

                            <template #content>
                                <div class="divide-y">
<!--                                    <div class="pb-3">-->
<!--                                        &lt;!&ndash; Teams Links &ndash;&gt;-->
<!--                                        &lt;!&ndash; Need to add v-if="$page.props.jetstream.hasTeamFeatures in a template tag &ndash;&gt;-->
<!--                                        <div class="block px-4 py-2 text-xs text-gray-400">-->
<!--                                            My Teams-->
<!--                                        </div>-->

<!--                                        <JetDropdownLink-->
<!--                                            @click="videoPlayer.makeVideoTopRight()"-->
<!--                                            :href="`/teams/1`">-->
<!--                                                  notTV Founders-->
<!--                                        </JetDropdownLink>-->
<!--                                    </div>-->
                                    <div class="pt-2 pb-3">
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>

                                        <JetDropdownLink
                                            @click="videoPlayer.makeVideoTopRight()"
                                            :href="route('dashboard')">
                                            Dashboard
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            @click="videoPlayer.makeVideoTopRight()"
                                            :href="route('training')">
                                            Training
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            @click="videoPlayer.makeVideoTopRight()"
                                            :href="route('profile.show')">
                                            Settings
                                        </JetDropdownLink>

                                    </div>
                                    <div class="border-t border-gray-100">
                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">

                                            <JetDropdownLink as="button">
                                                Log Out
                                            </JetDropdownLink>
                                        </form>
                                    </div>
                                </div>
                            </template>
                        </JetDropdown>
                    </div>
                </div>



            </div>
        </div>
    </nav>
</template>

<script setup>
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import { Link } from '@inertiajs/inertia-vue3';
import ChatToggle from "@/Components/Chat/ChatToggle.vue";
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import {Inertia} from "@inertiajs/inertia";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import { useChatStore } from "@/Stores/ChatStore.js";

let chat = useChatStore();
let videoPlayer = useVideoPlayerStore();

const logout = () => {
    videoPlayer.fullPage = true;
    videoPlayer.loggedIn = false;
    videoPlayer.class = "videoBgFull";
    videoPlayer.videoContainerClass = "videoContainerBgFull";
    chat.class = "chatHidden";
    chat.showChat = false;
    Inertia.post(route('logout'));
};

</script>
