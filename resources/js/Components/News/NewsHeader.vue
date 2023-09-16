<template>

    <div class="container mx-auto flex flex-col lg:flex-row items-center justify-start px-4 py-6 mb-3 border-b border-gray-500">
<!--    <div class="flex flex-col lg:flex-row items-center">-->
        <h1 class="text-3xl font-semibold text-center lg:text-left"><slot /></h1>
        <ul class="flex ml-0 lg:ml-16 mt-6 mr-6 lg:mt-0 space-x-8" >
            <li>
                <Link :href="``" class="text-gray-700 cursor-not-allowed hover:text-blue-500">Stories</Link>
            </li>
            <li>
                <Link :href="``" class="text-gray-700 cursor-not-allowed hover:text-blue-500">Categories</Link>
            </li>
            <li>
                <Link :href="``" class="text-gray-700 cursor-not-allowed hover:text-blue-500">Cities</Link>
            </li>
            <li>
                <Link :href="``" class="text-gray-700 cursor-not-allowed hover:text-blue-500">Provinces</Link>
            </li>
            <li>
                <Link :href="`/rss2`" class="text-gray-50 hover:text-blue-500">News Feeds</Link>
            </li>
        </ul>
    </div>

</template>

<script setup>

</script>
