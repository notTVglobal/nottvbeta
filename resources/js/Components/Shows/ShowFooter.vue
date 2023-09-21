<template>

    <div class="flex justify-end mt-6 pb-24">
        <!-- Paginator -->
        <!--                            <Pagination :links="`#`" class="mt-6"/>-->
        <Link :href="`/teams/${team.slug}`" class="text-blue-500 ml-2 uppercase"> {{ team.name }} ©
            <span v-if="show.last_release_year" >{{ show.first_release_year }}-{{show.last_release_year}}</span>
            <span v-if="!show.last_release_year">{{show.first_release_year}}</span>
        </Link>
    </div>

</template>

<script setup>

defineProps({
    team: Object,
    show: Object,
})

let thisYear = new Date().getFullYear()

</script>
