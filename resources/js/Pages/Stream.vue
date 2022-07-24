<template>
    <Head title="Stream" />
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->


    <div class="bg-black opacity-25 w-1/3 absolute top-0 right-0 h-full z-20 pt-16 overflow-hidden">
        Insert Chat Box here. It needs to be on top of the video player as an overlay on desktops and tablets, but below the video on mobile devices.
    </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white text-black shadow-xl sm:rounded-lg">
                    <div class="flex m-auto mt-10 mb-10 w-1/2">
                        <div class="py-10 bg-orange-400">If this page does not show a full screen video, then click "Stream" again.</div>
                        <div class="bg-black opacity-25 w-1/3 absolute top-0 right-0 h-max z-20">
                            Insert Chat Box here. It needs to be on top of the video player as an overlay on desktops and tablets, but below the video on mobile devices.
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>


<script setup>

defineProps ({
    video: Object
})

</script>

