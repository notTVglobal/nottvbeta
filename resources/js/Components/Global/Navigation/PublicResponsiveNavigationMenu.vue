<template>
  <div class="lg:hidden fixed top-0 w-full nav-mask border-b-2 border-gray-100">
    <div class="flex justify-between h-16 w-full bg-black nav-mask">
      <div class="flex">
        <!-- Logo -->
        <div class="shrink-0 flex items-center">
          <Link @click="goToWatchPage">
            <JetApplicationMark class="ml-5 block h-9 w-auto"/>
          </Link>
        </div>
        <!-- Hamburger -->
        <div class="absolute top-3 right-4 hamburgerMask">
          <div class="-mr-2 flex items-center lg:hidden z-50">

            <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 transition"
                :class="{ 'hover:text-white hover:bg-blue-600': appSettingStore.showNavDropdown}"

                @click="appSettingStore.toggleNavDropdown()">
              <!--                @click="chatStore.showNavDropdown = ! chatStore.showNavDropdown">-->

              <span class="pr-2">MENU</span>
              <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
              >
                <path
                    :class="{'hidden': appSettingStore.showNavDropdown, 'inline-flex': ! appSettingStore.showNavDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                    :class="{'hidden': ! appSettingStore.showNavDropdown, 'inline-flex': appSettingStore.showNavDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>



      </div>
    </div>
    <!-- Responsive Navigation Menu -->
    <!--    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"-->
    <div :class="{'block': appSettingStore.showNavDropdown, 'hidden': ! appSettingStore.showNavDropdown}"
         class="lg:hidden bg-gray-800 text-white fixed w-full h-full">
      <!-- Responsive Settings Options -->
      <!--   Fix Menu height e.g., h-[calc(h-100%-16rem)]      -->
      <div ref="scrollableDiv" class="pb-20 mb- h-[calc(100vh)] overflow-y-auto hide-scrollbar" @scroll="handleScroll">

        <div class="space-y-1 z-50 mb-6 pb-10 bg-gray-900 border-t border-1 border-white">

          <JetResponsiveNavLink
              @click="goToWatchPage"
              :href="route('home')"
              :active="route().current('home')">
            Watch Now
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('home')"
              :active="route().current('home')">
            Browse
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('news.index')"
              :active="route().current('news.index')">
            News Stories
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('news.reporters.index')"
              :active="route().current('news.reporters.index')">
            Reporters
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('public.newsletterSignup')"
              :active="route().current('public.newsletterSignup')">
            Get Exclusive Access!
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('public.contact')"
              :active="route().current('public.contact')">
            Contact Us
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="`/register`">
            Register
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="`/login`">
            Login
          </JetResponsiveNavLink>

        </div>


<!--        <div class="fixed bottom-0 pb-8 flex flex-col w-full space-y-1 text-gray-600 text-sm">-->
<!--          <div class="fixed bottom-0 w-full text-gray-600 text-sm pb-8">-->
            <div class="flex flex-col w-full space-y-1 text-gray-600 text-sm pb-20">
          <AppVersion />
          <!--                    <div class="flex pt-4 justify-center">Please send us comments and questions <a href="https://help.not.tv/" target="_blank" class="text-blue-600 hover:text-blue-40">&nbsp; here</a>.</div>-->
        </div>

      </div>
      <div class="fixed w-full bottom-4 text-center fade-out"
           :class="{ 'visible': !hasScrolled && isContentOverflowing }">Scroll down.
      </div>
    </div>
  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import JetApplicationMark from "@/Jetstream/ApplicationMark"
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { Link } from '@inertiajs/inertia-vue3'
import AppVersion from '@/Components/Global/AppVersion/AppVersion.vue'

const appSettingStore = useAppSettingStore()
const showingNavigationDropdown = ref(false)
const hasScrolled = ref(false)
const isContentOverflowing = ref(false)
const scrollableDiv = ref(null)

const checkOverflow = () => {
  if (scrollableDiv.value) {
    const isOverflowing = scrollableDiv.value.scrollHeight > scrollableDiv.value.clientHeight;
    isContentOverflowing.value = isOverflowing;
  }
};

const handleScroll = () => {
  // Check if the page has been scrolled down
  // const scrollPosition = scrollableDiv.value.scrollTop;
  // hasScrolled.value = scrollPosition > 0;
  hasScrolled.value = scrollableDiv.value.scrollTop > 0;
};

const linkRef = ref(null);

function goToWatchPage() {
  appSettingStore.closeNavDropdown()
  window.location.href = '/';
}

onMounted(() => {
  nextTick(() => {
    checkOverflow();
  });

  window.addEventListener('resize', checkOverflow); // Recheck on window resize

  if (scrollableDiv.value) {
    scrollableDiv.value.addEventListener('scroll', handleScroll);
  }
});

// Watch for changes in the dropdown visibility
watch(() => appSettingStore.showNavDropdown, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    nextTick(() => {
      checkOverflow();
    });
  }
});

// Cleanup
onUnmounted(() => {
  window.removeEventListener('resize', checkOverflow);

  if (scrollableDiv.value) {
    scrollableDiv.value.removeEventListener('scroll', handleScroll);
  }
});


</script>

<style>
.hamburgerMask {
  z-index: 100
}
.fade-out {
  transition: opacity 0.5s ease-in-out;
  opacity: 0;
}

.fade-out.visible {
  opacity: 1;
}
</style>
