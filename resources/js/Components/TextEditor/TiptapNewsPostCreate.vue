<template>
    <TiptapButtons :editor="editor" />
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

// import {onBeforeUnmount} from "vue";
import {Inertia} from "@inertiajs/inertia";
import throttle from "lodash/throttle"
import axios from "axios";
import TabbableTextarea from "@/Components/TabbableTextarea.vue";
import TiptapButtons from "@/Components/News/TiptapButtons.vue";

let newsStore = useNewsStore();

const editor = new Editor({
    content: newsStore.newsArticleContentTiptop,
    extensions: [
        StarterKit,
    ],
    // triggered on every change
    onUpdate: ({ editor }) => {
        newsStore.newsArticleContentTiptop = editor.getHTML()
        // const value = editor.getJSON()
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
