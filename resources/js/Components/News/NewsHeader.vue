<template>

    <div class="w-full mx-auto mb-3 pb-3 border-b border-gray-500 pt-6">


        <div class="w-full mx-auto flex flex-wrap justify-between mb-3 pb-3 gap-2">

            <div>
                <h1 class="text-3xl font-semibold text-center lg:text-left"><slot /></h1>
            </div>
            <div>
                <NewsHeaderButtons :can="can"/>
            </div>

        </div>

        <div>
            <ul class="flex flex-wrap ml-0 lg:ml-16 mt-6 mr-6 lg:mt-0 space-x-8" >
                <li>
                    <button disabled class="text-gray-700 dark:text-gray-50 disabled:text-gray-300 disabled:cursor-not-allowed hover:text-blue-500">Stories</button>
                </li>
                <li>
                    <button disabled class="text-gray-700 dark:text-gray-50 disabled:text-gray-300 disabled:cursor-not-allowed hover:text-blue-500">Categories</button>
                </li>
                <li>
                    <button disabled class="text-gray-700 dark:text-gray-50 disabled:text-gray-300 disabled:cursor-not-allowed hover:text-blue-500">Cities</button>
                </li>
                <li>
                    <button disabled class="text-gray-700 dark:text-gray-50 disabled:text-gray-300 disabled:cursor-not-allowed hover:text-blue-500">Provinces</button>
                </li>
                <li>
                    <button
                        @click="userStore.btnRedirect(`/rss2`)"
                        class="text-gray-700 dark:text-gray-50 hover:text-blue-500"
                    >Feeds</button>
                </li>
            </ul>
        </div>

    </div>

</template>

<script setup>
import NewsHeaderButtons from "@/Components/News/NewsHeaderButtons.vue";
import {useUserStore} from "@/Stores/UserStore";

let userStore = useUserStore()

defineProps({
    search: String,
    can: Object,
})
</script>
