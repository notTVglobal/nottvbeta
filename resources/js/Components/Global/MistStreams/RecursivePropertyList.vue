<!-- RecursivePropertyList.vue -->
<template>
  <div>
    <ul class="list-unstyled">
      <li v-for="(value, key) in object" :key="key" class="item">
        <!-- Toggle Button -->
        <input type="checkbox" v-if="isComplex(value)" @click="toggle(key, $event)" class="toggle toggle-sm mr-1"/>

        <!-- Key Display -->
        <strong>{{ key }}:</strong>

        <!-- Direct value or Recursion -->
        <span v-if="!isComplex(value)">{{ value }}</span>
        <ul v-else-if="isOpen(key)" class="nested">
          <RecursivePropertyList v-if="typeof value === 'object'" :object="value" />
        </ul>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { defineProps, reactive } from 'vue';

const props = defineProps({
  object: Object
});


// Helper functions
const isComplex = (value) => typeof value === 'object';
const opened = reactive({});

const toggle = (key, event) => {
  opened[key] = !opened[key];
  event.target.blur(); // Add this line to remove focus from the checkbox
};
const isOpen = (key) => !!opened[key];
</script>

<style scoped>
.toggle {
  background: none;
  border: none;
  cursor: pointer;
  margin-right: 5px;
}

.nested {
  margin-left: 20px; /* Indentation for nested objects */
}

.item {
  margin-bottom: 5px;
}
</style>