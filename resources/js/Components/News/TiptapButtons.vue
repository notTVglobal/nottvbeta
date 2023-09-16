<template>
    <div>
        <button @click.prevent="editor.chain().focus().toggleBold().run()" class="py-1 px-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-bold" :class="{ 'bg-gray-600 text-white': editor.isActive('bold') }">
            B
        </button>
        <button @click.prevent="editor.chain().focus().toggleItalic().run()" class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic" :class="{ 'bg-gray-600 text-white': editor.isActive('italic') }">
            i
        </button>
        <button @click.prevent="editor.chain().focus().toggleHeading({ level: 1 }).run()" class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic" :class="{ 'bg-gray-600 text-white': editor.isActive('heading', { level: 1}) }">
            h1
        </button>
        <button @click.prevent="editor.chain().focus().toggleHeading({ level: 2 }).run()" class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic" :class="{ 'bg-gray-600 text-white': editor.isActive('heading', { level: 2}) }">
            h2
        </button>
        <button @click.prevent="editor.chain().focus().toggleHeading({ level: 3 }).run()" class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic" :class="{ 'bg-gray-600 text-white': editor.isActive('heading', { level: 3}) }">
            h3
        </button>
    </div>

</template>

<script setup>

defineProps({
    editor: Object,
})

</script>
