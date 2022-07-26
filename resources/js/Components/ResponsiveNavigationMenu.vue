<template>
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
                    <div class="font-medium text-base text-gray-800">
                        {{ $page.props.user.name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">
                        {{ $page.props.user.email }}
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">

                <JetResponsiveNavLink :href="route('dashboard')"
                                      :active="route().current('dashboard')">
                    Dashboard
                </JetResponsiveNavLink>
                <JetResponsiveNavLink :href="route('stream')"
                                      :active="route().current('stream')"

                >
                    Stream
                </JetResponsiveNavLink>
                <JetResponsiveNavLink :href="route('image')"
                                      :active="route().current('image')"

                >
                    Image Uploader
                </JetResponsiveNavLink>
                <JetResponsiveNavLink :href="route('video')"
                                      :active="route().current('video')"

                >
                    Video
                </JetResponsiveNavLink>
                <JetResponsiveNavLink :href="route('shows')"
                                      :active="route().current('shows')"

                >
                    Shows
                </JetResponsiveNavLink>

                <JetResponsiveNavLink :href="route('training')"
                                      :active="route().current('training')">
                    Training
                </JetResponsiveNavLink>

                <JetResponsiveNavLink :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                    Profile
                </JetResponsiveNavLink>

                <JetResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures"
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
                    <JetResponsiveNavLink :href="route('teams.show', $page.props.user.current_team)"
                                          :active="route().current('teams.show')">
                        Team Settings
                    </JetResponsiveNavLink>

                    <JetResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams"
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
</script>
