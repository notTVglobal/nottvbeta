<template>
  <div>
    <button @click.prevent="editor.chain().focus().toggleBold().run()"
            class="py-1 px-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-bold"
            :class="{ 'bg-gray-600 text-white': editor.isActive('bold') }">
      B
    </button>
    <button @click.prevent="editor.chain().focus().toggleItalic().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('italic') }">
      i
    </button>
    <button @click.prevent="editor.chain().focus().toggleHeading({ level: 1 }).run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('heading', { level: 1}) }">
      h1
    </button>
    <button @click.prevent="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('heading', { level: 2}) }">
      h2
    </button>
    <button @click.prevent="editor.chain().focus().toggleHeading({ level: 3 }).run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('heading', { level: 3}) }">
      h3
    </button>
    <button @click.prevent="editor.chain().focus().toggleBulletList().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('bulletList') }">
      bullet list
    </button>
    <button @click.prevent="editor.chain().focus().toggleOrderedList().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('orderedList') }">
      ordered list
    </button>
    <button @click.prevent="editor.chain().focus().toggleCodeBlock().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('codeBlock') }">
      code block
    </button>
    <button @click.prevent="editor.chain().focus().toggleBlockquote().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('blockquote') }">
      blockquote
    </button>
    <button @click.prevent="editor.chain().focus().setHorizontalRule().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic">
      horizontal rule
    </button>
    <button @click.prevent="editor.chain().focus().undo().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 disabled:hover:bg-gray-200 disabled:cursor-not-allowed disabled:text-black rounded-lg font-italic"
            :disabled="!editor.can().chain().focus().undo().run()">
      undo
    </button>
    <button @click.prevent="editor.chain().focus().redo().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 disabled:hover:bg-gray-200 disabled:cursor-not-allowed disabled:text-black rounded-lg font-italic"
            :disabled="!editor.can().chain().focus().redo().run()">
      redo
    </button>
  </div>

</template>

<script setup>

defineProps({
  editor: Object,
})

</script>
