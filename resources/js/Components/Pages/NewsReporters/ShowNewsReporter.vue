<template>
  <main class="pb-8 mx-auto px-4 border-b border-gray-800">
    <div class="flex bg-gray-200 my-10 mx-auto p-5 w-[calc(100%-96)] rounded justify-center text-gray-900">
      <div class="flex flex-col md:flex-row m-6">
        <div class="w-full flex justify-center lg:justify-start">
          <img
              alt="News Reporter Profile Picture"
              v-if="$page.props.newsPerson.profile_photo_path"
              :src="`/storage/${$page.props.newsPerson.profile_photo_path}`"
              class="h-64 w-96 object-cover shadow-lg rounded-lg"
          >
        </div>

        <div class="w-fit mt-4 lg:mt-0 ml-2">
          <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $page.props.newsPerson.name }}
          </h1>
          <div class="space-y-4 mt-4">
            <!-- Sections for Biography, Past Stories, Contact -->
            <section>
              <h2 class="text-xl font-semibold">Biography</h2>
              <p class="text-sm italic text-gray-700">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
              </p>
            </section>
            <section>
              <h2 class="text-xl font-semibold">Past Stories</h2>
              <p class="text-sm italic text-gray-700">
                Story details go here.
              </p>
            </section>
            <section>
              <h2 class="text-xl font-semibold">Contact</h2>
              <p class="text-sm italic text-gray-700">
                Contact information goes here.
              </p>
            </section>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
<script setup>
</script>