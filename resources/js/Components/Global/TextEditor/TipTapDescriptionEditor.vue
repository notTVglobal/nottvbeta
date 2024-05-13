<template>
  <div>
    <div class="toolbar mb-2">
      <button @click.prevent="toggleBold" class="btn">Bold</button>
      <button @click.prevent="toggleItalic" class="btn">Italic</button>
      <button @click.prevent="toggleUnderline" class="btn">Underline</button>
      <button @click.prevent="toggleSuperscript" class="btn">Superscript</button>
      <button @click.prevent="toggleSubscript" class="btn">Subscript</button>
    </div>
    <div
        class="editor-textarea editor-container bg-gray-50 border border-gray-400 text-gray-900 text-sm w-full rounded-lg focus-within:ring-blue-500 focus-within:border-blue-500"
        @click="focusEditor">
      <EditorContent :editor="editor"/>
    </div>
  </div>
</template>

<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Document from '@tiptap/extension-document'
import Text from '@tiptap/extension-text'
import TextStyle from '@tiptap/extension-text-style'
import FontFamily from '@tiptap/extension-font-family'
import Color from '@tiptap/extension-color'
import Typography from '@tiptap/extension-typography'
import Underline from '@tiptap/extension-underline'
import Subscript from '@tiptap/extension-subscript'
import Superscript from '@tiptap/extension-superscript'
import Link from '@tiptap/extension-link'
import { onMounted, onUnmounted, ref } from 'vue'

const emits = defineEmits(['updateContent'])

const props = defineProps({
  description: String,
})

const initialContent = props.description ? props.description : '<p>Start typing the description...</p>'

const editor = useEditor({
  autofocus: true, // Setting autofocus to true
  extensions: [
    StarterKit,
    Document,
    Text,
    TextStyle,
    FontFamily,
    Color,
    Typography,
    Underline,
    Subscript,
    Superscript,
    Link.configure({openOnClick: false}),

  ],
  content: initialContent,
  onUpdate: ({editor}) => {
    const htmlOutput = editor.getHTML()
    emits('updateContent', htmlOutput)
  },
})


const hasFocused = ref(false) // State to track if the editor has been focused

const focusEditor = (event) => {
  if (!hasFocused.value) {
    if (!props.description) {
      // Clear the editor the first time it's focused
      editor.value.commands.clearContent()
    }
    hasFocused.value = true
  }
  editor.value.commands.focus()
}
const toggleBold = () => {
  editor.value.chain().focus().toggleBold().run()
}

const toggleItalic = () => {
  editor.value.chain().focus().toggleItalic().run()
}

const toggleUnderline = () => {
  editor.value.chain().focus().toggleUnderline().run()
}

const toggleSuperscript = () => {
  editor.value.chain().focus().toggleSuperscript().run()
}

const toggleSubscript = () => {
  editor.value.chain().focus().toggleSubscript().run()
}


// const submitDescription = () => {
//   if (editor) {
//     const htmlOutput = editor.getHTML();
//     // Emit or use an API call to send `htmlOutput` to your backend
//     console.log(htmlOutput);
//     // Example: emit to parent component or handle form submission
//     emit('update:description', htmlOutput);
//   }
// }

onMounted(() => {
  // Ensure the editor is focused when needed and adjusts height accordingly
  editor.value?.commands.focus()
  adjustHeight()
  const editorElement = document.querySelector('.ProseMirror')  // Adjust the selector as needed

})

const adjustHeight = () => {
  const content = editor.value.view.dom // Access the editor's content DOM node
  if (content) {
    content.style.minHeight = 'auto' // Reset minHeight to calculate potentially smaller size
    content.style.minHeight = `${Math.max(content.scrollHeight, 150)}px` // Set minHeight based on content or minimum
  }
}

// Add this as a handler for content updates
editor.value?.on('update', adjustHeight)

</script>
<style scoped>
.toolbar {
  display: flex;
  gap: 8px;
}

.btn {
  background-color: #4a5568;
  color: white;
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn:hover {
  background-color: #2d3748;
}
</style>
