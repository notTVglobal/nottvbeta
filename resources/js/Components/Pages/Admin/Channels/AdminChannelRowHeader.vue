<template>

    <div class="table-row">
      <div scope="col" class="table-cell px-6 py-3 uppercase">
        <font-awesome-icon icon="fa-repeat" class="mr-2 cursor-pointer hover:text-blue-500"
                           @click.prevent="emit('reloadChannelsList')"/>
        <span class="uppercase">Channel </span>
      </div>
      <div scope="col" class="hidden md:table-cell px-6 py-3 uppercase text-center">
        <span class="uppercase">Current Video</span>
      </div>
      <div scope="col" class="table-cell px-6 py-3 uppercase text-center">
        <span class="uppercase">External Source</span>
      </div>
      <div scope="col" class="px-6 py-3 max-w-64 text-center">
        <span class="uppercase">Channel Playlist</span>
      </div>
      <div scope="col" class="table-cell px-6 py-3 text-center">
        <span class="uppercase">Mist Stream</span>
      </div>
      <div scope="col" class="table-cell px-6 py-3 w-52 text-center">
        <span class="uppercase">Active</span>
      </div>

    </div>

</template>
<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
const emit = defineEmits(['reloadChannelsList'])
</script>