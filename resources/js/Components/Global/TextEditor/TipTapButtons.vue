<template>
  <div>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleBold().run()"
            class="py-1 px-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-bold"
            :class="{ 'bg-gray-600 text-white': editor.isActive('bold') }">
      B
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleItalic().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('italic') }">
      i
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleHeading({ level: 1 }).run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('heading', { level: 1}) }">
      h1
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('heading', { level: 2}) }">
      h2
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleHeading({ level: 3 }).run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('heading', { level: 3}) }">
      h3
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().setTextAlign('left').run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive({ textAlign: 'left' }) }">
      left
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().setTextAlign('center').run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive({ textAlign: 'center' }) }">
      center
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().setTextAlign('right').run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive({ textAlign: 'right' }) }">
      right
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().setTextAlign('justify').run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive({ textAlign: 'justify' }) }">
      justify
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleBulletList().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('bulletList') }">
      bullet list
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleOrderedList().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('orderedList') }">
      ordered list
    </button>


<!--    currently tipTap is readonly-editor. We need to convert JSON to HTML
        then we can enable the link buttons.-->
    <button hidden
            tabindex="-1"
            @click.prevent="setLink"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('link') }">
      setLink
    </button>
    <!--    currently tipTap is readonly-editor. We need to convert JSON to HTML
        then we can enable the link buttons.-->
    <button hidden
            tabindex="-1"
            @click.prevent="editor.chain().focus().unsetLink().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :disabled="!editor.isActive('link')">
      unsetLink
    </button>


    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleCodeBlock().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('codeBlock') }">
      code block
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleBlockquote().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('blockquote') }">
      blockquote
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().setHorizontalRule().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic">
      horizontal rule
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleStrike().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('strike') }">
      strike
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleUnderline().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('underline') }">
      underline
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleSubscript().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('subscript') }">
      subscript
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().toggleSuperscript().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('superscript') }">
      superscript
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().undo().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 disabled:hover:bg-gray-200 disabled:cursor-not-allowed disabled:text-black rounded-lg font-italic"
            :disabled="!editor.can().chain().focus().undo().run()">
      undo
    </button>
    <button tabindex="-1"
            @click.prevent="editor.chain().focus().redo().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 disabled:hover:bg-gray-200 disabled:cursor-not-allowed disabled:text-black rounded-lg font-italic"
            :disabled="!editor.can().chain().focus().redo().run()">
      redo
    </button>
    <!-- Text color dropdown -->
    <button tabindex="-1" @click.prevent="toggleTextColor('#000000')"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic">
      black
    </button>
    <button tabindex="-1" @click.prevent="toggleTextColor('#F98181')"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { color: '#F98181' }) }">
      red
    </button>
    <button tabindex="-1" @click.prevent="toggleTextColor('#70CFF8')"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { color: '#70CFF8' }) }">
      blue
    </button>
    <button tabindex="-1" @click.prevent="toggleTextColor('#958DF1')"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { color: '#958DF1' }) }">
      purple
    </button>
    <button tabindex="-1" @click.prevent="toggleTextColor('#FBBC88')"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { color: '#FBBC88' }) }">
      orange
    </button>
    <button tabindex="-1" @click.prevent="toggleTextColor('#FAF594')"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { color: '#FAF594' }) }">
      yellow
    </button>
    <button tabindex="-1" @click.prevent="toggleTextColor('#94FADB')"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { color: '#94FADB' }) }">
      teal
    </button>
    <button tabindex="-1" @click.prevent="toggleTextColor('#B9F18D')"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { color: '#B9F18D' }) }">
      green
    </button>

    <button tabindex="-1" @click.prevent="editor.chain().focus().setFontFamily('Inter').run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { fontFamily: 'Inter' }) }">
      Inter
    </button>
    <button tabindex="-1" @click.prevent="editor.chain().focus().setFontFamily('Comic Sans MS, Comic Sans').run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { fontFamily: 'Comic Sans MS, Comic Sans' }) }">
      Comic Sans
    </button>
    <button tabindex="-1" @click.prevent="editor.chain().focus().setFontFamily('serif').run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { fontFamily: 'serif' }) }">
      serif
    </button>
    <button tabindex="-1" @click.prevent="editor.chain().focus().setFontFamily('monospace').run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { fontFamily: 'monospace' }) }">
      monospace
    </button>
<!--    Cursive is the same as Comic Sans.... find a different font... -->
    <button hidden
            tabindex="-1"
            @click.prevent="editor.chain().focus().setFontFamily('cursive').run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"
            :class="{ 'bg-gray-600 text-white': editor.isActive('textStyle', { fontFamily: 'cursive' }) }">
      cursive
    </button>
<!--    They can toggle the fonts... we don't really need this do we? -->
    <button hidden
            tabindex="-1"
            @click.prevent="editor.chain().focus().unsetFontFamily().run()"
            class="py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic">
      unsetFontFamily
    </button>
  </div>

</template>

<script setup>

import { ref } from 'vue'

const props = defineProps({
  editor: Object,
})

let textColor = null; // Variable to hold the currently set text color

const toggleTextColor = (color) => {
  if (textColor === color) {
    props.editor.chain().focus().unsetColor().run()
    textColor = null;
  } else {
    props.editor.chain().focus().setColor(color).run()
    textColor = color;
  }
};




const setLink = () => {
  const previousUrl = props.editor.getAttributes('link').href
  const url = window.prompt('URL', previousUrl)
  // cancelled
  if (url === null) {
  }

  // empty
  if (url === '') {
    props.editor
        .chain()
        .focus()
        .extendMarkRange('link')
        .unsetLink()
        .run()
  }

  // update link
  props.editor
      .chain()
      .focus()
      .extendMarkRange('link')
      .setLink({ href: url })
      .run()
}

</script>
