<template>
  <Head title="Image uploading"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-900 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <AdminHeader>Images</AdminHeader>

      <div class="max-w-lg mx-auto mt-2 bg-gray-200 p-6">
        <h3 class="text-2xl font-bold text-center dark:text-white">Image Uploader</h3>

        <ImageUpload :image="images"
                     :modelType="null"
                     :modelId="null"
                     :name="'Generic Image Upload'"
                     :maxSize="'10MB'"
                     :fileTypes="'image/jpg, image/jpeg, image/png'"
                     @reloadImage="reloadImage"
        />

      </div>

      <div class="mt-8 mb-24 mx-auto w-[calc(100%-96)] py-10">
        <h3 class="text-2xl font-bold text-center dark:text-white">Image Gallery</h3>
        <!--        <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />-->
        <!-- Paginator -->
        <Pagination :data="images" class="mt-6"/>
        <div class="w-full p-4 flex flex-initial flex-wrap gap-6 justify-center mt-4">
          <div v-for="image in images.data" :key="image">
            <!--                <img :src="'/storage/images/' + image.name" class="max-w-xs">-->
            <SingleImage :image="image" :alt="'show cover'" class="max-w-xs"/>


          </div>
        </div>
        <!-- Paginator -->
        <Pagination :data="images" class="mt-6"/>
      </div>

    </div>
  </div>
</template>


<script setup>
import { router } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import AdminHeader from "@/Components/Pages/Admin/AdminHeader"
import Pagination from "@/Components/Global/Paginators/Pagination"
import Message from "@/Components/Global/Modals/Messages"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import ImageUpload from "@/Components/Global/Uploaders/ImageUpload"

usePageSetup('Admin/Images')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  images: Object,
  message: String,
  // filters: Object,
});

function reloadImage() {
  router.reload({
    only: ['images'],
  });
}


// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     router.get('image', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));

// config: { headers: function () { return {} } }

</script>
