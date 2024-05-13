<template>
  <TipTapButtons :editor="editor"/>
  <div
      class="tiptap hide-scrollbar h-auto overflow-y-auto min-h-[13rem] max-h-[96rem] mb-2 pb-2 bg-gray-50 border border-1 border-gray-300 focus:outline-none text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500">
    <editor-content :editor="editor" class="news-story"/>
    <div class="character-count" v-if="editor">
      {{ editor.storage.characterCount.characters() }}/{{ limit }} characters
      <br>
      {{ editor.storage.characterCount.words() }} words
    </div>

  </div>
</template>

<script setup>
import { onBeforeUnmount, watch } from 'vue'
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { History } from '@tiptap/extension-history'
import { useNewsStore } from '@/Stores/NewsStore'
import TipTapButtons from '@/Components/Global/TextEditor/TipTapNewsStoryButtons'
import { TextStyle } from '@tiptap/extension-text-style'
import { Color } from '@tiptap/extension-color'
import Typography from '@tiptap/extension-typography'
import CharacterCount from '@tiptap/extension-character-count'
import Placeholder from '@tiptap/extension-placeholder'
import { FontFamily } from '@tiptap/extension-font-family'
import { TextAlign } from '@tiptap/extension-text-align'
import { Underline } from '@tiptap/extension-underline'
import { Subscript } from '@tiptap/extension-subscript'
import { Superscript } from '@tiptap/extension-superscript'
import Link from '@tiptap/extension-link'

const newsStore = useNewsStore()

const limit = 5000

// Watch for changes in the content and update the editor
watch(() => newsStore.newsArticleContentTipTap, (newContent) => {
  if (editor && newContent) {
    editor.commands.setContent(newContent)
  }
}, {immediate: true})

const editor = new Editor({
  content: newsStore.newsArticleContentTiptop,
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
    TextAlign.configure({
      types: ['heading', 'paragraph'],
    }),
    Link.configure({
      openOnClick: true,
    }),
    CharacterCount.configure({
      limit: limit,
    }),
    Placeholder.configure({
      placeholder: 'Write something …',
    }),
  ],
  onUpdate: ({editor}) => {
    // newsStore.newsArticleContentTiptop = editor.getJSON()
    newsStore.newsArticleContentTiptop = editor.getHTML()

    // Auto-save -> triggered on every change,
    // currently disabled. Needs to be throttled.
    //
    // axios.post('/news/save', { id: newsStore.newsArticleIdTiptop, body:newsStore.newsArticleContentTiptop, title:newsStore.newsArticleTitleTiptop })
  },
  autofocus: true,
  editorProps: {
    attributes: {
      class: 'h-auto min-h-[12rem] max-h-[96rem] overflow-y-auto block w-full p-2.5 focus:outline-none',
    },
  },
})
History.configure({
  depth: 10,
})


// Ensure editor is destroyed properly on component unmount
onBeforeUnmount(() => {
  if (editor) {
    editor.destroy()
  }
})

</script>

<style scoped>
.tiptap {
  > * + * {
    margin-top: 0.75em;
  }
}

.character-count {
  margin-top: 1rem;
  margin-left: 1rem;
  color: #868e96;
}

/* Placeholder (at the top) */
.tiptap p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #adb5bd;
  pointer-events: none;
  height: 0;
}


/* Placeholder (on every new line) */
/*.tiptap p.is-empty::before {
  content: attr(data-placeholder);
  float: left;
  color: #adb5bd;
  pointer-events: none;
  height: 0;
}*/
</style>
