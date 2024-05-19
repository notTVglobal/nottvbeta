<template>
  <Head title="Fallback"/>
  <div class="min-h-screen flex flex-col justify-between bg-gray-900">
    <div class="flex flex-col items-center justify-center flex-grow mt-16 bg-gray-900">
      <PublicResponsiveNavigationMenu/>
      <PublicNavigationMenu/>
      <div class="w-full max-w-md mx-auto p-4">
        <div v-if="flashMessage" :class="alertClass" role="alert">
          <svg v-if="icon" :class="iconClass" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path :d="iconPath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
          </svg>
          <span>{{ flashMessage }}</span>
        </div>
        <p>This is the fallback page.</p>
      </div>
    </div>
    <Footer/>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import { icon } from '@fortawesome/fontawesome-svg-core'

const appSettingStore = useAppSettingStore()
appSettingStore.noLayout = true
appSettingStore.currentPage = 'fallback'

const props = defineProps({
  flash: Object,
})

const flashMessage = computed(() => props.flash.error || props.flash.warning || props.flash.message || props.flash.success)

const alertClass = computed(() => {
  if (props.flash.error) return 'alert alert-error'
  if (props.flash.warning) return 'alert alert-warning'
  if (props.flash.message) return 'alert alert-info'
  if (props.flash.success) return 'alert alert-success'
  return ''
})

const iconClass = computed(() => {
  if (props.flash.error) return 'stroke-error shrink-0 w-6 h-6'
  if (props.flash.warning) return 'stroke-warning shrink-0 w-6 h-6'
  if (props.flash.message) return 'stroke-info shrink-0 w-6 h-6'
  if (props.flash.success) return 'stroke-success shrink-0 w-6 h-6'
  return ''
})

const iconPath = computed(() => {
  if (props.flash.error) return 'M6 18L18 6M6 6l12 12'
  if (props.flash.warning) return 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
  if (props.flash.message) return 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
  if (props.flash.success) return 'M9 12l2 2 4-4m0 6a9 9 0 11-18 0 9 9 0 0118 0z'
  return ''
})
</script>