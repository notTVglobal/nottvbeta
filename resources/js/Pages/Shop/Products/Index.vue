<template>

    <Head title="`Shop Products`" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <header class="flex justify-between mb-3">
                <div id="topDiv">
                    <h1 class="text-3xl font-semibold pb-3">Products</h1>
                </div>
            </header>

            <ShopHeader />

            <div class="flex flex-wrap -m-4" v-if="!shopStore.products.length">
                <div class="lg:w-1/4 md:w-1/2 p-4 w-full mb-4">
                    <a class="block relative h-48 rounded overflow-hidden">
                        <img alt="ecommerce image"
                             class="object-cover object-center w-full h-full block"
                             src="https://dummyimage.com/420x260">
                    </a>
                    <div class="mt-4">
                        <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1 uppercase inline-block mr-2">N/A</h3>
                        <h2 class="text-blue-800 dark:text-blue-200 title-font text-lg font-medium">Loading...</h2>
                        <p class="mt-1">$0.00</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -m-4" v-else>
                <div class="lg:w-1/4 md:1/2 p-4 w-full mb-4"
                     v-for="product in shopStore.products"
                     :key="product.id"
                     >
                    <Link :href="`/shop/product/${product.slug}`" class="block relative h-48 rounded overflow-hidden">
                        <img alt="ecommerce image"
                             class="object-cover object-center w-full h-full block"
                             src="https://dummyimage.com/420x260">
                    </Link>
                    <div class="mt-4">
                        <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1 uppercase inline-block mr-2"
                            v-for="category in product.categories.slice(0, 2)"
                            v-text="category.name"
                        ></h3>
                        <h2 class="text-blue-800 dark:text-blue-200 title-font text-lg font-medium"
                            v-text="product.name"
                        ></h2>
                        <p class="mt-1"
                            v-text="formatCurrency(product.price)"
                        ></p>
                    </div>
                </div>
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
import { onBeforeMount, onMounted, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore"
import { useShopStore } from "@/Stores/ShopStore"
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

function formatCurrency(price) {
    price = (price / 100)
    return price.toLocaleString('en-CA', {style: 'currency', currency: 'CAD'})
}

</script>

