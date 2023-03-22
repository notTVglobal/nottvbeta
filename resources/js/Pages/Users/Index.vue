<template>
    <Head title="Users" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">

            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
                v-if="props.message"
            >
                    <span class="font-medium">
                        {{props.message}}
                    </span>
            </div>

            <div class="flex justify-between pt-4">
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
                <input v-model="search" type="search" placeholder="Search..." class="text-black border px-2 rounded-lg" />
            </div>


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            {{ props.users.teams }}

                            <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200">
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
                                                    <Link :href="`/users/${user.id}`" class="">
                                                        <img v-if="!user.profile_photo_path" :src="user.userSelected.profile_photo_url" class="rounded-full h-20 w-20 object-cover">
                                                        <img v-if="user.profile_photo_path" :src="`/storage/${user.profile_photo_path}`" class="rounded-full h-20 w-20 object-cover">
                                                    </Link>
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    <Link :href="`/users/${user.id}`" class="text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400">{{ user.name }}</Link>
                                                    <span v-if="user.isAdmin" class="ml-2 bg-red-800 text-white text-xs font-bold rounded-lg py-1 px-2">Admin</span>
                                                    <span v-if="user.isNewsPerson" class="ml-2 bg-orange-600 text-white text-xs font-bold rounded-lg py-1 px-2">News Team</span>
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    {{ user.email }}
                                                </th>
                                                <td class="px-6 py-4">
                                                    <span>{{ user.role }}</span>
                                                </td>
                                                <td class="px-6 py-4 space-x-2">
                                                    <Link :href="`/users/${user.id}/edit`"><button
                                                        class="px-4 py-2 mb-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                                    >Edit</button>
                                                    </Link>
                                                    <Link :href="`#`"><button
                                                        @click.prevent="deleteUser(user)"
                                                        class="px-4 py-2 mb-2 text-white bg-red-600 hover:bg-red-500 rounded-lg"
                                                    >Delete</button>
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
import {onBeforeMount, onMounted, ref, watch} from "vue"
import {Inertia} from "@inertiajs/inertia"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import {useUserStore} from "@/Stores/UserStore";
import {useForm} from "@inertiajs/inertia-vue3";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'users'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    users: Object,
    filters: Object,
    can: Object,
    message: String
});

const form = useForm({
    userId: '',
})

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/users', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

function deleteUser($user) {
    if(confirm('Are you sure you want to delete ' + $user.name +'? This action is not reversible and may have' +
        ' devastating effects on the database.')) {
        Inertia.post('/admin/user/delete', {'userId': $user.id});
    }
}

</script>
