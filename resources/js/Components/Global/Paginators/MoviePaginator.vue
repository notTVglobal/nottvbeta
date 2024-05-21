<template>
  <div class="pagination">
    <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1">Previous</button>
    <span>Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
    <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page">Next</button>
  </div>
</template>

<script setup>
const props = defineProps({
  pagination: Object
});

const emit = defineEmits(['page-changed']);

const changePage = (page) => {
  if (page > 0 && page <= props.pagination.last_page) {
    emit('page-changed', page);
  }
};
</script>
