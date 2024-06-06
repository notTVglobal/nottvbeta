<template>
  <div v-if="totalPages > 1" class="join">
    <button
        v-for="page in totalPages"
        :key="page"
        :class="['join-item', 'btn', { 'btn-active': currentPage === page }]"
        @click="goToPage(page)"
    >
      {{ page }}
    </button>
  </div>
</template>

<script setup>

const props = defineProps({
  totalPages: {
    type: Number,
    required: true,
  },
  currentPage: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(['update']);

const goToPage = (page) => {
  emit('update', page);
};
</script>

<style scoped>
.join {
  display: flex;
  justify-content: center;
  gap: 4px;
}
.btn-active {
  background-color: #ff7f50; /* Example active color */
}
</style>
