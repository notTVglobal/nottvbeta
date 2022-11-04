<template>
    <Head title="Users" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between">
                <h1 class="text-3xl font-semibold pb-3">Users</h1>
                <Link :href="`/dashboard`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Dashboard</button>
                </Link>
            </div>

            <div class="flex flex-row justify-between gap-x-4">
                <Link :href="`/users/create`"><button
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"
                >Add User</button>
                </Link>
                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
            </div>


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <div
                                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                                role="alert"
                                v-if="props.message"
                            >
                    <span class="font-medium">
                        {{props.message}}
                    </span>
                            </div>
                            {{ props.users.teams }}

                            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                                <div class="p-6 bg-white border-b border-gray-200">
                                    <div
                                        class="relative overflow-x-auto shadow-md sm:rounded-lg"
                                    >
                                        <!-- Paginator -->
                                        <Pagination :links="users.links" class="mb-6"/>

                                        <table
                                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                                        >
                                            <thead
                                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                            >
                                            <tr>
                                                <th scope="col" class="min-w-[8rem] px-6 py-3">
                                                    Avatar
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Name
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Email
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Role
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Edit
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr
                                                v-for="user in users.data"
                                                :key="user.id"
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                            >
                                                <th
                                                    scope="row"
                                                    class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    <img v-if="!user.profile_photo_path" :src="user.userSelected.profile_photo_url" class="rounded-full h-20 w-20 object-cover">
                                                    <img v-if="user.profile_photo_path" :src="`/storage/${user.profile_photo_path}`" class="rounded-full h-20 w-20 object-cover">
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    <Link :href="`/users/${user.id}`" class="text-blue-800 hover:text-blue-600">{{ user.name }}</Link>
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    {{ user.email }}
                                                </th>
                                                <td class="px-6 py-4">
                                                    {{ user.role }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <Link :href="`/users/${user.id}/edit`"><button
                                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                                    >Edit</button>
                                                    </Link>
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
            </div>
        </div>
    </div>

</template>


<script setup>
import Pagination from "@/Components/Pagination"
import {onMounted, ref, watch} from "vue"
import {Inertia} from "@inertiajs/inertia"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import {useForm} from "@inertiajs/inertia-vue3";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    users: Object,
    filters: Object,
    can: Object,
    message: String
});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/users', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));
</script>
