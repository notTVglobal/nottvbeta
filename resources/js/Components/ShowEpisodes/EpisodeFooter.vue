<template>

    <div class="flex justify-between mt-6">
        <!-- Paginator -->
        <!--                            <Pagination :links="`#`" class="mt-6"/>-->
        <div>
            <div v-if="can.editEpisode"><span class="text-xs uppercase font-semibold">Episode ID: </span><span class="text-xs">{{ episode.ulid }}</span></div>
        </div>
        <div>
            <Link :href="`/teams/${team.slug}`" class="text-blue-500 hover:text-blue-700 ml-2 uppercase">{{ team.name }} ©
                <span v-if="show.last_release_year > 0" >{{ show.first_release_year }}-{{show.last_release_year}}</span>
                <span v-if="!show.last_release_year && show.first_release_year">{{show.first_release_year}}</span>
                <span v-if="!show.last_release_year && !show.first_release_year">{{currentYear}}</span>
            </Link>
        </div>
    </div>

</template>

<script setup>

defineProps({
    team: Object,
    episode: Object,
    show: Object,
    can: Object,
})

let currentYear = new Date().getFullYear()

</script>
