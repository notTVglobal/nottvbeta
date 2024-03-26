<template>
  <header>
    <div class="flex justify-between my-3">
      <div class="mb-4 relative">
        <h1 class="text-3xl font-semibold inline-block">
          <slot/>
        </h1>
        <span v-if="displayBadges" class="text-white font-semibold badge badge-success absolute -top-2 -right-10 flex items-center justify-center min-w-[2rem] h-[2rem] rounded-full">{{ (badgePrimaryNumber ?? 0) > 99 ? '99+' : (badgePrimaryNumber ?? 0) }}</span>
        <span v-if="displayBadges" class="font-semibold badge badge-warning absolute -top-2 -right-20 flex items-center justify-center min-w-[2rem] h-[2rem] rounded-full">{{ (badgeSecondaryNumber ?? 0) > 99 ? '99+' : (badgeSecondaryNumber ?? 0) }}</span>

      </div>
      <div>
        <div class="flex flex-wrap-reverse justify-end gap-2">
          <Link :href="`/admin/settings`">
            <button
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
            >Admin Settings
            </button>
          </Link>
        </div>
      </div>
    </div>
  </header>

</template>

<script setup>
import { reactive } from 'vue'

const props = defineProps({
  displayBadges: Boolean,
  badgePrimaryNumber: Number,
  badgeSecondaryNumber: Number,
});

const defaultProps = {
  displayBadges: false,
};

const finalProps = reactive({ ...defaultProps, ...props });

</script>
