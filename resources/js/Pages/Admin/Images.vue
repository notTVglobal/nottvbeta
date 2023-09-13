<template>
    <Head title="Image uploading"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-900 dark:text-gray-50 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <AdminHeader>Images</AdminHeader>

            <div class="max-w-lg mx-auto mt-2 bg-gray-200 p-6">
                <h3 class="text-2xl font-bold text-center dark:text-white">Image Uploader</h3>

                <ImageUpload :image="images"
                             :server="'/upload'"
                             :name="''"
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
import { onBeforeMount, onMounted, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from '@inertiajs/inertia-vue3'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Pagination from "@/Components/Pagination.vue"
import Message from "@/Components/Modals/Messages.vue";
import SingleImage from "@/Components/Multimedia/SingleImage.vue"
import ImageUpload from "@/Components/Uploaders/ImageUpload.vue"
import AdminHeader from "@/Components/Admin/AdminHeader.vue";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'imageUploader';

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {
    //
    //     userStore.scrollToTopCounter ++;
    // }
});

let props = defineProps({
    images: Object,
    message: String,
    // filters: Object,
});

function reloadImage() {
    Inertia.reload({
        only: ['images'],
    });
}


let showMessage = ref(true);

// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('image', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));

// config: { headers: function () { return {} } }

</script>
