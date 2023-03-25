<template>

    <div class="flex justify-end mt-6">
        <!-- Paginator -->
        <!--                            <Pagination :links="`#`" class="mt-6"/>-->
        <Link :href="`/teams/${team.slug}`" class="text-blue-500 hover:text-blue-700 ml-2 uppercase"> {{ team.name }} © 2022 </Link>
    </div>

</template>

<script setup>

defineProps({
    team: Object,
    episode: Object
})

</script>
