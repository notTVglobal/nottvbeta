<template>
    <button @click.prevent="editor.chain().focus().toggleBold().run()" class="py-1 px-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-400 rounded-lg font-bold">
        B
    </button>
    <button @click.prevent="editor.chain().focus().toggleItalic().run()" class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-400 rounded-lg font-italic">
        i
    </button>
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
    <div class="overflow-y-scroll min-h-[13rem] max-h-[13rem] mb-2 pb-2 bg-gray-50 border border-1 border-gray-300 focus:outline-none text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 ">
        <editor-content :editor="editor" class=""/>
    </div>
</template>

<script setup>
import {useNewsStore} from "@/Stores/NewsStore"
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import HardBreak from '@tiptap/extension-hard-break'
// import {History} from "@tiptap/extension-history";
// import {BulletList} from "@tiptap/extension-bullet-list";
import {ListItem} from "@tiptap/extension-list-item";
// import {onBeforeUnmount} from "vue";
import {Inertia} from "@inertiajs/inertia";
import throttle from "lodash/throttle"
import axios from "axios";
import TabbableTextarea from "@/Components/TabbableTextarea.vue";

let newsStore = useNewsStore();

const editor = new Editor({
    content: newsStore.newsArticleContentTiptop,
    extensions: [
        StarterKit,
        Document,
        Paragraph,
        Text,
        HardBreak,
        // BulletList,
        ListItem,
        // History,
    ],
    // triggered on every change
    onUpdate: ({ editor }) => {
        newsStore.newsArticleContentTiptop = editor.getHTML()
        // const value = editor.getJSON()
        axios.post('/news/save', { id: newsStore.newsArticleIdTiptop, content:newsStore.newsArticleContentTiptop, title:newsStore.newsArticleTitleTiptop })
    },
    autofocus: true,
    editorProps: {
        attributes: {
            class: 'min-h-[12rem] max-h-[12rem] block w-full p-2.5 focus:outline-none'
        }
    }
})

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
p {
    margin-top: 0.75em;
    margin-bottom: 1em;
}
ul,
ol {
    padding: 0 1rem;
}
</style>
