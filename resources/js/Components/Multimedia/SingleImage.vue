<template>
    <div>
        <img v-if="!props.image.folder"
            :src="'/storage/images/' + props.poster"
             :alt="props.alt" :class="props.class" />
        <img v-if="props.image.folder"
             :src="props.image.cdn_endpoint+'/'+props.image.folder+'/'+props.image.name"
             :alt="props.alt"
             :class="props.class" />
    </div>
</template>

<script setup>
let props = defineProps({
    image: Object,
    poster: String,
    class: String,
    alt: String,
})
</script>
