<template>
  <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

    <!-- Responsive 16:9 Video Placeholder -->
    <div class="relative w-3/4 mx-auto">
      <div class="skeleton" style="padding-top: 56.25%;"></div>
    </div>

    <!-- Get Started Button -->
    <div class="flex justify-center my-8">
      <button @click="goToCreatorRegister" class="btn btn-lg w-3/4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg border-blue-500 hover:border-blue-700 focus:outline-none focus:shadow-outline" role="button">
        Get Started
      </button>
    </div>

    <div class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-10 w-full md:w-3/4 text-xl text-gray-900 rounded-lg">

      <p class="mb-4">🚀 You've landed an exclusive invite to notTV, where broadcasters unite to co-create the next generation of media streaming. A world brimming with creativity, stories, and dreams awaits you. We're delighted to have you test our beta site and join our vibrant community of storytellers, artists, and dreamers. Welcome aboard!</p>

      <p class="mb-4">🌟 notTV is a place where every voice matters, and the power of shared stories can inspire, entertain, and educate. Armed with a suite of tools and a supportive platform, there's no limit to what you can create. You're joining a family of creators eager to make their mark on the world, and we can't wait to see the stories you'll share.</p>

      <p>🔑 Your exclusive invite is your key to an incredible journey of creation and connection. Dive into content creation, explore new ideas, and engage with a community that cheers you on at every step. Your adventure is just beginning, and we're here to support you every step of the way.</p>

      <p class="mt-4">✨ Ready to unlock your creative potential? Let's make something amazing together! Click below to get started and officially become part of the notTV family.</p>

      <!-- Get Started Button -->
      <div class="flex justify-center mt-8">
        <button @click="goToCreatorRegister" class="btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline" role="button">
          Get Started
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
  inviteCodeUlid: String
})

const goToCreatorRegister = () => {
  router.visit(route('creator.register.show', props.inviteCodeUlid))
}
</script>
