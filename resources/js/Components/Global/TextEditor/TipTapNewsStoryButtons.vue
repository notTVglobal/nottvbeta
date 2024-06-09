<template>
  <div>
    <div class="toolbar mb-2">
      <div class="flex flex-wrap gap-1 mb-2">
        <!-- Text formatting buttons -->
        <div class="button-group">
          <button tabindex="-1" @click.prevent="toggleBold" class="icon-btn" :class="{ 'active': isBoldActive }">
            <font-awesome-icon :icon="['fas', 'bold']" />
          </button>
          <button tabindex="-1" @click.prevent="toggleItalic" class="icon-btn" :class="{ 'active': isItalicActive }">
            <font-awesome-icon :icon="['fas', 'italic']" />
          </button>
          <button tabindex="-1" @click.prevent="toggleUnderline" class="icon-btn" :class="{ 'active': isUnderlineActive }">
            <font-awesome-icon :icon="['fas', 'underline']" />
          </button>
          <button tabindex="-1" @click.prevent="toggleStrike" class="icon-btn" :class="{ 'active': isStrikeActive }">
            <font-awesome-icon :icon="['fas', 'strikethrough']" />
          </button>
          <button tabindex="-1" @click.prevent="toggleSuperscript" class="icon-btn" :class="{ 'active': isSuperscriptActive }">
            <font-awesome-icon :icon="['fas', 'superscript']" />
          </button>
          <button tabindex="-1" @click.prevent="toggleSubscript" class="icon-btn" :class="{ 'active': isSubscriptActive }">
            <font-awesome-icon :icon="['fas', 'subscript']" />
          </button>
        </div>

        <!-- Heading buttons -->
        <div class="button-group">
          <button tabindex="-1" @click.prevent="toggleHeading(1)" class="icon-btn" :class="{ 'active': isHeadingActive(1) }">
            H1
          </button>
          <button tabindex="-1" @click.prevent="toggleHeading(2)" class="icon-btn" :class="{ 'active': isHeadingActive(2) }">
            H2
          </button>
          <button tabindex="-1" @click.prevent="toggleHeading(3)" class="icon-btn" :class="{ 'active': isHeadingActive(3) }">
            H3
          </button>
        </div>

        <!-- List buttons -->
        <div class="button-group">
          <button tabindex="-1" @click.prevent="toggleBulletList" class="icon-btn" :class="{ 'active': isBulletListActive }">
            <font-awesome-icon :icon="['fas', 'list-ul']" />
          </button>
          <button tabindex="-1" @click.prevent="toggleOrderedList" class="icon-btn" :class="{ 'active': isOrderedListActive }">
            <font-awesome-icon :icon="['fas', 'list-ol']" />
          </button>
        </div>

        <!-- Link buttons -->
        <div class="button-group">
          <button tabindex="-1" @click.prevent="setLink" class="icon-btn" :class="{ 'active': isLinkActive }">
            <font-awesome-icon :icon="['fas', 'link']" />
          </button>
          <button tabindex="-1" @click.prevent="unsetLink" class="icon-btn" :class="{ 'disabled': !isLinkActive }" :disabled="!isLinkActive">
            <font-awesome-icon :icon="['fas', 'unlink']" />
          </button>
        </div>

        <!-- Code and blockquote buttons -->
        <div class="button-group">
          <button tabindex="-1" @click.prevent="toggleCodeBlock" class="icon-btn" :class="{ 'active': isCodeBlockActive }">
            <font-awesome-icon :icon="['fas', 'code']" />
          </button>
          <button tabindex="-1" @click.prevent="toggleBlockquote" class="icon-btn" :class="{ 'active': isBlockquoteActive }">
            <font-awesome-icon :icon="['fas', 'quote-right']" />
          </button>
          <button tabindex="-1" @click.prevent="setHorizontalRule" class="icon-btn">
            <font-awesome-icon :icon="['fas', 'minus']" />
          </button>
        </div>

        <!-- Undo/redo buttons -->
        <div class="button-group">
          <button tabindex="-1" @click.prevent="undo" class="icon-btn" :class="{ 'disabled': !canUndo }" :disabled="!canUndo">
            <font-awesome-icon :icon="['fas', 'undo']" />
          </button>
          <button tabindex="-1" @click.prevent="redo" class="icon-btn" :class="{ 'disabled': !canRedo }" :disabled="!canRedo">
            <font-awesome-icon :icon="['fas', 'redo']" />
          </button>
        </div>

        <!-- Alignment buttons -->
        <div class="button-group">
          <button tabindex="-1" @click.prevent="setTextAlign('left')" class="icon-btn" :class="{ 'active': isTextAlignLeft }">
            <font-awesome-icon :icon="['fas', 'align-left']" />
          </button>
          <button tabindex="-1" @click.prevent="setTextAlign('center')" class="icon-btn" :class="{ 'active': isTextAlignCenter }">
            <font-awesome-icon :icon="['fas', 'align-center']" />
          </button>
          <button tabindex="-1" @click.prevent="setTextAlign('right')" class="icon-btn" :class="{ 'active': isTextAlignRight }">
            <font-awesome-icon :icon="['fas', 'align-right']" />
          </button>
          <button tabindex="-1" @click.prevent="setTextAlign('justify')" class="icon-btn" :class="{ 'active': isTextAlignJustify }">
            <font-awesome-icon :icon="['fas', 'align-justify']" />
          </button>
        </div>

        <!-- Color and font buttons -->
        <div class="button-group">
          <button tabindex="-1" @click.prevent="showColorPicker" class="icon-btn">
            <font-awesome-icon :icon="['fas', 'palette']" />
          </button>

          <button tabindex="-1" @click.prevent="toggleFontFamily" class="icon-btn">
            <font-awesome-icon :icon="['fas', 'font']" />
          </button>
        </div>
      </div>
    </div>
    <ColorPicker
        :visible="colorPickerVisible"
        :color="currentColor"
        @close="hideColorPicker"
        @apply="applyColor"
        @update:color="currentColor = $event"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import ColorPicker from '@/Components/Global/Text/ColorPicker.vue'

const props = defineProps({
  editor: Object,
})

const colorPickerVisible = ref(false)
const currentColor = ref('#000000')

const showColorPicker = () => {
  colorPickerVisible.value = true
}

const hideColorPicker = () => {
  colorPickerVisible.value = false
}

const applyColor = (color) => {
  console.log(color)
  currentColor.value = color
  if (props.editor) {
    props.editor.chain().focus().setColor(color).run()
  }
}

const toggleBold = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleBold().run()
  }
}

const toggleItalic = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleItalic().run()
  }
}

const toggleUnderline = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleUnderline().run()
  }
}

const toggleSuperscript = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleSuperscript().run()
  }
}

const toggleSubscript = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleSubscript().run()
  }
}

const toggleBulletList = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleBulletList().run()
  }
}

const toggleOrderedList = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleOrderedList().run()
  }
}

const toggleCodeBlock = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleCodeBlock().run()
  }
}

const toggleBlockquote = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleBlockquote().run()
  }
}

const setHorizontalRule = () => {
  if (props.editor) {
    props.editor.chain().focus().setHorizontalRule().run()
  }
}

const toggleStrike = () => {
  if (props.editor) {
    props.editor.chain().focus().toggleStrike().run()
  }
}

const undo = () => {
  if (props.editor) {
    props.editor.chain().focus().undo().run()
  }
}

const redo = () => {
  if (props.editor) {
    props.editor.chain().focus().redo().run()
  }
}

const setTextAlign = (alignment) => {
  if (props.editor) {
    props.editor.chain().focus().setTextAlign(alignment).run()
  }
}

const toggleFontFamily = () => {
  // This is where you can implement the logic for changing font family
  // You can use a dropdown menu or a predefined set of fonts
  const fontOptions = [
    'Comic Sans MS, Comic Sans',
    'monospace',
    'Lucida Handwriting, Pacifico, Brush Script MT, cursive',
    'unset'
  ];
  const currentFont = props.editor.getAttributes('textStyle').fontFamily
  const nextFont = fontOptions[(fontOptions.indexOf(currentFont) + 1) % fontOptions.length]

  if (nextFont === 'unset') {
    props.editor.chain().focus().unsetFontFamily().run()
  } else {
    props.editor.chain().focus().setFontFamily(nextFont).run()
  }
}

const toggleHeading = (level) => {
  if (props.editor) {
    props.editor.chain().focus().toggleHeading({ level }).run()
  }
}

const isBoldActive = computed(() => props.editor && props.editor.isActive('bold'))
const isItalicActive = computed(() => props.editor && props.editor.isActive('italic'))
const isUnderlineActive = computed(() => props.editor && props.editor.isActive('underline'))
const isSuperscriptActive = computed(() => props.editor && props.editor.isActive('superscript'))
const isSubscriptActive = computed(() => props.editor && props.editor.isActive('subscript'))
const isBulletListActive = computed(() => props.editor && props.editor.isActive('bulletList'))
const isOrderedListActive = computed(() => props.editor && props.editor.isActive('orderedList'))
const isLinkActive = computed(() => props.editor && props.editor.isActive('link'))
const isCodeBlockActive = computed(() => props.editor && props.editor.isActive('codeBlock'))
const isBlockquoteActive = computed(() => props.editor && props.editor.isActive('blockquote'))
const isStrikeActive = computed(() => props.editor && props.editor.isActive('strike'))
const canUndo = computed(() => props.editor && props.editor.can().undo())
const canRedo = computed(() => props.editor && props.editor.can().redo())
const isTextAlignLeft = computed(() => props.editor && props.editor.isActive({ textAlign: 'left' }))
const isTextAlignCenter = computed(() => props.editor && props.editor.isActive({ textAlign: 'center' }))
const isTextAlignRight = computed(() => props.editor && props.editor.isActive({ textAlign: 'right' }))
const isTextAlignJustify = computed(() => props.editor && props.editor.isActive({ textAlign: 'justify' }))
const isHeadingActive = (level) => computed(() => props.editor && props.editor.isActive('heading', { level }))

const setLink = () => {
  if (props.editor) {
    const previousUrl = props.editor.getAttributes('link').href
    const url = window.prompt('URL', previousUrl)
    if (url === null) return

    if (url === '') {
      props.editor.chain().focus().extendMarkRange('link').unsetLink().run()
    } else {
      props.editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
    }
  }
}
</script>

<style scoped>
.toolbar {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.button-group {
  display: flex;
  gap: 8px;
  margin-right: 16px;
}

.icon-btn {
  background-color: #4a5568;
  color: white;
  padding: 8px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  transition: background-color 0.3s, transform 0.3s;
}

.icon-btn.active {
  background-color: #2d3748;
}

.icon-btn:hover {
  background-color: #2d3748;
  transform: scale(1.1);
}

.icon-btn.disabled {
  background-color: #a0aec0;
  cursor: not-allowed;
}

.icon-btn i {
  font-size: 16px;
}
</style>
