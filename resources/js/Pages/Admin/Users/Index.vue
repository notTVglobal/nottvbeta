<template>
    <Head title="Users Admin" />

    <div class="place-self-center flex flex-col gap-y-3 pageWidth">
        <div class="bg-white text-black p-5 mb-10">
            <div class="flex justify-between mb-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-semibold">Users</h1>

                    <Link href="/admin/users/create" class="text-blue-500 text-sm ml-2">New User</Link>
                </div>

                    <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
            </div>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <!-- Paginator -->
                            <Pagination :links="users.links" class="mb-2"/>
                        <div class="bg-orange-300">
                            Only users who are creators should have a clickable name
                            which goes to their creator profile page.
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users.data" :key="user.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <Link :href="`/admin/users/${user.id}`" class="text-indigo-600 hover:text-indigo-900">{{ user.name }}</Link>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="`/admin/users/edit/${user.id}`" class="text-indigo-600 hover:text-indigo-900">Edit</Link>
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
import Pagination from "@/Components/Pagination";
import { ref, watch } from "vue";
import {Inertia} from "@inertiajs/inertia";
import throttle from "lodash/throttle";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"
videoPlayer.fullPage = false

let props = defineProps({
    users: Object,
    filters: Object,
});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/admin/users', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));
</script>
