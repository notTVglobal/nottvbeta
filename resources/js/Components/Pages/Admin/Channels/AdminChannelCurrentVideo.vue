<template>
  <div v-if="data?.currentVideo">
    <Popper
        hover openDelay="50" closeDelay="50" offset-skid="0" offset-distance="0"
        placement="bottom">

      <template #content>
        <div class="text-xs" id="tooltip">
          <div>ID: {{ data?.currentVideo.id }}</div>
          <div>File Name: {{ data?.currentVideo.file_name }}</div>
          <div>Type: {{ data?.currentVideo.type }}</div>
          <div>Folder: {{ data?.currentVideo.folder }}</div>
          <div>Storage Location: {{ data?.currentVideo.storage_location }}</div>
          <div>Processing: <span v-if="data?.currentVideo.is_processing">true</span><span
              v-else>false</span></div>
          <div>Length: {{ data?.currentVideo.length }}</div>
        </div>
      </template>

      <span v-if="data?.currentVideo.name" class="cursor-help text-green-500 font-semibold">{{
          data?.currentVideo.name
        }}</span>
      <span v-else class="cursor-help text-blue-500 font-semibold">Video has no name</span>

    </Popper>

  </div>
  <div v-else class="italic text-gray-300 text-xs">No video object playing</div>
</template>

<script setup>
defineProps({
  data: Object,
})
</script>