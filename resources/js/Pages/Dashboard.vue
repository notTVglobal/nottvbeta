<template>
  <Head title="Dashboard"/>

  <div class="place-self-center flex flex-col gap-y-3 w-full">
    <div id="topDiv" class="rounded bg-white text-black dark:text-white dark:bg-gray-900 p-5 mb-10">

      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

      <DashboardHeader :can="can"/>

      <!--            <section class="grid grid-cols-1 my-8 mx-2 md:mx-10 m-auto bg-gray-200 rounded p-5 h-64 text-black">-->
      <!--                <div class="flex flex-wrap justify-center">-->
      <!--                    <span class=" font-semibold uppercase">Announcements</span>-->
      <!--                </div>-->
      <!--            </section>-->

      <NotificationPanel/>

      <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8 mx-2 m-auto text-black">

        <div class="p-5 bg-gray-200 dark:bg-gray-800 rounded">
          <MyAssignments/>
        </div>

        <div class="p-5 bg-gray-200 dark:bg-gray-800 rounded relative">
          <MyTeams :can="can" :teams="teams"/>
        </div>

        <div class="p-5 bg-gray-200 dark:bg-gray-800 rounded relative">
          <MyShows :can="can" :shows="shows"/>
        </div>

      </section>

      <div class="w-full bg-gray-300 dark:bg-gray-900 rounded p-3 my-8 mx-2 border-b border-2">
        <div class="stat place-items-center mb-4">
          <div class="stat-title font-semibold uppercase text-black dark:text-white mb-2 text-sm">Yesterday's Top Show
          </div>
          <div class="stat-value text-accent text-3xl">Down The Rabbit Hole</div>
          <div class="stat-desc mt-2 text-sm">︎Episode 2</div>
        </div>
      </div>

      <div class="w-full bg-gray-300 dark:bg-gray-900 rounded pb-8 p-3 mb-16 mx-2 border-b border-2">

        <div class="font-semibold text-xl pb-2 text-black dark:text-white">Stats</div>

        <div class="w-full mx-auto stats shadow stats-vertical lg:stats-horizontal">


          <div class="stat place-items-center">
            <div class="stat-title">Avg. Daily View Time</div>
            <div class="stat-value">~ minutes</div>
            <div class="stat-desc">From January 1st to February 1st</div>
          </div>

          <div class="stat place-items-center">
            <div class="stat-title">Premium subscribers</div>
            <div class="stat-value text-secondary">{{ subscriptionCount }}</div>
            <div class="stat-desc">↗︎ ~ (~%)</div>
          </div>

          <div class="stat place-items-center">
            <div class="stat-title">New Creators</div>
            <div class="stat-value">~</div>
            <div class="stat-desc">↘︎ ~ (~%)</div>
          </div>

        </div>

        <div class="w-full mt-4 mx-auto stats shadow stats-vertical lg:stats-horizontal">
          <div class="stat place-items-center">
            <div class="stat-title">Total Shows</div>
            <div class="stat-value">{{ showCount }}</div>
            <div class="stat-desc">↗︎ ~ (~%)</div>
          </div>
          <div class="stat place-items-center">
            <div class="stat-title">Total Users</div>
            <div class="stat-value">{{ userCount }}</div>
            <div class="stat-desc">↗︎ ~ (~%)</div>
          </div>
          <div class="stat place-items-center">
            <div class="stat-title">Total Creators</div>
            <div class="stat-value">{{ creatorCount }}</div>
            <div class="stat-desc">︎↗︎ ~ (~%)</div>
          </div>
        </div>

        <div class="w-full mt-4 mx-auto stats shadow stats-vertical lg:stats-horizontal">
          <div class="stat place-items-center">
            <div class="stat-title">Total Video Storage Used</div>
            <div class="stat-value text-secondary">{{ notTvTotalStorageUsed }}</div>
            <div class="stat-desc">My total: {{ myTotalStorageUsed }} ({{ myTotalStoragePercentage }}%)</div>
          </div>
          <div class="stat place-items-center">
            <div class="stat-title">Daily Peak Bandwidth</div>
            <div class="stat-value text-secondary">~ Gbps</div>
            <div class="stat-desc">︎↘︎ ~ (~%)</div>
          </div>
          <div class="stat place-items-center">
            <div class="stat-title">Average Bandwidth per User</div>
            <div class="stat-value text-secondary">~ Mbps</div>
            <div class="stat-desc">︎↘︎ ~ (~%)</div>
          </div>

        </div>
      </div>


      <div class="mt-6 h-0.5 bg-gray-800"></div>

      <section class="grid grid-cols-1 mt-6 gap-2 bg-gray-300 dark:dark:bg-gray-900 rounded">
        <div class="font-semibold text-2xl text-gray-800 dark:text-white px-4 pt-6 pb-2">
          Account Summary
        </div>

        <div class="mb-3 bg-orange-300 py-1 px-2 mx-4 text-xs font-semibold text-red-800">
          In development. Not currently working.
        </div>

        <div class="w-fit mx-auto stats stats-vertical sm:stats-horizontal bg-primary text-primary-content">

          <div class="stat my-2">
            <div class="stat-title text-white">Total balance</div>
            <div class="stat-value">$89,410</div>
            <div class="stat-actions">
              <button class="btn btn-sm btn-success">Add funds</button>
              <button class="ml-2 btn btn-sm mr-2">Withdrawal</button>
            </div>
          </div>

        </div>

        <div class="border-2">
          <div class="grid justify-items-stretch grid-cols-3 ">
            <div class="bg-gray-800 text-white text-sm p-2 col-span-3">Membership: 000000</div>
          </div>
          <table class="w-full">
            <thead class="">
            <td class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">My Account Name</td>
            <td class="bg-blue-400 px-2 mb-3 text-right font-semibold text-sm text-black">Balance</td>
            </thead>
            <tr class="border-b border-1 border-gray-100 text-black dark:text-white">
              <td class="px-2 col-span-2 py-2">Chequing</td>
              <td class="px-2 text-right">89,400.00</td>
            </tr>
            <tr class="border-b border-1 border-gray-100 text-black dark:text-white">
              <td class="px-2 col-span-2 py-2">Equity Shares</td>
              <td class="px-2 text-right">10.00</td>
            </tr>
          </table>
          <table class="w-full mt-6">
            <thead class="">
            <td class="bg-blue-400 font-semibold text-sm text-black px-2">Team Shares</td>
            <td class="bg-blue-400 px-2 text-right font-semibold text-sm text-black">Balance</td>
            </thead>
            <tr v-for="team in teams.data"
                :key="team.id"
                class="border-b border-1 border-gray-100 text-black dark:text-white">
              <td class="px-2 py-2">{{ team.name }}</td>
              <td class="px-2 text-right">0.00</td>
            </tr>
          </table>
          <table class="w-full mt-6">
            <thead class="">
            <td class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">Community Account Name</td>
            <td class="bg-blue-400 px-2 mb-3 text-right font-semibold text-sm text-black">Balance</td>
            </thead>
            <tr class="border-b border-1 border-gray-100 text-black dark:text-white">
              <td class="px-2 col-span-2 py-2">Public Good Fund</td>
              <td class="px-2 text-right">0.00</td>
            </tr>
            <tr class="border-b border-1 border-gray-100 text-black dark:text-white">
              <td class="px-2 col-span-2 py-2">Production Fund for Members</td>
              <td class="px-2 text-right">0.00</td>
            </tr>
            <tr class="border-b border-1 border-gray-100 text-black dark:text-white">
              <td class="px-2 col-span-2 py-2">News Fund</td>
              <td class="px-2 text-right">0.00</td>
            </tr>
          </table>

        </div>


      </section>
      <section class="mt-16 space-y-4 w-fit px-10 py-6 bg-gray-200 text-black dark:text-white rounded">
        <div class="text-sm uppercase mb-4 border-b border-blue-500">
          External Links
        </div>
        <a href="https://www.cbsc.ca/" target="_blank">
          <div class="hover:bg-blue-500 p-2">
            Canadian Broadcast Standards Council
          </div>
        </a>

        <a href="https://rtdnacanada.com/" target="_blank">
          <div class="hover:bg-blue-500 p-2">
            RTNDA
          </div>
        </a>

        <a href="https://adstandards.ca/" target="_blank">
          <div class="hover:bg-blue-500 p-2">
            Ad Standards
          </div>
        </a>

        <a href="https://www.cybertip.ca/en/" target="_blank">
          <div class="hover:bg-blue-500 p-2">
            Cybertip
          </div>
        </a>

      </section>

    </div>
  </div>

</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { computed, inject, onBeforeMount, onMounted, ref } from "vue"

import Pagination from "@/Components/Global/Paginators/Pagination"
import Message from "@/Components/Global/Modals/Messages"
import MyShowsHeader from "@/Components/Pages/Dashboard/Elements/MyShows/MyShowsHeader"
import DashboardHeader from "@/Components/Pages/Dashboard/Layout/DashboardHeader"
import MyAssignments from "@/Components/Pages/Dashboard/Elements/MyAssignments/MyAssignments"
import MyTeams from "@/Components/Pages/Dashboard/Elements/MyTeams/MyTeams"
import MyShows from "@/Components/Pages/Dashboard/Elements/MyShows//MyShows"
import NotificationPanel from "@/Components/Pages/Dashboard/Elements/DashboardNotification/DashboardNotificationPanel"

const getUserData = inject('getUserData', null)




import { useUserStore } from "@/Stores/UserStore"

const userStore = useUserStore()




import { usePageSetup } from '@/Utilities/PageSetup'

usePageSetup('dashboard')







onMounted(() => {
  if (!getUserData) {
    getUserTimezone()
    updateUserStore()
  }
  Inertia.reload()
});

// isCreator, isNewsPerson, isVip, isSubscriber
// need to go in the UserStore. Inertia won't
// update the initialPage.props, so the solution
// is to send this data in 3 places the user
// will land after logging in.
// 1) The Dashboard
// 2) Stream
// 3) any other page from the /login page
// (AppLayout will be our catch all.)
// Only the Dashboard has a controller,
// the others will use Axios to get the data
// and save it in the UserStore.
let props = defineProps({
  id: null,
  isAdmin: null,
  isCreator: null,
  isNewsPerson: null,
  isSubscriber: null,
  hasAccount: null,
  isVip: null,
  shows: Object,
  teams: Object,
  myTotalStorageUsed: ref(String),
  notTvTotalStorageUsed: ref(String),
  showCount: Number,
  userCount: Number,
  creatorCount: Number,
  subscriptionCount: Number,
  can: Object,
});

// Function to extract the numeric value from a string with "MB" suffix
const extractNumericValue = (str) => {
  const numericValue = parseFloat(str);
  return isNaN(numericValue) ? 0 : numericValue;
};

// Convert x and n to numeric values
const myTotalStorageUsedNumeric = extractNumericValue(props.myTotalStorageUsed);
const notTvTotalStorageUsedNumeric = extractNumericValue(props.notTvTotalStorageUsed);

// Create a computed property to calculate the percentage
const myTotalStoragePercentage = computed(() => {
  // return Math.round((myTotalStorageUsedNumeric / notTvTotalStorageUsedNumeric) * 100);
  return ((myTotalStorageUsedNumeric / notTvTotalStorageUsedNumeric) * 100).toFixed(2);
});

// Round the percentage to have no decimal places
const myTotalStorageRoundedPercentage = computed(() => {
  return Math.round(myTotalStoragePercentage);
});


async function updateUserStore() {
  userStore.id = props.id
  userStore.loggedIn = true
  userStore.isAdmin = props.isAdmin
  userStore.isCreator = props.isCreator
  userStore.isNewsPerson = props.isNewsPerson
  userStore.isVip = props.isVip
  userStore.isSubscriber = props.isSubscriber
  userStore.hasAccount = props.hasAccount
  userStore.getUserDataCompleted = true
  userStore.timezone = userTimezone
  console.log('get user data on Dashboard')
  if (userStore.isCreator) {
    userStore.prevUrl = '/dashboard'
  } else {
    userStore.prevUrl = '/stream'
  }
  if (!userStore.userSubscribedToNotifications) {
    await userStore.subscribeToUserNotifications(props.id)
  }
  // save user Timezone
  await updateUserTimezone()

}

const userTimezone = ref('');

const getUserTimezone = () => {
  // Use the Intl object to get the user's timezone
  userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

const updateUserTimezone = async () => {
  try {
    const response = await axios.post('/users/update-timezone', {timezone: userTimezone.value});

    // Handle success response as needed
    console.log(response.data.message);
  } catch (error) {
    // Handle error response or network error
    console.error(error);

    if (error.response) {
      // Handle specific error responses if needed
      console.error(error.response.data);
    }
  }
};

</script>


