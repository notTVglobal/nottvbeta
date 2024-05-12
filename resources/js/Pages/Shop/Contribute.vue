<template>

  <Head title="Upgrade Account"/>
  <div id="topDiv" class="min-h-screen text-center bg-gray-900 text-white px-10">

    <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

    <!--            <h1 class="text-3xl font-semibold pb-3">Become a notTV Premium Subscriber</h1>-->

    <div class="flex justify-center w-full">
      <div class="flex flex-col justify-center w-full bg-gray-900 rounded-lg py-10">

        <div class="flex flex-col items-center space-y-8 px-8 mb-80">
          <h2 class="text-3xl font-bold text-white py-4">Contribute to notTV</h2>
          <p class="px-6 pb-4 text-lg text-gray-300 max-w-4xl">
            Your contribution supports independent creators, journalists, and the notTV funds dedicated to production, news, and public good. Together, we're creating a vibrant ecosystem for storytelling and community-driven media. Choose how you'd like to contribute:
          </p>


          <div v-if="userStore.isSubscriber" class="max-w-4xl p-4 rounded-lg bg-gradient-to-r from-blue-600 to-blue-400 text-white">
            🌟 A huge thank you for being a vibrant part of our journey! Dive into your unique content journey and explore your payment plan and history anytime - just navigate to the top menu and select 'Account'. Together, we're redefining the way the world communicates. 🌈
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl items-start">
            <!-- Monthly Contribution Card -->
            <ContributeCard v-if="!userStore.isSubscriber" @click="startSubscription('monthly')" :color="`blue`" :animation="true" :itemSelected="`monthlyContribution`" class="cursor-pointer">
              <template #title>Monthly</template>
              <template #icon><font-awesome-icon icon="fa-star" class="text-2xl"/></template>
              <template #main>Join our monthly plan to continuously support independent journalism, content creators, and various funds essential for community enrichment.</template>
              <template #button>Select Plan - $25</template>
            </ContributeCard>

            <!-- Annual Contribution Card -->
            <ContributeCard v-if="!userStore.isSubscriber" @click="startSubscription('yearly')" color="purple" :animation="true" :itemSelected="`yearlyContribution`" class="cursor-pointer">
              <template #title>Annual Contribution</template>
              <template #icon><font-awesome-icon icon="fa-gem" class="text-2xl"/></template>
              <template #main>Opt for an annual contribution to make a lasting impact on our mission towards a decentralized and democratic media platform.</template>
              <template #button>Select Plan - $250</template>
            </ContributeCard>

            <!-- One-Time Contribution Card -->
            <ContributeCard :color="`green`" :animation="true" :disableButton="true" :itemSelected="`oneTimeDonation`" class="cursor-pointer">
              <template #title>One-Time Donation</template>
              <template #icon><font-awesome-icon icon="fa-heart" class="text-2xl"/></template>
              <template #main>
                Your support fuels our mission. Contribute to our various funds, including for content creators, independent journalism, and community enrichment. Every bit helps.
              </template>
              <template #button>
                <div class="flex items-center space-x-4 w-full">
                  <div class="flex flex-col w-full">
                    <input v-model="donationAmount"
                           type="range"
                           min="25"
                           max="125"
                           value="25"
                           class="range range-success bg-white text-black hover:shadow-lg transition-shadow duration-200"
                           step="25"
                           @input="handleDonationAmountChange" />
                    <div class="w-full flex justify-between text-xs px-2">
                      <span>|</span>
                      <span>|</span>
                      <span>|</span>
                      <span>|</span>
                      <span>|</span>
                    </div>
                  </div>
                  <span class="text-black">$ {{ donationAmount }}</span>
                </div>

                <!-- Input for manual donation amount entry -->
                <div class="input-container">
                  <input type="number" v-model="donationAmount" @input="handleDonationAmountChange" placeholder="Enter amount" class="bg-white text-black input input-bordered input-success input-md w-24 mr-10 mt-2">
                  <button @click.stop="oneTimeDonation('onetime') " class="donate-now-btn hover:bg-green-700 bg-green-500 rounded-lg px-2 py-2">Donate Now</button>
                </div>
              </template>
            </ContributeCard>

            <!-- Favourite Show Contribution Card -->
            <ContributeCard @click="oneTimeDonation('favouriteShow')" color="orange" :animation="true" :disableButton="false" :itemSelected="`favouriteShowContribution`" class="cursor-pointer">
              <template #title>Support Your Favorite <br />Show, Creator or Reporter</template>
              <template #icon><font-awesome-icon icon="fa-heart" class="text-2xl"/></template>
              <template #main>Contribute directly to the production of your favorite show. Your support helps us create more of the content you love.<br></template>
              <template #button>Support Now</template>
            </ContributeCard>

          </div>
        </div>





        <dialog id="favouriteShowSelect" class="modal">
          <div class="modal-box">
            <h3 class="font-bold text-lg">Choose an Option</h3>
            <p class="py-4">Please select one of the following:</p>
            <div class="flex flex-col space-y-4">
              <button class="btn btn-primary" @click="selectOption('creator')">Creator</button>
              <button class="btn btn-primary" @click="selectOption('show')">Show</button>
              <button class="btn btn-primary" @click="selectOption('reporter')">Reporter</button>
            </div>
            <div class="modal-action">
              <form method="dialog">
                <button class="btn">Close</button>
              </form>
            </div>
          </div>
        </dialog>










        <div class="hidden flex flex-wrap justify-center space-y-3 space-x-3 px-8 mb-10 mx-auto">
          <div class="bg-gray-800 rounded-lg p-6 text-gray-300">

            <h3 class="text-2xl font-semibold">Monthly Contribution</h3>
            <p class="my-4">Support ongoing development and enjoy early access to exclusive content and community features. Your monthly contribution keeps the spirit of independent media alive.</p>
            <button @click="startSubscription('monthly')" class="btn bg-green-500 hover:bg-green-700 text-white mt-4">Contribute Monthly</button>
          </div>
          <div class="bg-gray-800 rounded-lg p-6 text-gray-300">
            <h3 class="text-2xl font-semibold">Yearly Contribution</h3>
            <p class="my-4">Make a lasting impact with a yearly contribution. Gain special recognition in the community and first access to new features and content. Your support fuels our mission for a whole year.</p>
            <button @click="startSubscription('yearly')" class="btn bg-purple-500 hover:bg-purple-700 text-white mt-4">Contribute Yearly</button>
          </div>
          <div class="bg-gray-800 rounded-lg p-6 text-gray-300">
            <h3 class="text-2xl font-semibold">One-Time Donation</h3>
            <p class="my-4">Every contribution matters. Make a one-time donation to support notTV's goals. Choose the amount you wish to donate and make a difference today.</p>
            <div class="flex items-center space-x-4">
              <div class="w-fit mx-auto">
                <input type="range"
                       min="5"
                       max="500"
                       v-model="donationAmount"
                       @input="handleDonationAmountChange"
                       class="slider text-center mx-auto"
                       id="donationAmount">
                <span id="donationValue" class="text-white ml-4">${{ donationAmount }}</span>
              </div>

            </div>
            <button @click="oneTimeDonation('onetime')" class="btn bg-blue-500 hover:bg-blue-700 text-white mt-4">Donate Now</button>
          </div>
        </div>







        <div class="hidden flex flex-wrap justify-center space-y-3 space-x-3 px-8 mb-80 mx-auto">
          <div></div>

          <div class="card monthly bg-gray-700 hover:bg-gray-600 hover:cursor-pointer rounded-lg px-12 pt-6"
               @mouseover="hoverMonthlyFull = true"
               @mouseleave="hoverMonthlyFull = false"
               @click="startSubscription('monthly')">
            <div class="flex justify-between mb-2">
              <div class="productName">Monthly</div>
              <div class="price">$25</div>
            </div>
            <div class="py-6">
              <font-awesome-icon icon="fa-star" class="upgradeIcon"/>
            </div>
            <div class="max-w-64">
              Support ongoing development and enjoy early access to exclusive content and community features. Your monthly contribution keeps the spirit of independent media alive.
            </div>
            <div
                class="flex no-wrap justify-center bg-gray-900 hover:bg-gray-800 hover:cursor-pointer rounded-lg w-full mt-12 px-4 py-4 mb-4 mx-auto"
                @mouseover="hoverMonthly = true"
                @mouseleave="hoverMonthly = false">
                                    <span class="bg-gray-700 mr-4 py-1 px-2 w-fit rounded-full"
                                          :class="{active: hoverMonthly}"><font-awesome-icon icon="fa-check"/></span>
              <span> Select Plan</span></div>

          </div>

          <div class="card annually bg-gray-700 hover:bg-gray-600 hover:cursor-pointer rounded-lg px-12 pt-6"
               @mouseover="hoverYearlyFull = true"
               @mouseleave="hoverYearlyFull = false"
               @click="startSubscription('yearly')">
            <div class="flex justify-between mb-2">
              <div class="productName">Yearly</div>
              <div class="price">$250</div>
            </div>
            <div class="py-6">
              <font-awesome-icon icon="fa-star" class="upgradeIcon"/>
              <font-awesome-icon icon="fa-star" class="upgradeIcon"/>
            </div>
            <div class="max-w-64">
              Make a lasting impact with a yearly contribution. Gain special recognition in the community and first access to new features and content. Your support fuels our mission for a whole year.
            </div>
            <div
                class="flex no-wrap justify-center bg-gray-900 hover:bg-gray-800 hover:cursor-pointer rounded-lg w-full mt-12 px-4 py-4 mb-4 mx-auto"
                @mouseover="hoverYearly = true"
                @mouseleave="hoverYearly = false">

                                <span class="bg-gray-700 mr-4 py-1 px-2 w-fit rounded-full"
                                      :class="{active: hoverYearly}"><font-awesome-icon icon="fa-check"/></span> <span
                class=""> Select Plan</span></div>

          </div>

          <div class="hidden card forever bg-gray-700 hover:bg-gray-600 hover:cursor-pointer rounded-lg px-12 py-6"
               @mouseover="hoverForeverFull = true"
               @mouseleave="hoverForeverFull = false"
               @click="oneTimeDonation('onetime')">
            <div class="flex justify-between mb-2">
              <div class="productName">Forever</div>
              <div class="price">$999</div>
            </div>
            <div class="py-10">
              <font-awesome-icon icon="fa-star" class="upgradeIcon" :class="{activeIcon: hoverForeverFull}"/>
              <font-awesome-icon icon="fa-star" class="upgradeIcon" :class="{activeIcon: hoverForeverFull}"/>
              <font-awesome-icon icon="fa-star" class="upgradeIcon" :class="{activeIcon: hoverForeverFull}"/>
            </div>
            <div>
              Get full access to all features, shows, channels and movies!
            </div>
            <div
                class="flex no-wrap justify-center bg-gray-900 hover:bg-gray-800 hover:cursor-pointer rounded-lg w-full mt-12 px-4 py-4 mb-4 mx-auto"
                @mouseover="hoverForever = true"
                @mouseleave="hoverForever = false">
                                <span class="bg-gray-700 mr-4 py-1 px-2 w-fit rounded-full"
                                      :class="{active: hoverForever}"><font-awesome-icon icon="fa-check"/></span> <span> Select Plan</span>
            </div>

          </div>
        </div>

        <div class="">

        </div>


      </div>
    </div>


  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useShopStore } from '@/Stores/ShopStore'
import Message from '@/Components/Global/Modals/Messages'
import ContributeCard from '@/Components/Pages/Contribute/ContributeCard.vue'
import { computed } from 'vue'

usePageSetup('upgrade')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const shopStore = useShopStore()

let props = defineProps({
  user: Object,
  selectedSubscription: null,
  hoverMonthly: false,
  hoverYearly: false,
  hoverForever: false,
  hoverMonthlyFull: false,
  hoverYearlyFull: false,
  hoverForeverFull: false,
})

// Computed property to bind the slider with the store's donation amount
const donationAmount = computed({
  get: () => shopStore.donationAmount,
  set: (value) => shopStore.updateDonationAmount(Number(value)),
});

function handleDonationAmountChange(event) {
  shopStore.updateDonationAmount(Number(event.target.value));
}

function oneTimeDonation(type) {
  switch (type) {
    case 'onetime':
      console.log(`Donating $${shopStore.donationAmount}`);
      // shopStore.monthlyContribution();
      break;
    case 'favouriteShow':
      document.getElementById('favouriteShowSelect').showModal()
      console.log(`Choose favourite show next...`);
      break;
  }
  // Implement the logic to handle the donation submission

  // This could involve calling an API endpoint, showing a confirmation message, etc.
}

function startSubscription(subscription) {
  switch (subscription) {
    case 'monthly':
      shopStore.monthlyContribution();
      break;
    case 'yearly':
      shopStore.yearlyContribution();
      break;
    case 'onetime':
      shopStore.oneTimeDonation();
      break;
    case 'favouriteShow':
      shopStore.favouriteShowContribution();
      break;
    default:
      console.log('Invalid subscription type');
      return; // Early return for an invalid case
  }
  Inertia.get('/contribute/subscription');
}

</script>

<style scoped>

.upgradeIcon {
  font-size: xxx-large;
}

.productName {
  font-size: large;
  font-weight: bold;
  padding-top: 1rem;
}

.price {
  font-size: xxx-large;
  color: dodgerblue;
  vertical-align: top;
}

.active {
  background-color: #0a59da;
}

.activeIcon {
  color: #0a59da;
}

.subscription-card {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

@media (min-width: 768px) { /* Adjust based on your breakpoint for md */
  .subscription-card {
    height: 100%; /* Ensure equal height in desktop view */
  }
}

</style>

