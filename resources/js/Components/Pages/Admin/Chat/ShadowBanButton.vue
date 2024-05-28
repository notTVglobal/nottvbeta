<template>
  <div v-if="$page.props.auth.user.isAdmin" class="relative">
    <button v-if="!isUserBanned" @click="showPopup = true" class="manage-ban-button">Ban</button>
    <button v-if="isUserBanned" @click="unbanUser" class="manage-unban-button">Unban</button>
    <div v-if="showPopup" class="popup">
      <button class="close-button" @click="showPopup = false">&times;</button>
      <form @submit.prevent="handleBan">
        <label class="block mb-2 text-sm font-medium text-gray-300">
          Ban Duration (minutes, leave empty for permanent):
          <input type="number" v-model="banDuration" class="input-field mt-1 block w-full">
        </label>
        <div class="flex justify-end space-x-2 mt-4">
          <button type="submit" class="ban-button">Ban</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAdminStore } from '@/Stores/AdminStore';

const props = defineProps({
  message: Object,
});

const adminStore = useAdminStore();
const showPopup = ref(false);
const banDuration = ref(null);

const isUserBanned = computed(() => {
  return adminStore.bannedUsers.some(user => user.id === props.message.user_id);
});

const handleBan = async () => {
  await adminStore.banUser(props.message.user_id, banDuration.value);
  await adminStore.fetchBannedUsers();
  showPopup.value = false;
};

const unbanUser = async () => {
  await adminStore.unbanUser(props.message.user_id);
  await adminStore.fetchBannedUsers();
};

onMounted(async () => {
  await adminStore.fetchBannedUsers();
});
</script>

<style scoped>
.relative {
  position: relative;
}

.manage-ban-button,
.manage-unban-button {
  background-color: #1f2937; /* Gray-900 */
  color: #f9fafb; /* Gray-50 */
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  transition: background-color 0.3s ease;
  font-size: 0.75rem; /* Small font size */
  top: 0;
  right: 0;
  margin: 0.5rem;
}

.manage-ban-button:hover,
.manage-unban-button:hover {
  background-color: #4b5563; /* Gray-700 */
}

.popup {
  position: fixed;
  bottom: 10px;
  right: 10px;
  background-color: #111827; /* Gray-900 */
  border: 1px solid #4b5563; /* Gray-700 */
  padding: 1rem;
  border-radius: 0.25rem;
  z-index: 100;
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
  color: #f9fafb; /* Gray-50 */
  width: 300px; /* Set a fixed width for the popup */
}

.close-button {
  background: none;
  border: none;
  color: #f9fafb; /* Gray-50 */
  font-size: 1.5rem;
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  cursor: pointer;
  transition: color 0.3s ease;
}

.close-button:hover {
  color: #ef4444; /* Red-500 */
}

.input-field {
  background-color: #1f2937; /* Gray-900 */
  color: #f9fafb; /* Gray-50 */
  border: 1px solid #4b5563; /* Gray-700 */
  border-radius: 0.25rem;
  padding: 0.5rem;
}

.ban-button {
  background-color: #ef4444; /* Red-500 */
  color: #fff;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  transition: background-color 0.3s ease;
}

.ban-button:hover {
  background-color: #dc2626; /* Red-600 */
}

.unban-button {
  background-color: #10b981; /* Green-500 */
  color: #fff;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  transition: background-color 0.3s ease;
}

.unban-button:hover {
  background-color: #059669; /* Green-600 */
}
</style>
