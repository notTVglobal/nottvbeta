<template>

    <Head title="Checkout" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <header class="flex justify-between mb-3">
                <div id="topDiv">
                    <h1 class="text-3xl font-semibold pb-3">Checkout <span class="inline-block ml-1" v-text="'(' + shopStore.cart.length + ' items)'"></span></h1>
                </div>
            </header>

            <div>
                <Link :href="route('products')"><button
                    class="bg-blue-600 hover:bg-blue-400 text-white px-2 py-1 rounded">
                    See all products
                </button>
                </Link>
            </div>

            <div class="mb-4 w-full">
                <div class="lg:w-2/3 w-full mx-auto mt-8 overflow-auto">
                    <table class="table-auto w-full text-left whitespace-no-wrap">
                        <thead>
                        <tr>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 rounded-tl rounded-bl">Item</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200">Quantity</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200">Price</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in shopStore.cart" :key="item.id">
                                <td class="p-4" v-text="item.name"></td>
                                <td class="p-4" v-text="item.quantity"></td>
                                <td class="p-4" v-text="shopStore.cartLineTotal(item)"></td>
                                <td class="w-10 text-right">
                                    <button
                                        class="flex ml-auto text-sm text-white bg-blue-600 border-0 py-2 px-6 focus:outline-none hover:bg-blue-700 rounded"
                                        @click.prevent="shopStore.removeFromCart(index)">
                                        Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4 font-bold">Total Amount</td>
                                <td class="p-4 font-bold" v-text="shopStore.cartQuantity"></td>
                                <td class="p-4 font-bold" v-text="shopStore.cartTotal"></td>
                                <td class="w-10 text-right"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="lg:w-2/3 w-full mx-auto mt-8">
                    <h2 class="px-2 text-2xl font-semibold text-black dark:text-gray-100">Billing Info</h2>
                    <div class="flex flex-wrap -mx-2">
                        <div class="p-2 w-2/3">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm text-black dark:text-gray-200">Name</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    v-model="shopStore.customer.name"
                                    class="w-full bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                    :disabled="paymentProcessing"
                                >
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="relative">
                                <label for="address1" class="leading-7 text-sm text-black dark:text-gray-200">Address Line 1</label>
                                <input
                                    type="text"
                                    id="address1"
                                    name="address1"
                                    v-model="shopStore.customer.address1"
                                    class="w-full bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                    :disabled="paymentProcessing"
                                >
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="relative">
                                <label for="address2" class="leading-7 text-sm text-black dark:text-gray-200">Address Line 2</label>
                                <input
                                    type="text"
                                    id="address2"
                                    name="address2"
                                    v-model="shopStore.customer.address2"
                                    class="w-full bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                    :disabled="paymentProcessing"
                                >
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="relative">
                                <label for="city" class="leading-7 text-sm text-black dark:text-gray-200">City</label>
                                <input
                                    type="text"
                                    id="city"
                                    name="city"
                                    v-model="shopStore.customer.city"
                                    class="w-full bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                    :disabled="paymentProcessing"
                                >
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="relative">
                                <label for="province" class="leading-7 text-sm text-black dark:text-gray-200">Province</label>
                                <region-select
                                    id="province"
                                    name="province"
                                    v-model="shopStore.customer.province"
                                    :country="shopStore.customer.country"
                                    :region="shopStore.customer.province"
                                    class="w-full bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                    :disabled="paymentProcessing"
                                />
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="relative">
                                <label for="country" class="leading-7 text-sm text-black dark:text-gray-200">Country</label>
                                <country-select
                                    id="country"
                                    name="country"
                                    v-model="shopStore.customer.country"
                                    :country="shopStore.customer.country"
                                    topCountry="CA"
                                    class="w-full bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                    :disabled="paymentProcessing"
                                />
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="relative">
                                <label for="postalCode" class="leading-7 text-sm text-black dark:text-gray-200">Postal Code</label>
                                <input
                                    id="postalCode"
                                    name="postalCode"
                                    v-model="shopStore.customer.postalCode"
                                    class="w-full bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                    :disabled="paymentProcessing"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -ms-2 mt-4">
                        <div class="p-2 w-full">
                            <div class="relative">
                                <label for="card_element" class="leading-7 text-sm text-black dark:text-gray-200">Credit Card Information</label>
                                <div id="card-element"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 mb-24 w-full">
                        <button
                            class="flex mx-auto text-white bg-blue-600 border-0 py-2 px-8 focus:outline-none hover:bg-blue-700 rounded"
                            @click.prevent="processPayment"
                            :disabled="paymentProcessing"
                            v-text="paymentProcessing ? 'Processing' : 'Pay Now'"
                        ></button>
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
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore"
import { useShopStore } from "@/Stores/ShopStore"
import Message from "@/Components/Modals/Messages"
import {loadStripe} from "@stripe/stripe-js";
// import CountrySelect from "@/Components/Forms/CountrySelect.vue";
// import RegionSelect from "@/Components/Forms/RegionSelect.vue";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let shopStore = useShopStore()

videoPlayerStore.currentPage = 'shop'
userStore.showFlashMessage = true;

let props = defineProps({
    user: Object,
    can: Object,

})

onMounted(async () => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()

    shopStore.getProducts()
    shopStore.customer = props.user
});

let form = useForm({
    name: props.user.name,
    address1: props.user.address1,
    address2: props.user.address2,
    city: props.user.city,
    province: props.user.province,
    country: props.user.country,
    postalCode: props.user.postalCode,
});



</script>

<script>
import { loadStripe } from '@stripe/stripe-js';
import { useShopStore } from '@/Stores/ShopStore'
import { Inertia } from "@inertiajs/inertia";
export default {
    setup() {
        // const shopStore = useShopStore()

    },
    props: {
        user: Object,
    },
    data() {
        const shopStore = useShopStore()
        shopStore.customer = this.user
        return {
            stripe: {},
            cardElement: {},
            customer: shopStore.customer,
            cart: shopStore.cart,
            order: [],
            paymentProcessing: false,
            country: shopStore.customer.country,
            region: shopStore.customer.province
        }
    },
    async mounted() {
        this.stripe = await loadStripe(process.env.MIX_STRIPE_KEY);

        const elements = this.stripe.elements();
        this.cardElement = elements.create('card', {
            classes: {
                base: 'bg-gray-100 py-3 px-2 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out'
            }
        });

        this.cardElement.mount('#card-element');
    },
    methods: {
        updateOrder(order) {
            this.order = order;
        },
        updateCart(cart) {
            this.cart = cart;
        },
        clearCart() {
            this.updateCart = [];
        },
        async processPayment() {
            // send the payment information to Laravel + Stripe
            this.paymentProcessing = true;

            const {paymentMethod, error} = await this.stripe.createPaymentMethod({
                    type: 'card',
                    card: this.cardElement,
                    billing_details: {
                        name: this.customer.name,
                        email: this.customer.email,
                        phone: this.customer.phone,
                        address: {
                            line1: this.customer.address1,
                            line2: this.customer.address2,
                            city: this.customer.city,
                            state: this.customer.province,
                            postal_code: this.customer.postalCode,
                            country: this.customer.country,
                        }
                    }
                }
            );

            if (error) {
                this.paymentProcessing = false;
                alert(error);
            } else {
                this.customer.payment_method_id = paymentMethod.id;
                this.customer.amount = this.cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
                this.customer.cart = JSON.stringify(this.cart);
                console.log('you arrived here.');

                axios.post('/shop/purchase', this.customer)
                    .then((response) => {
                        this.paymentProcessing = false;

                        // this.updateOrder(response.data);
                        this.order = this.cart;
                        this.cart = [];
                        console.log('you arrived at the second here.');
                        Inertia.post('/shop/summary', {
                                order: response.data
                        })
                    })
                    .catch((error) => {
                        this.paymentProcessing = false;
                        alert(error);
                    });
            }
        }
    },
    computed: {
        // ...mapState(useShopStore, ['cart']),
        // ...mapState(useShopStore, ['paymentProcessing']),
        // ...mapState(useShopStore, ['order']),
    },
}

</script>

