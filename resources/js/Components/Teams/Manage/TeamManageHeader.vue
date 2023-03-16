<template>


        <div>
            <h3 class="inline-flex items-center text-3xl font-semibold relative">
                <img :src="'/storage/images/' + logo" alt="" class="w-20 mr-2">
                <Link :href="`/teams/${team.slug}`" class="uppercase">{{ team.name }}</Link>
                <div
                    class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -right-4 -top-0.5">
                    {{ team.memberSpots }}
                </div>
            </h3>

        </div>



</template>

<script setup>
defineProps({
    team: Object,
    teamLeader: String,
    logo: String,
    can: Object,
    message: String,
})
</script>
