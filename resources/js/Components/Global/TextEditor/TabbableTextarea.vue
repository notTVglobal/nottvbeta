<template>
<!--    <textarea-->
<!--        @keydown.tab.prevent="onTabPress"-->
<!--        @keyup="emit('update:modelValue', $event.target.value)"-->
<!--        v-text="modelValue"-->
<!--        :placeholder="placeholder" />-->
  <textarea
      @keydown.tab.prevent="onTabPress"
      @input="onInput"
      :value="modelValue"
      :placeholder="placeholder"
      class="textarea textarea-bordered text-black bg-white dark:bg-gray-800 dark:text-white"
  />
</template>

<script setup>
defineProps({
  modelValue: String,
  placeholder: String
});

const emit = defineEmits(['update:modelValue']);
// let emit = defineEmits(['update:modelValue']);

const onInput = (event) => {
  emit('update:modelValue', event.target.value);
};

function onTabPress(e) {
  let textarea = e.target;

  // get caret position/selection
  let val = textarea.value,
      start = textarea.selectionStart,
      end = textarea.selectionEnd;

  // set textarea value to: text before caret + tab + text after caret
  textarea.value = val.substring(0, start) + "\t" + val.substring(end);

  // put caret at right position again
  textarea.selectionStart = textarea.selectionEnd = start + 1;
}

</script>
