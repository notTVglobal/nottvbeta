<template>

    <Head :title="`${props.product.name}`" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">
<!--            <div class="container px-12 py-24 mx-auto">-->
            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <header class="flex justify-between mb-3">
                    <div id="topDiv">
                        <h1 class="text-3xl font-semibold pb-3">{{ props.product.name }}</h1>
                    </div>
                </header>

            <ShopHeader />

                <div class="lg:w-5/6 2xl:w-3/5 mx-auto flex flex-wrap">
                    <img alt="ecommerce placeholder" class="lg:w-1/2 2xl:w-1/2 w-full lg:h-auto 2xl:h-auto h-64 object-cover object-center rounded"
                         src="https://dummyimage.com/640x640">
                    <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                        <h2 class="text-sm title-font text-gray-500 tracking-widest uppercase inline-block mr-4"
                        v-for="category in product.categories.slice(0,2)"
                        v-text="category.name"
                        ></h2>
                        <h1 class="text-blue-800 dark:text-blue-200 text-3xl title-font font-medium mb-2"
                            v-text="product.name"
                        ></h1>
                        <p class="leading-relaxed"
                           v-text="product.description"
                        ></p>
                        <div class="flex mt-6 pt-4 border-t-2 border-gray-200">
                            <span class="title-font font-medium text-2xl text-gray-900 dark:text-gray-100"
                                  v-text="formatCurrency(product.price)"
                            ></span>
                        </div>
                        <button class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded"
                                @click="shopStore.addToCart(product)"
                        >Add To Cart</button>
                    </div>
                </div>
                <!--            <div class="flex flex-row justify-end gap-x-4 mb-4">-->

                <!--                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />-->
                <!--            </div>-->

                <div class="px-2">
                    <!--                Display items, services and events here.-->
                </div>
            </div>
<!--        </div>-->
    </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore"
import { useShopStore } from "@/Stores/ShopStore"
import { storeToRefs } from 'pinia'
import Message from "@/Components/Modals/Messages"
import ShopHeader from "@/Components/Shop/ShopHeader"

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let shopStore = useShopStore()

videoPlayerStore.currentPage = 'shop'
userStore.showFlashMessage = true;

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()

    shopStore.getProducts()
});

let props = defineProps({
    product: Object,
    can: Object,
})

// const { addToCart } = storeToRefs(shopStore)

function formatCurrency(price) {
    price = (price / 100)
    return price.toLocaleString('en-CA', {style: 'currency', currency: 'CAD'})
}

</script>

