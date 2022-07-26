<template>
    <div>
        <JetBanner/>

        <div class="bg-gray-800 text-gray-200">

            <NavigationMenu>
                <ResponsiveNavigationMenu />
            </NavigationMenu>

            <!-- Page Heading -->
                        <header v-if="$slots.header" class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                <slot name="header" />
                            </div>
                        </header>


            <!-- Page Content -->
            <main>
                <VideoPlayer />
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu";
import NavigationMenu from "@/Components/NavigationMenu";
import VideoPlayer from "@/Components/VideoPlayer.vue";
import { Inertia } from '@inertiajs/inertia';
import JetBanner from '@/Jetstream/Banner.vue';

const logout = () => {
    Inertia.post(route('logout'));
};

</script>
