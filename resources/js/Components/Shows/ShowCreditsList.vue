<template>
<div class="bg-orange-300 p-2 font-bold">Credits</div>
    <Link
        :href="`#`"><button
        class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
    >Create Assignment</button></Link>
    <div class="p-2">This section is in development.</div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div>
                        <div class="text-sm font-medium text-gray-900 pl-14">
                            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
                            Name
                        </div>
                    </div>
                </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Role
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Completed Assignments
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Total Credits
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                Status
            </td>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <!--  INSERT CREDITS HERE  -->
        </tbody>

    </table>

</template>

<script setup>


</script>
