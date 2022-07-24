<template>
    <Head title="Creators" />

    <div class="bg-white rounded text-black p-5 mb-10 py-20 w-3/4">
        <div class="flex justify-between mb-6">
            <div class="flex items-center">
                <h1 class="text-3xl">Creators</h1>

                <Link href="/creators/create" class="text-blue-500 text-sm ml-2">New Creator</Link>
            </div>
            <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
        </div>

        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="creator in creators.data" :key="creator.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <Link :href="`/creators/${creator.id}`" class="text-indigo-600 hover:text-indigo-900">{{ creator.name }}</Link>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="`/creators/edit/${creator.id}`" class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <!-- Paginator -->
                        <Pagination :links="creators.links" class="mt-6"/>

                    </div>
                </div>
            </div>
        </div>


        <div class="flex items-center">
            <!--               <Link :href="`/shows/${show.id}`" class="text-indigo-600 hover:text-indigo-900">Link to a show</Link>-->

        </div>
    </div>

</template>

<script setup>
import Pagination from "@/Components/Pagination";
import { ref, watch } from "vue";
import {Inertia} from "@inertiajs/inertia";
import throttle from "lodash/throttle";

let props = defineProps({
    creators: Object,
    filters: Object,
});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/shows', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));
</script>


