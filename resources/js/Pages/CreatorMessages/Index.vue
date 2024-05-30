<template>
  <div>
    <h1>Creator Messages</h1>
    <ul>
      <li v-for="message in messages" :key="message.id">
        <router-link :to="`creator-messages/${message.id}`">
          {{ message.message }}
        </router-link>
      </li>
    </ul>
    <router-link :to="{ name: 'creator-messages.create' }">Create New Message</router-link>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  messages: Array,
});
</script>
