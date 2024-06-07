<template>
  <Breadcrumbs :breadcrumbs="[
          { text: 'Reporters', to: '/news/reporters' },
          { text: $page.props.newsPerson.name, to: '' }
      ]" />

  <div class="text-center text-3xl font-semibold tracking-widest uppercase text-gray-50">Reporter Profile</div>

</template>
<script setup>
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import Breadcrumbs from '@/Components/Global/Breadcrumbs/Breadcrumbs'
</script>
<style scoped>

.breadcrumbLink {
  @apply text-blue-300 hover:text-blue-500
}

</style>
