<template>
  <div class="relative">
    <button @click.stop="showBannedUsers" class="manage-banned-users-button text-3xl" tabindex="0">🕵️‍♂️</button>
    <AdminModal :title="`Banned Users`" :isVisible="showModal" @close="closeModal">
      <ul>
        <li v-for="user in bannedUsers" :key="user.id" class="mb-2 flex justify-between items-center">
          <div>
            <strong>{{ user.name }}</strong>
            <p>Banned until: {{ user.ban_expires_at ? new Date(user.ban_expires_at).toLocaleString() : 'Permanently' }}</p>
          </div>
          <button @click.prevent="unbanUser(user.id)" class="unban-button">Unban</button>
        </li>
      </ul>
    </AdminModal>
  </div>
</template>
<script setup>
import { computed, ref } from 'vue'
import { useAdminStore } from '@/Stores/AdminStore';
import AdminModal from '@/Components/Global/Modals/AdminModal.vue'

const adminStore = useAdminStore();
const showModal = ref(false);

// Use computed property to bind bannedUsers directly from the store
const bannedUsers = computed(() => adminStore.bannedUsers);

const showBannedUsers = async () => {
  adminStore.showShadowBanButton()
  await adminStore.fetchBannedUsers();
  showModal.value = true;
};

const unbanUser = async (userId) => {
  await adminStore.unbanUser(userId);
  await adminStore.showBannedUsers(); // Refresh the list
  if (adminStore.bannedUsers <= 0) {
    showModal.value = false;
    adminStore.hideShadowBanButton()
  }
};

const closeModal = () => {
  showModal.value = false
  adminStore.hideShadowBanButton()
}

</script>
<style scoped>
.manage-banned-users-button {
  background-color: #1f2937; /* Gray-900 */
  color: #f9fafb; /* Gray-50 */
  border-radius: 0.25rem;
  transition: background-color 0.3s ease;
  position: absolute;
  top: 0;
  left: -20px;
  margin: 0.5rem;
}

.manage-banned-users-button:hover {
  background-color: #4b5563; /* Gray-700 */
}

.unban-button {
  background-color: #10b981; /* Green-500 */
  color: #fff;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  transition: background-color 0.3s ease;
}

.unban-button:hover {
  background-color: #059669; /* Green-600 */
}
</style>