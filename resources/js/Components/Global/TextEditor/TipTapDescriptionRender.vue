<template>
  <div>
    <editor-content :editor="editor"/>
  </div>
</template>
<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { ref, onMounted } from 'vue'
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
      editable: false
    })
);
</script>


<style>
/* Paragraphs */
p {
  margin-bottom: 1.2em; /* Provides space between paragraphs */
  font-size: 1rem; /* Standard font size for readability */
  line-height: 1.6; /* Good line height for text readability */

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
