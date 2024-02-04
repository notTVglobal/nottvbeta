<template>
  <div class="readonly-editor">
    <editor-content :editor="editor" contenteditable="false"/>
  </div>
</template>
<script setup>
import { Editor, useEditor, EditorContent, Text } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { ref } from 'vue'
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
  content: String,
})

let editor = ref(
    useEditor({
      extensions: [
        StarterKit, // Include the desired extensions (e.g., StarterKit for basic functionality)
        Document,
        Paragraph,
        Text,
        TextStyle,
        FontFamily,
        Color,
        Typography,
        Strike,
        Underline,
        Subscript,
        Superscript,
        TextAlign.configure({
          types: ['heading', 'paragraph'],
        }),
        Link.configure({
          openOnClick: true,
        }),
      ],
      content: JSON.parse(props.content), // Use the passed Tiptap JSON content
    })
);
</script>


<style scoped>
.readonly-editor {
  /* Disable pointer events to prevent user interaction */
  pointer-events: none;
}

/* Optionally, apply styles to visually indicate it's read-only */
.readonly-editor :not(.ProseMirror) {
  opacity: 1; /* Adjust opacity to make it visually read-only */
  cursor: not-allowed; /* Change cursor style to indicate non-interactivity */
}

</style>