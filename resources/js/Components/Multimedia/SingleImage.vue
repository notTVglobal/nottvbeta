<template>
    <div>
        <img v-if="!props.image.cdn_endpoint"
            :src="'/storage/images/' + props.image.name"
             :alt="props.alt" :class="props.class" />
        <img v-if="props.image.cdn_endpoint"
             :src="props.image.cdn_endpoint+'/'+props.image.folder+'/'+props.image.name"
             :alt="props.alt"
             :class="props.class" />
    </div>
</template>

<script setup>
let props = defineProps({
    image: Object,
    class: String,
    alt: String,
})
</script>
