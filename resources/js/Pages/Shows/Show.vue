<template>

    <Head :title="props.show.name" />
    <div class="flex m-auto mt-10 mb-10 w-1/2">
        <div class="bg-white rounded text-black p-5 mb-10">
            <div class="flex justify-between mb-6">
                <h1 class="text-2xl pb-3">{{props.show.name}}</h1>
                <Link href="/shows" class="text-blue-500 text-sm ml-2">Go back</Link>
            </div>
            <p class="mb-6">
                {{ props.show.description }}
            </p>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <div class="bg-orange-300">
                                Episodes list. New features will be added to sort, search and browse episodes.
                            </div>

<!--                            <table class="min-w-full divide-y divide-gray-200">-->
<!--                                <tbody class="bg-white divide-y divide-gray-200">-->
<!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
<!--                                    <td class="px-6 py-4 whitespace-nowrap">-->
<!--                                        <div class="flex items-center">-->
<!--                                            <div>-->
<!--                                                <div class="text-sm font-medium text-gray-900">-->
<!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </td>-->

<!--                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">-->
<!--                                        <Link :href="`/admin/users/edit/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">Edit</Link>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                                </tbody>-->
<!--                            </table>-->

<!--                            &lt;!&ndash; Paginator &ndash;&gt;-->
<!--                            <Pagination :links="episode.links" class="mt-6"/>-->

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<script setup>
let props = defineProps({
    show: Object,
    episodes: Object
});
</script>


