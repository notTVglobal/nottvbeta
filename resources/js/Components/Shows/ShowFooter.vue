<template>

    <div class="flex justify-end mt-6 mb-24">
        <!-- Paginator -->
        <!--                            <Pagination :links="`#`" class="mt-6"/>-->
        <Link :href="`/teams/${team.slug}`" class="text-blue-500 ml-2 uppercase"> {{ team.name }} ©
            <span v-if="show.copyrightYear != thisYear">{{ show.copyrightYear }}-{{thisYear}}</span>
            <span v-if="show.copyrightYear == thisYear">{{thisYear}}</span>
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
