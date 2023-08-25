<template>
    <div class="mb-2">
        <Link :href="route('products')"><button
            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded">
            See all products
        </button>
        </Link>
        <Link :href="route('checkout')"><button
            class="bg-blue-600 hover:bg-blue-700 text-white ml-2 px-2 py-1 rounded">
            <span>Checkout</span><span class="inline-block ml-1" v-text="'(' + shopStore.cart.length + ' items)'"></span>
        </button>
        </Link>
    </div>
</template>

<script setup>
import { useShopStore } from "@/Stores/ShopStore"

let shopStore = useShopStore()
</script>
