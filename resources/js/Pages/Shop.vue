<template>

    <Head title="Shop" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

            <header class="flex justify-between mb-3">
                <div id="topDiv">
                    <h1 class="text-3xl font-semibold pb-3">Shop</h1>
                </div>
            </header>

            <div class="mb-4">
                <div class="p-2 font-semibold text-2xl bg-orange-300 text-black">Coming Soon!</div>
                <p class="mt-4">
                    This is the home for our notTV Shop... Our sponsors and content creators will be able to add commercials for products, services and events.
                </p>
            </div>

<!--            <div class="flex flex-row justify-end gap-x-4 mb-4">-->

<!--                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />-->
<!--            </div>-->

            <div class="px-2">
<!--                Display items, services and events here.-->
            </div>

        </div>
    </div>

</template>

<script setup>
import { onMounted } from "vue"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useShopStore } from "@/Stores/ShopStore"
import Message from "@/Components/Global/Modals/Messages"

usePageSetup('shop')

const appSettingStore = useAppSettingStore()
const shopStore = useShopStore()

onMounted(() => {
    shopStore.getProducts()
});

let props = defineProps({
    can: Object,
})

</script>

