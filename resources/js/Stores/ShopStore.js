import { defineStore } from "pinia";
import {ref} from "vue";

export let useShopStore = defineStore('shopStore', {
    state() {
        return {
            products: [],
            cart: [],
            order: [],
            paymentProcessing: ref(false),
            customer: {},
            stripe: {},
            cardElement: {},
            upgradeSelection: '',
            upgradeStripeId: '',
            selectedSubscriptionPrice: null,
            showPaymentForm: ref(false),
            premiumMonthlyStripeId: 'price_1NoiAOKahp38LUVYPWtzQ8f1',
            premiumYearlyStripeId: 'price_1NhgZTKahp38LUVY8n9Skgwf',
            premiumForeverStripeId: 'price_1NoiBDKahp38LUVY5OGjIrCM'
        };
    },

    actions: {
        getProducts() {
            // fetch the products and their attached categories from the api
            axios.get('/api/products')
                .then((response) => {
                    this.products = response.data;
                })
                .catch( error => console.error(error));
        },
        addToCart(product) {
            let productInCartIndex = this.cart.findIndex(item => item.slug === product.slug);
            if (productInCartIndex !== -1) {
                        this.cart[productInCartIndex].quantity++;
                        return;
                    }
            product.quantity = 1;
            product.id = product.categories[0].categories.product_id;
            this.cart.push({ ... product });
        },
        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        updateOrder(order) {
            this.order = order;
        },
        updateCart(cart) {
            this.cart = cart;
        },
        clearCart() {
            this.updateCart = [];
        },
        cartLineTotal(item) {
            let price = (item.price * item.quantity);
            price = (price / 100);

            return price.toLocaleString('en-CA', {style: 'currency', currency: 'CAD'});
        },
        orderLineTotal(item) {
            let price = (item.price * item.products.quantity);
            price = (price / 100);

            return price.toLocaleString('en-CA', {style: 'currency', currency: 'CAD'});
        },
        upgradeMonthly() {
            this.upgradeSelection = 'monthly'
            this.upgradeStripeId = this.premiumMonthlyStripeId
            this.selectedSubscriptionPrice = 2500

        },
        upgradeYearly() {
            this.upgradeSelection = 'yearly'
            this.upgradeStripeId = this.premiumYearlyStripeId
            this.selectedSubscriptionPrice = 25000
        },
        upgradeForever() {
            this.upgradeSelection = 'forever'
            this.upgradeStripeId = this.premiumForeverStripeId
            this.selectedSubscriptionPrice = 99900
        },
        changeUpgradeSelection() {
            this.upgradeSelection = ''
            this.upgradeStripeId = ''
        }

    },

    getters: {
        cartQuantity() {
            return this.cart.reduce((acc, item) => acc + item.quantity, 0);
        },
        orderQuantity() {
            return this.order.products.length;
        },
        cartTotal() {
            let price = this.cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
            price = (price / 100);
            return price.toLocaleString('en-CA', {style: 'currency', currency: 'CAD'});
        },
        orderTotal() {
            let price = this.order.total;
            price = (price / 100);
            return price.toLocaleString('en-CA', {style: 'currency', currency: 'CAD'});
        },

        // updateQuantity(state) {
        //     let productInCartIndex = item.findIndex(item => item.slug === state.products.slug);
        //     if (productInCartIndex !== -1) {
        //         this.items[productInCartIndex].quantity++;
        //         return;
        //     }
        //         this.items[productInCartIndex].quantity = 1;
        //         this.items.push({ ...item });
        // },



    }
});
