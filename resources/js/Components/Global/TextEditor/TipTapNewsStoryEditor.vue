<template>
  <TipTapButtons :editor="editor"/>
  <div class="relative">
    <div
        class="tiptap hide-scrollbar h-auto overflow-y-auto min-h-[13rem] max-h-[96rem] mb-2 pb-2 bg-gray-50 border border-1 border-gray-300 focus:outline-none text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500">
      <editor-content :editor="editor" class="news-story"/>
      <div class="character-count" v-if="editor">
        {{ editor.storage.characterCount.characters() }} characters
        <br>
        {{ editor.storage.characterCount.words() }} words
      </div>
    </div>
  </div>

</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { useNewsStore } from '@/Stores/NewsStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
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
import { throttle } from 'lodash'

const newsStore = useNewsStore()
const notificationStore = useNotificationStore()

const limit = 5000
const editor = ref(null)
const hasShownSaveMessage = ref(false)
const isUnmounting = ref(false)

const saveContent = throttle(async () => {
  if (isUnmounting.value) return // Skip saving if the component is unmounting

  if (!newsStore.id) {
    if (!hasShownSaveMessage.value) {
      console.log('Please save the story and go to editing mode to enable automatic saving.')
      notificationStore.setGeneralServiceNotification('Save Required', 'Please save the story and go to editing mode to enable automatic saving.')
      hasShownSaveMessage.value = true
    }
    return
  }
  try {
    await axios.post('/newsStory/cache', {
      id: newsStore.id,
      slug: newsStore.slug,
      status: newsStore.status,
      title: newsStore.title,
      content: newsStore.content,
      newsPerson: newsStore.newsPerson,
      category: newsStore.category,
      subCategory: newsStore.subCategory,
      city: newsStore.city,
      province: newsStore.province,
      federalElectoralDistrict: newsStore.federalElectoralDistrict,
      subnationalElectoralDistrict: newsStore.subnationalElectoralDistrict
    })
    newsStore.showSaveMessage = true
    setTimeout(() => {
      newsStore.showSaveMessage = false
    }, 2000)  // Show the save message for 2 seconds
  } catch (error) {
    console.error('Error caching content:', error)
  }
}, 5000) // Throttle delay set to 5 seconds

const editorInstance = new Editor({
  content: newsStore.content,
  extensions: [
    StarterKit,
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
    Placeholder.configure({
      placeholder: 'Write something …',
    }),
  ],
  onUpdate: ({ editor }) => {
    newsStore.content = editor.getHTML()
    saveContent()
  },
  autofocus: true,
  editorProps: {
    attributes: {
      class: 'h-auto min-h-[12rem] max-h-[96rem] overflow-y-auto block w-full p-2.5 focus:outline-none',
    },
  },
})

editor.value = editorInstance

onMounted(() => {
  editor.value.commands.setContent(newsStore.content)

})

// Ensure editor is destroyed properly on component unmount
onBeforeUnmount(() => {
  isUnmounting.value = true
  if (editor.value) {
    editor.value.destroy()
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

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}

.save-message {
  font-size: 1.5rem;
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
