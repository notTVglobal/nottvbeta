<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
           for="subscription_settings">
      SUBSCRIPTION SETTINGS
    </label>

    <!-- Monthly Subscription Settings -->
    <div class="mb-4">
      <h2 class="font-bold text-sm text-gray-700 dark:text-gray-300">Premium Subscription - Monthly</h2>
      <div class="flex flex-row mb-2">
        <label for="monthly_price" class="w-1/4 text-gray-700 dark:text-gray-300">Price ($):</label>
        <input v-model="adminStore.settingsForm.subscriptionSettings.monthly.price"
               class="border border-gray-400 p-2 w-full rounded-lg text-black"
               type="text"
               name="monthly_price"
               id="monthly_price">
      </div>
      <div class="flex flex-row mb-2">
        <label for="monthly_api_id" class="w-1/4 text-gray-700 dark:text-gray-300">API ID:</label>
        <input v-model="adminStore.settingsForm.subscriptionSettings.monthly.api_id"
               class="border border-gray-400 p-2 w-full rounded-lg text-black"
               type="text"
               name="monthly_api_id"
               id="monthly_api_id">
      </div>
    </div>

    <!-- Yearly Subscription Settings -->
    <div class="mb-4">
      <h2 class="font-bold text-sm text-gray-700 dark:text-gray-300">Premium Subscription - Yearly</h2>
      <div class="flex flex-row mb-2">
        <label for="yearly_price" class="w-1/4 text-gray-700 dark:text-gray-300">Price ($):</label>
        <input v-model="adminStore.settingsForm.subscriptionSettings.yearly.price"
               class="border border-gray-400 p-2 w-full rounded-lg text-black"
               type="text"
               name="yearly_price"
               id="yearly_price">
      </div>
      <div class="flex flex-row mb-2">
        <label for="yearly_api_id" class="w-1/4 text-gray-700 dark:text-gray-300">API ID:</label>
        <input v-model="adminStore.settingsForm.subscriptionSettings.yearly.api_id"
               class="border border-gray-400 p-2 w-full rounded-lg text-black"
               type="text"
               name="yearly_api_id"
               id="yearly_api_id">
      </div>
    </div>

    <!-- Stripe Secret API Key -->
    <div class="mb-4">
      <h2 class="font-bold text-sm text-gray-700 dark:text-gray-300">Stripe Secret API Key</h2>
      <div class="flex flex-row mb-2">
        <label for="stripe_secret_key" class="w-1/4 text-gray-700 dark:text-gray-300">Secret Key:</label>
        <input v-model="adminStore.settingsForm.subscriptionSettings.stripe_secret_key"
               class="border border-gray-400 p-2 w-full rounded-lg text-black"
               type="text"
               name="stripe_secret_key"
               id="stripe_secret_key">
      </div>
    </div>

    <div v-if="errors.subscription_settings" v-text="errors.subscription_settings"
         class="text-xs text-red-600 mt-1"></div>
  </div>
</template>

<script setup>
import { useAdminStore } from '@/Stores/AdminStore'

const adminStore = useAdminStore()

const props = defineProps({
  errors: Object,
})
</script>
