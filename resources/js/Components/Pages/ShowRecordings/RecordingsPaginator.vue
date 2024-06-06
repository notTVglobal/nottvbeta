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

.btn {
  transition: background-color 0.3s ease;
}

/* Default hover effect */
.btn:hover {
  background-color: #ff6347; /* Example hover color */
}

.btn-active {
  background-color: #ff7f50; /* Active color in dark mode */
}

/* Dark mode styles */
@media (prefers-color-scheme: dark) {
  .btn {
    color: #ffffff;
    background-color: #333333;
  }

  .btn:hover {
    background-color: #555555; /* Example dark mode hover color */
  }

  .btn-active {
    background-color: #ff7f50; /* Active color in dark mode */
  }
}
</style>
