<template>

  <Head title="Upgrade Account"/>
  <div id="topDiv" class="h-[calc(100vh-16rem)] text-center bg-gray-800 text-white px-10">

    <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

    <!--            <h1 class="text-3xl font-semibold pb-3">Become a notTV Premium Subscriber</h1>-->

    <div class="flex justify-center w-full">
      <div class="flex flex-col justify-center w-fit bg-gray-900 rounded-md py-10">

        <h2 class="text-3xl py-8 px-2 font-bold">Choose a Subscription</h2>

        <div class="flex flex-wrap justify-center space-y-3 space-x-3 px-8 mx-auto">
          <div></div>

          <div class="card monthly bg-gray-700 hover:bg-gray-600 hover:cursor-pointer rounded-lg px-12 pt-6"
               @mouseover="hoverMonthlyFull = true"
               @mouseleave="hoverMonthlyFull = false"
               @click="payNow('monthly')">
            <div class="flex justify-between mb-2">
              <div class="productName">Monthly</div>
              <div class="price">$25</div>
            </div>
            <div class="py-6">
              <font-awesome-icon icon="fa-star" class="upgradeIcon"/>
            </div>
            <div>
              Get full access to all features, shows, channels and movies!
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
               @click="payNow('yearly')">
            <div class="flex justify-between mb-2">
              <div class="productName">Yearly</div>
              <div class="price">$250</div>
            </div>
            <div class="py-6">
              <font-awesome-icon icon="fa-star" class="upgradeIcon"/>
              <font-awesome-icon icon="fa-star" class="upgradeIcon"/>
            </div>
            <div>
              Get full access to all features, shows, channels and movies!
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
               @click="payNow('forever')">
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

        <div class="py-20"></div>


      </div>
    </div>


  </div>

</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useUserStore } from "@/Stores/UserStore"
import { useShopStore } from "@/Stores/ShopStore"
import Message from "@/Components/Global/Modals/Messages"

usePageSetup('shopUpgrade')

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

function payNow(subscription) {
  if (subscription === 'monthly') {
    shopStore.upgradeMonthly()
    Inertia.get('/shop/subscribe')
  }
  if (subscription === 'yearly') {
    shopStore.upgradeYearly()
    Inertia.get('/shop/subscribe')
  }
  if (subscription === 'forever') {
    shopStore.upgradeForever()
    Inertia.get('/shop/subscribe')
  }
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

</style>

