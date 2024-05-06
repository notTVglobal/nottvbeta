<template>
  <div
      class="editor-textarea editor-container bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus-within:ring-blue-500 focus-within:border-blue-500"
      @click="focusEditor">
    <EditorContent :editor="editor"/>
  </div>
</template>

<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { TabExtension } from '@/Utilities/TabExtension'
import { History } from '@tiptap/extension-history'
import { useNewsStore } from '@/Stores/NewsStore'
import TipTapButtons from '@/Components/Global/TextEditor/TipTapNewsStoryButtons'


import CharacterCount from '@tiptap/extension-character-count'
import Placeholder from '@tiptap/extension-placeholder'
import Paragraph from '@tiptap/extension-paragraph'

import { TextAlign } from '@tiptap/extension-text-align'
import { Strike } from '@tiptap/extension-strike'

import { onMounted, ref, defineEmits } from 'vue'
import { TextStyle } from '@tiptap/extension-text-style'
import { FontFamily } from '@tiptap/extension-font-family'
import { Color } from '@tiptap/extension-color'
import Typography from '@tiptap/extension-typography'
import { Underline } from '@tiptap/extension-underline'
import { Subscript } from '@tiptap/extension-subscript'
import { Superscript } from '@tiptap/extension-superscript'
import Link from '@tiptap/extension-link'

const emits = defineEmits(['updateContent'])

const props = defineProps({
  description: String,
})

const initialContent = props.description ? props.description : '<p>Start typing the description...</p>'

const editor = useEditor({
  extensions: [
    StarterKit,
    TabExtension,
    Document,
    Text,
    TextStyle,
    FontFamily,
    Color,
    Typography,
    Underline,
    Subscript,
    Superscript,
    Link.configure({
      openOnClick: false,
    }),
  ],
  content: initialContent,
  autofocus: false,
  onUpdate: ({editor}) => {
    const htmlOutput = editor.getHTML()
    emits('updateContent', htmlOutput)
  },
})
History.configure({
  depth: 10,
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


// const submitDescription = () => {
//   if (editor) {
//     const htmlOutput = editor.getHTML();
//     // Emit or use an API call to send `htmlOutput` to your backend
//     console.log(htmlOutput);
//     // Example: emit to parent component or handle form submission
//     emit('update:description', htmlOutput);
//   }
// }

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
<style>
.editor-textarea {
  outline: none; /* Mimics the lack of outline in styled textareas */
  min-height: 150px; /* Mimics the 'rows' attribute by setting initial height */
  max-height: 500px; /* Prevents the editor from growing indefinitely */
  overflow-y: auto; /* Adds a scrollbar when content exceeds max height */
  padding: 8px; /* Provides padding inside the editor, similar to a typical textarea */
}

.editor-container:focus-within {
  border-color: #3182ce; /* Changes border color on focus */
  ring: 2px solid #3182ce; /* Example of using Tailwind's ring utilities */
}

/* Paragraphs */
p {
  margin-bottom: 1.2em; /* Provides space between paragraphs */
  font-size: 1rem; /* Standard font size for readability */
  line-height: 1.6; /* Good line height for text readability */
}

/* Headings - Adjust sizes as needed for visual hierarchy */
h1, h2, h3, h4, h5, h6 {
  margin-top: 1.2em;
  margin-bottom: 0.8em;
}

h1 {
  font-size: 2em; /* Larger for main titles */
}

h2 {
  font-size: 1.75em; /* Slightly smaller */
}

h3 {
  font-size: 1.5em;
}

/* Lists */
ul {
  margin-left: 20px; /* Indent lists */
  margin-bottom: 1.2em;
  list-style-type: disc; /* Add disc style for unordered lists */
}

ol {
  margin-left: 20px; /* Indent lists */
  margin-bottom: 1.2em;
}

li {
  margin-bottom: 0.5em; /* Space between list items */
}

/* Links */
a {
  color: #1E90FF; /* A distinct color for links to stand out */
  text-decoration: none; /* No underline to keep it clean */
}

a:hover {
  text-decoration: underline; /* Underline on hover for usability */
}

/* Additional elements */
blockquote {
  border-left: 3px solid #ccc;
  padding-left: 20px;
  margin: 1.2em 0;
}

/* Add styles for other elements as needed */
</style>