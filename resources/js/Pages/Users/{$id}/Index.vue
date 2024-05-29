<template>

  <Head :title="props.userSelected.name"/>

  <div class="flex flex-col gap-y-3">
    <div id="topDiv" class="light:bg-white dark:bg-gray-800 light:text-black dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-end mb-3 gap-2 pt-6">
        <Link v-if="$page.props.auth.user.isAdmin === 1" :href="`/users`">
          <button
              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
          >All Users
          </button>
        </Link>
        <Link v-if="$page.props.auth.user.isAdmin === 1" :href="`/users/${props.userSelected.id}/edit`">
          <button
              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
          >Edit User
          </button>
        </Link>
        <Link :href="`/dashboard`">
          <button
              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
          >Dashboard
          </button>
        </Link>
      </div>

      <p class="mb-6">
        <img :src="props.userSelected.profile_photo_url" class="rounded-full h-20 w-20 object-cover"/>
      </p>

      <h2 class="text-2xl pb-2">
        {{ props.userSelected.name }}
      </h2>

      <div class="p-6 bg-gray-800 border-b border-gray-200 space-y-2 text-gray-100">
        <div v-if="props.userSelected.isAdmin === 1">
          <span class="font-bold text-red-600">Administrator</span>
        </div>
        <div v-if="props.userSelected.isVip">
          <span class="text-2xl font-semibold text-purple-600">VIP</span>
        </div>
        <div v-if="props.isCreator">
          <span class="text-2xl font-semibold text-blue-500">Creator</span>
        </div>
        <div v-if="props.isNewsPerson" class="text-yellow-600">
          <span class="text-2xl font-semibold">News Team</span>
          <!-- Displaying roles -->
          <div v-if="props.newsPersonRoles.length">
        <span class="text-xl">
            {{ props.newsPersonRoles.join(', ') }}
        </span>
          </div>
        </div>
        <div>
          <div class=""><span class="text-xs uppercase mr-2">User ID: </span><span
              class="text-base font-semibold">{{ props.userSelected.id }}</span></div>

          <div><span class="text-xs uppercase mr-2">User Type: </span>{{ props.role }}</div>
        </div>
        <div>
          <div v-if="$page.props.auth.userSelected.role_id === 4">
            <span class="text-xs uppercase mr-2">Creator Number: </span>{{ props.userSelected.creatorNumber }}
          </div>
        </div>
        <div v-if="props.subscriptionName">
          <span class="text-xs uppercase mr-2">Subscription: </span>{{ props.subscriptionName }}
        </div>
        <div>
          <span class="text-xs uppercase mr-2">Subscription Status: </span>{{ props.subscriptionStatus }}
        </div>
        <div v-if="props.trialEndsAt">
          <span class="text-xs uppercase mr-2">Trial Ends At: </span>{{ formatDate(props.trialEndsAt) }}
        </div>
        <div v-if="props.endsAt">
          <span class="text-xs uppercase mr-2">Subscription Renewal Date: </span>{{ formatDate(props.endsAt) }}
        </div>
        <div v-if="$page.props.auth.user.isAdmin">
          <span class="text-xs uppercase mr-2">Stripe ID: </span>{{ props.userSelected.stripe_id }}
        </div>
        <div v-if="$page.props.auth.user.isAdmin" class="text-yellow-500">
          <span class="text-xs uppercase mr-2">Last Login: </span>{{ userStore.formatDateTimeFromUtcToUserTimezone(props.lastLoginAt) }} {{ userStore.timezoneAbbreviation}}
        </div>
      </div>

      <div class="p-6 bg-gray-800 border-b border-gray-600">
        <div class="space-y-3">
          <div>
            <span class="text-base font-semibold uppercase mr-2 text-gray-400">Email:</span>
            <span class="text-sm text-gray-300">{{ props.userSelected.email }}</span>
          </div>
          <div>
            <span class="text-base font-semibold uppercase mr-2 text-gray-400">Phone:</span>
            <span class="text-sm text-gray-300">{{ props.userSelected.phone }}</span>
          </div>
          <div class="pt-5 mt-5 border-t border-gray-600">
            <div>
              <span class="text-base font-semibold uppercase mr-2 text-gray-400">Address 1:</span>
              <span class="text-sm text-gray-300">{{ props.userSelected.address1 }}</span>
            </div>
            <div>
              <span class="text-base font-semibold uppercase mr-2 text-gray-400">Address 2:</span>
              <span class="text-sm text-gray-300">{{ props.userSelected.address2 }}</span>
            </div>
            <div>
              <span class="text-base font-semibold uppercase mr-2 text-gray-400">City:</span>
              <span class="text-sm text-gray-300">{{ props.userSelected.city }}</span>
            </div>
            <div>
              <span class="text-base font-semibold uppercase mr-2 text-gray-400">Province:</span>
              <span class="text-sm text-gray-300">{{ props.userSelected.province }}</span>
            </div>
            <div>
              <span class="text-base font-semibold uppercase mr-2 text-gray-400">Country:</span>
              <span class="text-sm text-gray-300">{{ props.userSelected.country }}</span>
            </div>
            <div>
              <span class="text-base font-semibold uppercase mr-2 text-gray-400">Postal Code:</span>
              <span class="text-sm text-gray-300">{{ props.userSelected.postalCode }}</span>
            </div>
          </div>
        </div>
      </div>
      <div v-if="userSelected.role_id === 4" class="p-6 light:bg-white dark:bg-gray-800 border-b border-gray-200">
        <div class="text-2xl pb-2">
          Teams this user belongs to:
        </div>
        <div v-for="team in props.teams"
             :key="team.id">
          <Link :href="`/teams/${team.slug}`"
                class="text-xl mt-2 tracking-wider light:text-blue-800 light:hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">
            {{ team.name }}
          </Link>
        </div>
      </div>
    </div>
  </div>


</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import Message from '@/Components/Global/Modals/Messages'

usePageSetup('usersShow')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

let props = defineProps({
  userSelected: Object,
  isCreator: Boolean,
  isNewsPerson: Boolean,
  newsPersonRoles: Array,
  subscriptionStatus: String,
  trialEndsAt: String,
  endsAt: String,
  subscriptionName: String,
  role: String,
  lastLoginAt: String,
  teams: Object,
})

</script>
