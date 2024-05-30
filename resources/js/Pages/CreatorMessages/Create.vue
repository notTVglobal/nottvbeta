<template>
  <div>
    <h1>Create Creator Message</h1>
    <form @submit.prevent="submit">
      <div>
        <label for="sender_id">Sender ID:</label>
        <input v-model="form.sender_id" type="text" id="sender_id" />
      </div>
      <div>
        <label for="recipient_id">Recipient ID:</label>
        <input v-model="form.recipient_id" type="text" id="recipient_id" />
      </div>
      <div>
        <label for="message">Message:</label>
        <textarea v-model="form.message" id="message"></textarea>
      </div>
      <button type="submit">Save</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  sender_id: '',
  recipient_id: '',
  message: '',
});

function submit() {
  form.post(route('creator-messages.store'));
}
</script>
