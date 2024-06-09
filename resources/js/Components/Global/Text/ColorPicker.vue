<template>
  <div>
    <div class="overlay" v-if="visible" @click="hide"></div>
    <div class="color-picker-dialog" v-if="visible">
      <Chrome v-model="localColor" />
      <button class="btn btn-primary mt-2" @click="applyColor">Apply</button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Chrome } from '@ckpack/vue-color'

const props = defineProps({
  visible: {
    type: Boolean,
    default: false
  },
  color: {
    type: String,
    default: '#000000'
  }
})

const emit = defineEmits(['close', 'apply', 'update:color'])

const localColor = ref(props.color)
//
// watch(() => props.color, (newColor) => {
//   localColor.value = newColor
// })

// const updateColor = (newColor) => {
//   localColor.value = newColor.hex
//   emit('update:color', newColor.hex)
// }

const applyColor = () => {
  console.log(localColor.value.hex)
  emit('apply', localColor.value.hex)
  hide()
}

const hide = () => {
  emit('close')
}
</script>

<style scoped>
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 999;
}

.color-picker-dialog {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  z-index: 1000;
}

.btn-primary {
  background-color: #4a5568;
  color: white;
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary:hover {
  background-color: #2d3748;
}
</style>
