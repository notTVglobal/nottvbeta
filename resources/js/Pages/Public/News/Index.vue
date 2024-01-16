<template>

  <Head title="News"/>

  <div>
    <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
  </div>

  <div class="px-6 py-4">
    <div class="bg-gray-200 mx-auto p-5 my-10 h-full w-full text-gray-900 rounded">
      <div class="bg-white text-gray-900">Public News Page</div>

    </div>
  </div>


</template>

<script setup>
import PublicNavigationMenu from "@/Components/Global/Navigation/PublicNavigationMenu"
</script>

<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>
