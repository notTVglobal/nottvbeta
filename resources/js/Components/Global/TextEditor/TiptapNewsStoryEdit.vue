<template>
  <TiptapButtons :editor="editor"/>
  <!--    <button-->
  <!--        @click="editor.chain().focus().undo().run()"-->
  <!--        :disabled="!editor.can().undo()"-->
  <!--    >-->
  <!--        undo-->
  <!--    </button>-->
  <!--    <button-->
  <!--        @click="editor.chain().focus().redo().run()"-->
  <!--        :disabled="!editor.can().redo()"-->
  <!--    >-->
  <!--        redo-->
  <!--    </button>-->
  <!--    <button @click.prevent="editor.chain().focus().toggleBulletList().run()"  class="ProseMirror py-1 px-3 ml-2 mb-2 bg-gray-200 rounded-lg">-->
  <!--        Bullets-->
  <!--    </button>-->
  <!--    <button @click.prevent="editor.chain().focus().toggleList().run()" class="py-1 px-3 ml-2 mb-2 bg-gray-200 rounded-lg">-->
  <!--        List-->
  <!--    </button>-->
  <!--    <button @click.prevent="editor.chain().focus().toggleBlockquote().run()" class="py-1 px-3 ml-2 mb-2 bg-gray-200 rounded-lg">-->
  <!--        Block-->
  <!--    </button>-->
  <!--    <button @click.prevent="editor.chain().focus().toggleCode().run()" class="py-1 px-3 ml-2 mb-2 bg-gray-200 rounded-lg">-->
  <!--        Code-->
  <!--    </button>-->
  <!--    <tabbable-textarea :editor="editor" class="element" />-->

  <div
      class="h-auto overflow-y-auto min-h-[13rem] max-h-[96rem] mb-2 pb-2 bg-gray-50 border border-1 border-gray-300 focus:outline-none text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 ">
    <editor-content :editor="editor" class="" :key="updateContent"/>
  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { watch } from 'vue'
import throttle from "lodash/throttle"
import axios from "axios"
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import HardBreak from '@tiptap/extension-hard-break'
// import {History} from "@tiptap/extension-history"
// import {BulletList} from "@tiptap/extension-bullet-list"
import { ListItem } from "@tiptap/extension-list-item"
// import {onBeforeUnmount} from "vue"
import { useNewsStore } from "@/Stores/NewsStore"
import TiptapButtons from "@/Components/Pages/News/TiptapButtons"
import TabbableTextarea from "@/Components/Global/TextEditor/TabbableTextarea"

const newsStore = useNewsStore()

let editor = new Editor({
  content: newsStore.newsArticleContentTiptop,
  extensions: [
    StarterKit,
    Paragraph.configure({
      HTMLAttributes: {
        class: 'news-paragraph',
      },
    }),
  ],

  onUpdate: ({editor}) => {
    newsStore.newsArticleContentTiptop = editor.getHTML()
    // newsStore.newsArticleContentTiptop = editor.getJSON()
    // const value = editor.getJSON()

    // Auto-save -> triggered on every change,
    // currently disabled. Needs to be throttled.
    //
    // axios.post('/news/save', { id: newsStore.newsArticleIdTiptop, body:newsStore.newsArticleContentTiptop, title:newsStore.newsArticleTitleTiptop })
  },

  autofocus: true,
  editorProps: {
    attributes: {
      class: 'h-auto min-h-[12rem] max-h-[96rem] overflow-y-auto block w-full p-2.5 focus:outline-none'
    }
  }
})


// watch(() => newsStore.newsArticleContentTiptop, (newNewsStoryContent) => {
//   Object.keys(newNewsStoryContent).forEach(key => {
//     if (form[key] !== undefined) {
//       form[key] = newNewsStoryContent[key];
//     }
//   });
// }, { deep: true });

// watch(() => newsStore.newsArticleContentTiptop, () => {
//   updateContent++;
//   // You can also watch for other relevant states if they affect setSelectedCategory
// })

// editor.commands.setContent({
//     "type": "doc",
//     "content": newsStore.newsArticleTiptop,
// })

// const json = editor.getJSON()
// newsStore.newsArticleTiptop = editor.getHTML()


// onBeforeUnmount(() => {
//     // this isn't working, I think it's
//     // needed for the history extension.
//     editor.destroy()
// })

// History.configure({
//     depth: 10,
// })

</script>

<style scoped>

</style>
