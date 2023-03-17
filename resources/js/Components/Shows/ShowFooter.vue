<template>

    <div class="flex justify-end mt-6 mb-24">
        <!-- Paginator -->
        <!--                            <Pagination :links="`#`" class="mt-6"/>-->
        <Link :href="`/teams/${team.slug}`" class="text-blue-500 ml-2 uppercase"> {{ team.name }} © 2022 </Link>
    </div>

</template>

<script setup>

defineProps({
    team: Object,
})

</script>
