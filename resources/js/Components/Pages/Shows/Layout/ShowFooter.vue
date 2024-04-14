<template>

  <div class="flex justify-between mt-64 mb-4">
    <div>
      <span class="text-xs uppercase font-semibold">Show ID: </span>
      <span class="text-xs">{{ show.ulid }}</span>
    </div>

    <div class="flex justify-end">


      <!-- Paginator -->
      <!--                            <Pagination :links="`#`" class="mt-6"/>-->
      <div class="text-gray-200 ml-2 uppercase">
        {{ show.name }}&nbsp;
      </div>
      <div>
        ©
        <!--                    If there is a copyright year display it... we need to remove the &copy; and replace it with whichever creative commons icon it needs -->
        <span v-if="show.last_release_year > 0">{{ show.first_release_year }}-{{ show.last_release_year }}</span>
        <span v-if="!show.last_release_year && show.first_release_year">{{ show.first_release_year }}</span>
        <span class="text-xs font-semibold text-gray-500"
              v-if="!show.last_release_year && !show.first_release_year && show.copyrightYear">{{
            show.copyrightYear
          }}</span>
        <span class="text-xs font-semibold text-gray-500"
              v-if="show.creative_commons?.id === 7">&nbsp;&copy;&nbsp;</span>
        <!--                    If there is no copyright year then it's probably public domain... so we'll just display the creative commons name-->
        <span v-if="show.creative_commons?.name"
              class="text-xs font-semibold text-gray-500">&nbsp;&bull;&nbsp;{{ show.creative_commons?.name }}&nbsp;&bull;&nbsp;</span>


      </div>
    </div>

  </div>

</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()

defineProps({
  team: Object,
  show: Object,
})

let currentYear = new Date().getFullYear()

</script>
