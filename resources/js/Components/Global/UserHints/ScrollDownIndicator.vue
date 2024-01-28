<template>
  <div class="scrolldownIndicator">
    <div :class="{ 'visible': !hasScrolled && isContentOverflowing }"
         class="fade-out bg-black bg-opacity-80 text-white">
      Scroll down.
    </div>
  </div>
</template>

<script setup>
// Provide a Reference to the Scrollable Div in the parent component:
// import { ref, provide } from 'vue';
// const scrollRef = ref(null);
// provide('scrollRef', scrollRef);
import { ref, onMounted, onUnmounted, watch, nextTick, inject } from "vue"
import { useChannelStore } from "@/Stores/ChannelStore"

const channelStore = useChannelStore()

const props = defineProps({
  watchedState: Boolean,
  triggerCheck: Boolean,
  scrollRef: Object
});

// Inject the ref provided by the parent component
const scrollableDiv = inject('scrollRef')

const hasScrolled = ref(false)
const isContentOverflowing = ref(false)

const checkOverflow = () => {
  if (scrollableDiv.value) {
    isContentOverflowing.value = scrollableDiv.value.scrollHeight > scrollableDiv.value.clientHeight;
    // console.log('Is overflowing:', isOverflowing); // Check if the overflow is detected
  }
};

const handleScroll = () => {
  // Check if the page has been scrolled down
  // const scrollPosition = scrollableDiv.value.scrollTop;
  // hasScrolled.value = scrollPosition > 0;
  hasScrolled.value = scrollableDiv.value.scrollTop > 0;
  console.log('Scrolled:', hasScrolled.value); // Check if scrolling is detected
};


let resizeObserver;

onMounted(() => {
  console.log(scrollableDiv.value); // Check if the ref is correctly injected
  nextTick(() => {
    checkOverflow();
  });

  window.addEventListener('resize', checkOverflow); // Recheck on window resize

  if (scrollableDiv.value) {
    resizeObserver = new ResizeObserver(entries => {
      for (let entry of entries) {
        checkOverflow();
      }
    });
    resizeObserver.observe(scrollableDiv.value);
    scrollableDiv.value.addEventListener('scroll', handleScroll);
  }
});

// Watch for changes in the div visibility
// watch(() => channelStore.channel_list, (newValue, oldValue) => {
//     if (newValue !== oldValue) {
//         nextTick(() => {
//             checkOverflow();
//         });
//     }
// });

// Cleanup
onUnmounted(() => {
  window.removeEventListener('resize', checkOverflow);

  if (resizeObserver) {
    resizeObserver.disconnect();
  }

  if (scrollableDiv.value) {
    scrollableDiv.value.removeEventListener('scroll', handleScroll);
  }
});
</script>

<style scoped>
.fade-out {
  transition: opacity 0.5s ease-in-out;
  opacity: 0;
}

.fade-out.visible {
  opacity: 1;
}
</style>
