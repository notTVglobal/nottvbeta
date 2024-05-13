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
import Typography from '@tiptap/extension-typography'
import { TextAlign } from '@tiptap/extension-text-align' // Import the desired extensions
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
        Text,
        TextStyle,
        FontFamily,
        Color,
        Typography,
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
      content: props.content, // Use the passed Tiptap JSON content
      editable: false
    })
);

// Ensure editor is destroyed properly on component unmount
onBeforeUnmount(() => {
  if (editor.value) {
    editor.value.destroy()
  }
})
</script>


<style scoped>
/* Paragraphs */
p {
  color: #EFEFEF; /* Light grey color for readability against darker backgrounds */
  margin-bottom: 1.2em; /* Provides space between paragraphs */
  font-size: 1rem; /* Standard font size for readability */
  line-height: 1.6; /* Good line height for text readability */
}

/* Headings - Adjust sizes as needed for visual hierarchy */
h1, h2, h3, h4, h5, h6 {
  color: #FFF; /* White color for headings to stand out */
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
  color: #EFEFEF;
  list-style-type: disc; /* Add disc style for unordered lists */
}

ol {
  margin-left: 20px; /* Indent lists */
  margin-bottom: 1.2em;
  color: #EFEFEF;
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
  color: #DDD; /* Slightly lighter text for blockquotes */
}

/* Add styles for other elements as needed */
</style>