<template>

  <Head title="Order Summary"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

      <header class="flex justify-between mb-3">
        <div id="topDiv">
          <h1 class="text-3xl font-semibold pb-3">Order Summary</h1>
        </div>
      </header>

      <div class="mb-4">
        <div class="lg:w-2/3 w-full mx-auto mt-8 overflow-auto">
          <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
            <tr>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 rounded-tl rounded-bl">
                Item
              </th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200">Quantity
              </th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200">Price</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in props.order.products" :key="item.id">
              <td class="p-4" v-text="item.name"></td>
              <td class="p-4" v-text="item.products.quantity"></td>
              <td class="p-4" v-text="shopStore.orderLineTotal(item)"></td>
            </tr>
            <tr>
              <td class="p-4 font-bold">Total Amount</td>
              <td class="p-4 font-bold" v-text="shopStore.orderQuantity"></td>
              <td class="p-4 font-bold" v-text="shopStore.orderTotal"></td>
              <td class="w-10 text-right"></td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!--            <div class="flex flex-row justify-end gap-x-4 mb-4">-->

      <!--                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />-->
      <!--            </div>-->

      <div class="px-2">
        Thank you for your order!
      </div>

    </div>
  </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useUserStore } from "@/Stores/UserStore"
import { useShopStore } from "@/Stores/ShopStore"
import Message from "@/Components/Global/Modals/Messages"

usePageSetup('shopSummary')

const userStore = useUserStore()
const shopStore = useShopStore()

onMounted(async () => {
  shopStore.cart = [];
  shopStore.products = [];
  await loadOrder()
});

let props = defineProps({
  order: Object,
  can: Object,
})

function loadOrder() {
  shopStore.order = props.order
}

</script>

