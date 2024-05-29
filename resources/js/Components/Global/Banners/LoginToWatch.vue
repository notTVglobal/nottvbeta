<template>
  <div v-if="!$page.props.auth.user" class="mt-8 border border-yellow-600 rounded-lg p-8">
    <div class="block">
      <h2 class="text-2xl font-bold mb-4">Join the Community</h2>
      <p class="text-lg mb-6">
        Login to watch episodes and explore more inspiring content!
      </p>
      <Link :href="`/login`"
            class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold py-2 px-4 rounded-full">
        Login Now
      </Link>
    </div>
  </div>
</template>
<script setup>
</script>