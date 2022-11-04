<template>
    <nav class="sticky top-0 bg-black border-b border-gray-100 z-50">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-50">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link @click="videoPlayer.makeVideoTopRight()" :href="route('dashboard')">
                            <JetApplicationMark class="block h-9 w-auto"/>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <JetNavLink @click="videoPlayer.makeVideoFullPage() && videoPlayer.videoContainerClassFullPage()" :href="route('stream')" :active="route().current('stream')">
                            Stream
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
                        <JetNavLink @click="videoPlayer.makeVideoTopRight()" :href="route('video')" :active="route().current('video')">
                            MistServer API
                        </JetNavLink>
                        <JetNavLink @click="videoPlayer.makeVideoTopRight()" :href="route('shop')" :active="route().current('shop')">
                            Shop
                        </JetNavLink>
                        <JetNavLink @click="videoPlayer.makeVideoTopRight()" :href="route('schedule')" :active="route().current('schedule')">
                            Schedule
                        </JetNavLink>
                        <ChatToggle v-model:checked="chat.show" label="Chat" />
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6 z-50">
                    <div class="ml-3 relative">
                        <!-- Teams Dropdown -->
                        <JetDropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                            <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                                {{ $page.props.user.current_team.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd"
                                                          d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                            </template>

                            <template #content>
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <template v-if="$page.props.jetstream.hasTeamFeatures">
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Team
                                        </div>

                                        <!-- Team Settings -->
                                        <JetDropdownLink
                                            @click="videoPlayer.makeVideoTopRight()"
                                            :href="route('teams.show', $page.props.user.current_team)">
                                            Team Settings
                                        </JetDropdownLink>

                                        <JetDropdownLink v-if="$page.props.jetstream.canCreateTeams"
                                                         @click="videoPlayer.makeVideoTopRight()"
                                                         :href="route('teams.create')">
                                            Create New Team
                                        </JetDropdownLink>

                                        <div class="border-t border-gray-100"/>

                                        <!-- Team Switcher -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Switch Teams
                                        </div>

                                        <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                            <form @submit.prevent="switchToTeam(team)">
                                                <JetDropdownLink as="button">
                                                    <div class="flex items-center">
                                                        <svg
                                                            v-if="team.id == $page.props.user.current_team_id"
                                                            class="mr-2 h-5 w-5 text-green-400"
                                                            fill="none"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <div>{{ team.name }}</div>
                                                    </div>
                                                </JetDropdownLink>
                                            </form>
                                        </template>
                                    </template>
                                </div>
                            </template>
                        </JetDropdown>
                    </div>

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
                                    <div class="pb-3">
                                        <!-- Administrator Links -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Administrator Links
                                        </div>

                                        <JetDropdownLink
                                            @click="videoPlayer.makeVideoTopRight()"
                                            :href="route('admin.channels.index')">
                                            Channels
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            @click="videoPlayer.makeVideoTopRight()"
                                            :href="route('teams.index')">
                                            Teams
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            @click="videoPlayer.makeVideoTopRight()"
                                            :href="route('admin.users.index')">
                                            Users
                                        </JetDropdownLink>
                                    </div>
                                    <div class="pb-3">
                                        <!-- Teams Links -->
                                        <!-- Need to add v-if="$page.props.jetstream.hasTeamFeatures in a template tag -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            My Teams
                                        </div>

                                        <JetDropdownLink
                                            @click="videoPlayer.makeVideoTopRight()"
                                            :href="`/teams/1`">
                                                  notTV Founders
                                        </JetDropdownLink>
                                    </div>
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
                                            Profile
                                        </JetDropdownLink>

                                        <JetDropdownLink v-if="$page.props.jetstream.hasApiFeatures"
                                                         @click="videoPlayer.makeVideoTopRight()"
                                                         :href="route('api-tokens.index')">
                                            API Tokens
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

const switchToTeam = (team) => {
    Inertia.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    Inertia.post(route('logout'));
};

</script>
