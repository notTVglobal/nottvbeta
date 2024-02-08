<template>

  <div class="flex justify-between mt-6">
    <!-- Paginator -->
    <!--                            <Pagination :links="`#`" class="mt-6"/>-->
    <div>
      <div v-if="can.editEpisode"><span class="text-xs uppercase font-semibold">Episode ID: </span><span
          class="text-xs">{{ episode.ulid }}</span></div>
    </div>
    <div>
      <Link :href="`/teams/${team.slug}`" class="text-blue-500 hover:text-blue-700 ml-2 uppercase">
        {{ team.name }}&nbsp;
      </Link>

      <span v-if="episode?.creative_commons?.name"
            class="text-xs font-semibold text-gray-500">&nbsp;{{ episode?.creative_commons?.name }}&nbsp;<span v-if="!episode?.creative_commons?.id === 7">&bull;&nbsp;</span></span>

      <span class="text-xs font-semibold text-gray-500"
            v-if="episode?.creative_commons?.id === 7">&nbsp;&copy;&nbsp;</span>

      <!--                    If there is a copyright year display it... we need to remove the &copy; and replace it with whichever creative commons icon it needs -->
      <span v-if="show?.last_release_year > 0">{{ show?.first_release_year }}-{{ show?.last_release_year }}</span>
      <span v-if="!show?.last_release_year && show?.first_release_year">{{ show?.first_release_year }}</span>
      <span class="text-xs font-semibold text-gray-500"
            v-if="!show?.last_release_year && !show?.first_release_year && episode?.copyrightYear">{{
          episode.copyrightYear
        }}</span>

      <!--                    If there is no copyright year then it's probably public domain... so we'll just display the creative commons name-->



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
