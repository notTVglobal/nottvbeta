<template>
    <Head title="Users" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

            <AdminHeader>Users</AdminHeader>

            <div class="flex flex-row justify-between gap-x-4">
                <button
                    @click="appSettingStore.btnRedirect(`/users/create`)"
                    class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg disabled:bg-gray-400"
                >Add User</button>
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
                                        <Pagination :data="users" class="mb-6"/>

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
                                                    <span v-if="user.isSubscriber" class="ml-2 bg-green-700 text-white text-xs font-bold rounded-lg py-1 px-2">Premium</span>
                                                    <span v-if="user.isAdmin" class="ml-2 bg-red-800 text-white text-xs font-bold rounded-lg py-1 px-2">Admin</span>
                                                    <span v-if="user.isNewsPerson" class="ml-2 bg-orange-600 text-white text-xs font-bold rounded-lg py-1 px-2">News Team</span>
                                                    <span v-if="user.isVip" class="ml-2 bg-purple-700 text-white text-xs font-bold rounded-lg py-1 px-2">VIP</span>

                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    {{ user.email }}
                                                </th>
                                                <td class="px-6 py-4">
<!--                                                    <span v-if="user.isSubscriber">Premium</span>-->
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
                                        <Pagination :data="users" class="mt-6"/>
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
import { router } from '@inertiajs/vue3'
import { ref, watch } from "vue"

import { useForm } from "@inertiajs/vue3"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import Pagination from "@/Components/Global/Paginators/Pagination"
import throttle from "lodash/throttle"
import Message from "@/Components/Global/Modals/Messages"
import AdminHeader from "@/Components/Pages/Admin/AdminHeader"

usePageSetup('users')

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()

let props = defineProps({
    users: Object,
    filters: Object,
    can: Object,
});

let search = ref(props.filters.search);

const form = useForm({
    userId: '',
})

watch(search, throttle(function (value) {
    router.get('/users', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

function deleteUser($user) {
    if(confirm('Are you sure you want to delete ' + $user.name +'? This action is not reversible and may have' +
        ' devastating effects on the database.')) {
        router.post('/admin/user/delete', {'userId': $user.id});
    }
}

</script>
