<template>
  <div>
    <h1>Show Creator Message</h1>
    <p>Sender ID: {{ creatorMessage.sender_id }}</p>
    <p>Recipient ID: {{ creatorMessage.recipient_id }}</p>
    <p>Message: {{ creatorMessage.message }}</p>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  creatorMessage: Object,
});
</script>
