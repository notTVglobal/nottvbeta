<template>
    <!-- Hamburger -->
    <div class="-mr-2 flex items-center sm:hidden z-50">
        <button
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition"
            @click="showingNavigationDropdown = ! showingNavigationDropdown">
            <svg
                class="h-6 w-6"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 24 24"
            >
                <path
                    :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                    :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>
    </div>
    <!-- Responsive Navigation Menu -->
    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
         class="sm:hidden bg-gray-800 text-gray-50 mb-5">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                    <img class="h-10 w-10 rounded-full object-cover"
                         :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                </div>

                <div>
                    <div class="font-medium text-base text-gray-100">
                        {{ $page.props.user.name }}
                    </div>
                    <div class="font-medium text-sm text-gray-100">
                        {{ $page.props.user.email }}
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1 z-50">

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('dashboard')"
                                      :active="route().current('dashboard')">
                    Dashboard
                </JetResponsiveNavLink>
                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('stream')"
                                      :active="route().current('stream')"

                >
                    Stream
                </JetResponsiveNavLink>
                <JetResponsiveNavLink @click="videoPlayer.makeVideoFullPage()"
                                      :href="route('image')"
                                      :active="route().current('image')"

                >
                    Image Uploader
                </JetResponsiveNavLink>
                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('video')"
                                      :active="route().current('video')"

                >
                    Video
                </JetResponsiveNavLink>
                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('shows')"
                                      :active="route().current('shows')"

                >
                    Shows
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('training')"
                                      :active="route().current('training')">
                    Training
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                    Profile
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      v-if="$page.props.jetstream.hasApiFeatures"
                                      :href="route('api-tokens.index')"
                                      :active="route().current('api-tokens.index')">
                    API Tokens
                </JetResponsiveNavLink>

                <!-- Authentication -->
                <form method="POST" @submit.prevent="logout">
                    <JetResponsiveNavLink as="button">
                        Log Out
                    </JetResponsiveNavLink>
                </form>

                <!-- Team Management -->
                <template v-if="$page.props.jetstream.hasTeamFeatures">
                    <div class="border-t border-gray-200"/>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Manage Team
                    </div>

                    <!-- Team Settings -->
                    <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                          :href="route('teams.show', $page.props.user.current_team)"
                                          :active="route().current('teams.show')">
                        Team Settings
                    </JetResponsiveNavLink>

                    <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                          v-if="$page.props.jetstream.canCreateTeams"
                                          :href="route('teams.create')"
                                          :active="route().current('teams.create')">
                        Create New Team
                    </JetResponsiveNavLink>

                    <div class="border-t border-gray-200"/>

                    <!-- Team Switcher -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Switch Teams
                    </div>

                    <template v-for="team in $page.props.user.all_teams" :key="team.id">
                        <form @submit.prevent="switchToTeam(team)">
                            <JetResponsiveNavLink as="button">
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
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>{{ team.name }}</div>
                                </div>
                            </JetResponsiveNavLink>
                        </form>
                    </template>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import { ref } from "vue";

const showingNavigationDropdown = ref(false);

let videoPlayer = useVideoPlayerStore();
</script>
