import { defineStore } from "pinia";
import {ref} from "vue";

export let useShopStore = defineStore('shopStore', {
    state() {
        return {
            products: [],
            cart: [],
            order: {}
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

    },

    getters: {
        cartQuantity() {
            return this.cart.reduce((acc, item) => acc + item.quantity, 0);
        },

        cartTotal() {
            let price = this.cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
            price = (price / 100);
            return price.toLocaleString('en-CA', {style: 'currency', currency: 'CAD'});
        }

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
