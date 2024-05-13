<template>
  <div>
    <editor-content :editor="editor"/>
  </div>
</template>
<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { TextStyle } from '@tiptap/extension-text-style'
import { Color } from '@tiptap/extension-color'
import { FontFamily } from '@tiptap/extension-font-family'
import Paragraph from '@tiptap/extension-paragraph'
import Typography from '@tiptap/extension-typography'
import { TextAlign } from '@tiptap/extension-text-align' // Import the desired extensions
import { Strike } from '@tiptap/extension-strike'
import { Underline } from '@tiptap/extension-underline'
import { Subscript } from '@tiptap/extension-subscript'
import { Superscript } from '@tiptap/extension-superscript'
import Link from '@tiptap/extension-link'


const props = defineProps({
  description: String,
})

let editor = ref(
    useEditor({
      extensions: [
        StarterKit, // Include the desired extensions (e.g., StarterKit for basic functionality)
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
          openOnClick: true,
        }),
      ],
      content: props.description, // Use the passed Tiptap HTML content
      editable: false,
    }),
)

// Ensure editor is destroyed properly on component unmount
onBeforeUnmount(() => {
  if (editor.value) {
    editor.value.destroy()
  }
})
</script>



