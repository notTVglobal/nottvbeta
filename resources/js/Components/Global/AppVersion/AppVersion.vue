<template>

  <div>
    <!--                                        <div class="flex pt-6 justify-center ">Web application</div>-->
    <!--                                        <div class="flex justify-center">concept and design</div>-->
    <div class="flex justify-center">
      not
      <span class="text-xs">
                &#174;
            </span>
      TV &#169; 2009-{{ new Date().getFullYear() }}
    </div>
    <div class="flex justify-center">
      Beta Preview v0.9.3.39
    </div>
    <div class="flex justify-center text-blue-600 hover:text-blue-400 underline">
      <Link @click="appSettingStore.closeNavDropdown()" :href="`/changelog`">changelog</Link>
    </div>
    <!--                                        <div class="flex justify-center">Please send us</div>-->
    <!--                                          <div class="flex justify-center">comments and questions</div>-->
    <!--                                        <div class="flex justify-center"><a href="https://help.not.tv/" target="_blank" class="text-blue-600 hover:text-blue-40">here</a>.</div>-->
  </div>

</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"

const appSettingStore = useAppSettingStore()

</script>
