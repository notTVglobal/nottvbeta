<template>
  <div>
    <div v-if="data[dataTypeDb]">
      <AdminChannelDataPopper
          :data="data"
          :dataType="dataType"
          :dataTypeDb="dataTypeDb"
          @open-modal="emit('open-modal')"/>
    </div>
    <div v-else>
      <button @click="emit('open-modal')">
    <span v-if="data[dataTypeDb]?.name" class="text-gray-700 tracking-wide">
                              <span v-if="data.playback_priority_type === dataType"
                                    :class="{'text-black font-bold': data.playback_priority_type === dataType}">{{
                                  data?.dataTypeDb.name
                                }}</span>
      <span v-else>{{ data?.dataTypeDb.name }}</span>
    </span>
        <span v-else class="italic text-gray-300 text-sm">no {{ dataType }}</span>
      </button>
    </div>
    <!-- Status indicator + action to change source -->
    <source-selector :source="data"
                     :source-type="dataType"/>
  </div>
</template>
<script setup>
import SourceSelector from '@/Components/Pages/Admin/Channels/SourceSelector'
import AdminChannelDataPopper from '@/Components/Pages/Admin/Channels/AdminChannelDataPopper.vue'

defineProps({
  data: Object,
  dataType: String,
  dataTypeDb: String,
})

const emit = defineEmits(['open-modal'])
</script>