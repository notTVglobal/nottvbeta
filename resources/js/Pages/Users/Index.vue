<template>
    <Head title="Users" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">
            <div class="flex justify-between mb-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-semibold">Users</h1>

                    <Link v-if="can.createUser" href="/users/create" class="text-blue-500 text-sm ml-2">New User</Link>
                </div>

                <div>
                    <Link href="/dashboard" class="text-blue-500 text-sm ml-2">Dashboard</Link>
                    <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
                </div>
            </div>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <!-- Paginator -->
                            <Pagination :links="users.links" class="mb-2"/>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                            <td>
                                Name
                            </td>
                            <td>
                                Role
                            </td>
                            <td>
                            </td>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users.data" :key="user.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <Link :href="`/users/${user.id}`" class="text-indigo-600 hover:text-indigo-900">{{ user.name }}</Link>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ user.role_id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td v-if="user.can.edit" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="`/users/${user.id}/edit`" class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <!-- Paginator -->
                        <Pagination :links="users.links" class="mt-6"/>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>


<script setup>
import Pagination from "@/Components/Pagination"
import { ref, watch } from "vue"
import {Inertia} from "@inertiajs/inertia"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import {useForm} from "@inertiajs/inertia-vue3";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let props = defineProps({
    users: Object,
    filters: Object,
    can: Object
});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/users', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));
</script>
