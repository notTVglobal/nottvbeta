<template>

  <Head title="Creator Invite Introduction"/>
  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
      <PublicResponsiveNavigationMenu />
    </div>

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">

      <div class="flex flex-col mt-36 mb-16 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
        <div><ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/></div>
        <div class="uppercase">
          Welcome to Your Next Adventure in Creativity!
        </div>
      </div>
      <main class="pb-8  hide-scrollbar">
        <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

          <!-- Responsive 16:9 Video Placeholder -->
          <div class="relative w-3/4 mx-auto">
            <div class="skeleton" style="padding-top: 56.25%;"></div>
          </div>

          <!-- Get Started Button -->
          <div class="flex justify-center my-8">
            <button @click="goToCreatorRegister" class="btn btn-lg w-3/4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded border-blue-500 hover:border-blue-700 focus:outline-none focus:shadow-outline" role="button">
              Get Started
            </button>
          </div>

          <div class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-10 w-full md:w-3/4 text-xl text-gray-900 rounded">

            <p class="mb-4">You've landed an exclusive invite to notTV, where broadcasters unite to co-create the next generation of media streaming. A world brimming with creativity, stories, and dreams awaits you. We're delighted to have you test our beta site and join our vibrant community of storytellers, artists, and dreamers. Welcome aboard!</p>

            <p class="mb-4">notTV is a place where every voice matters, and the power of shared stories can inspire, entertain, and educate. Armed with a suite of tools and a supportive platform, there's no limit to what you can create. You're joining a family of creators eager to make their mark on the world, and we can't wait to see the stories you'll share.</p>

            <p>Your exclusive invite is your key to an incredible journey of creation and connection. Dive into content creation, explore new ideas, and engage with a community that cheers you on at every step. Your adventure is just beginning, and we're here to support you every step of the way.</p>

            <p class="mt-4">Ready to unlock your creative potential? Let's make something amazing together! Click below to get started and officially become part of the notTV family.</p>

            <!-- Get Started Button -->
            <div class="flex justify-center mt-4">
              <button @click="goToCreatorRegister" class="btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" role="button">
                Get Started
              </button>
            </div>

          </div>




        </div>
      </main>

      <Footer />

    </div>
  </div>
</template>

<script setup>
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

import Footer from '@/Components/Global/Layout/Footer.vue'
import { Inertia } from '@inertiajs/inertia'
import {Link} from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import AuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import { onMounted } from 'vue'


const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'creator.invite.show'

const props = defineProps({
  inviteCode: String,
})

const goToCreatorRegister = () => {
  Inertia.visit(route('creator.register.show', props.inviteCode))
}



onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
});
</script>
