<template>
    <div>
        <img v-if="!image.folder"
             :src="'/storage/images/' + image.name"
             :alt="alt"
             :class="class"/>

        <img v-if="image.folder"
             :src="image.cdn_endpoint + image.cdn_folder + image.folder + '/' + image.name"
             :alt="alt"
             :class="class"/>
    </div>
</template>

<script setup>
defineProps({
    image: Object,
    class: String,
    alt: String,
})
</script>
