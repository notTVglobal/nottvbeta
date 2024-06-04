<template>
  <div>
    <ShowCreators :creator="creator"/>
  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup';
import ShowCreators from '@/Components/Pages/Creators/ShowCreators.vue'
import { defineProps } from 'vue'

const props = defineProps({
  creator: Object,
});

usePageSetup('creator.' + props.creator.id);

</script>
