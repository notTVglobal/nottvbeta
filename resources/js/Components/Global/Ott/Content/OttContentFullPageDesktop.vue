<template>
  <Transition
      enter-from-class="transform -translate-x-full opacity-0"
      enter-to-class="transform translate-x-0 opacity-100"
      enter-active-class="transition duration-3000"
      leave-active-class="transition duration-2000"
      leave-from-class="transform translate-x-0 opacity-100"
      leave-to-class="transform -translate-x-full opacity-0"
  >
  <div v-if="ottStore.showOttContent && !appSettingStore.smallScreen && appSettingStore.fullPage" class="fixed bottom-20 left-0 w-1/2 h-32 pl-10 pr-1 py-1 bg-yellow-500 z-100">
    <div class="content bg-black w-full h-full p-2 text-red font-mono">
      This is an OttContent box. Creators can place items in this box during a broadcast.
      <br/> Images, links, messages.
      <div class="relative w-full flex justify-end bottom-4">
        <button @click="hide" class="btn">hide</button>
      </div>
    </div>
  </div>
  </Transition>
</template>
<script setup>
import { useOttStore } from "@/Stores/OttStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"

const ottStore = useOttStore();
const appSettingStore = useAppSettingStore()

const hide = () => {
  ottStore.hideOttContent()
}
</script>