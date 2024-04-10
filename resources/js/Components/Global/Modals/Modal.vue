<template>
  <Transition
      enter-from-class="opacity-0 scale-125"
      enter-to-class="opacity-100 scale-100"
      enter-active-class="transition duration-300"
      leave-active-class="transition duration-200"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-125"
  >
    <div v-if="show" class="modal-mask">
      <div class="modal-container">
        <header class="uppercase text-sm font-semibold mb-2">
          <slot name="header">default header</slot>
        </header>

        <div class="py-3">
          <slot>default body</slot>
        </div>

        <footer>
          <div class="modal-footer">
            <slot name="footer">
              <button
                  @click="$emit('close')"
                  class="bg-gray-300 p-2 rounded-md hover:bg-gray-400 hover:text-gray-800"
              >Close
              </button>
            </slot>
          </div>
        </footer>
      </div>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  show: Boolean
});
</script>

<style>
.modal-mask {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  display: grid;
  place-items: center;
  z-index: 100;
}

.modal-container {
  background: white;
  color: black;
  padding: 1rem;
  width: 80vw;
  max-width: 500px;
  border-radius: 8px;
}

.modal-footer {
  border-top: 1px solid #ddd;
  margin-top: 1rem;
  padding-top: 0.5rem;
  font-size: .8rem;
}
</style>
