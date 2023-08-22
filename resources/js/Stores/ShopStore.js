import { defineStore } from "pinia";
import {ref} from "vue";

export let useShopStore = defineStore('shopStore', {
    state() {
        return {
            products: [],
            cart: {},
            order: {}
        };
    },

    actions: {
        getProducts() {
            // fetch the products and their attached categories from the api
            axios.get('/api/products')
                .then((response) => {
                    this.updateProducts(response.data);
                })
                .catch( error => console.error(error));
        },
        clearCart() {
            this.updateCart = [];
        },
        updateProducts(products) {
            this.products = products;
        },
        addToCart(product) {
            let productInCartIndex = this.cart.findIndex(item => item.slug === product.slug);
            if (productInCartIndex !== -1) {
                this.cart[productInCartIndex].quantity++;
                return;
            } else
                this.product.quantity = 1;
            this.cart.push(product);
        },
    },

    getters: {

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        updateOrder(order) {
            this.order = order;
        },
        updateCart(cart) {
            this.cart = cart;
        }
    }
});
