<template>

  <Head v-if="shopStore.selectedFavourite" :title="`Contribution to ${shopStore.selectedFavourite.name}`"/>
  <Head v-else :title="`Contribute a Donation`"/>

  <div class="w-full lace-self-center flex flex-col gap-y-3 bg-orange-400">
    <div id="topDiv" class="w-full bg-gray-800 text-gray-50 dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <!--            <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>-->

      <header class="flex justify-center pt-4 mt-2 mb-4">
        <h1 class="text-3xl font-semibold pb-3">
          <span v-if="shopStore.selectedFavourite.name" class="capitalize">Contribute to {{
              shopStore.selectedFavourite.name
            }}</span>
          <span v-else>Contribute to notTV</span>
        </h1>
      </header>


      <section id="security-assurance">
        <div style="text-align: center; margin-bottom: 20px;">
          <!-- Font Awesome Padlock Icon -->
          <font-awesome-icon icon="fa-lock" aria-hidden="true" style="font-size: 24px; color: #2c3e50;"/>
        </div>
        <div style="text-align: center; max-width: 600px; margin: auto;">
          <h2>Your Security, Our Priority</h2>
          <p>We take your security seriously. All transactions are encrypted and processed through secure payment
            gateways. Your payment information is never stored on our servers, ensuring your data remains private and
            protected.</p>
        </div>
      </section>


      <div class="flex flex-col justify-center items-center w-full mx-auto mt-8">
        <div v-if="shopStore.selectedFavourite.name" class="flex flex-col text-center">
          <h2 class="text-2xl font-semibold text-gray-100 dark:text-gray-100">${{ shopStore.donationAmount }} Donation
            to</h2>
          <div class="flex items-center gap-2 mt-2">
            <FavouriteSelectedImage :item="shopStore.selectedFavourite"/>
            <span class="text-xl">{{ shopStore.selectedFavourite.name }}</span>
          </div>
        </div>
        <div v-else class="flex flex-col">
          <h2 class="text-2xl font-semibold text-gray-100 dark:text-gray-100 mb-2">${{ shopStore.donationAmount }}
            Donation
            to</h2>
          <img :src="`/storage/images/logo_white.svg`" alt="notTV Logo White" class="w-30">
        </div>

        <div v-if="!shopStore.showPaymentForm"
             class="flex flex-col w-full items-center justify-center text-center mx-auto mt-8 px-12">
          <h2 class="mt-6 mx-auto text-xl font-semibold text-white dark:text-gray-100">Payment form is loading...</h2>
          <span class="loading loading-dots loading-lg text-info"></span>
        </div>

        <form id="payment-form" class="w-full" @submit.prevent="submit">
          <div id="link-authentication-element" class="mb-2"></div>
          <div id="payment-element" class="w-full"></div>
          <div class="mb-2 text-xs text-gray-300">
            By clicking ‘Complete Payment’, you agree to our Terms and Conditions and acknowledge our Privacy Policy.
          </div>
          <button id="submit" class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                  :disabled="processing">
            <div class="spinner hidden" id="spinner"></div>
            <span v-if="!processing && shopStore.showPaymentForm" id="button-text">Complete Payment</span>
          </button>
          <div id="payment-message" class="hidden">{{ paymentMessage }}</div>
        </form>
        <!--        <form id="payment-form" class="w-full">-->
        <!--          <div id="link-authentication-element" class="mb-2">-->
        <!--            &lt;!&ndash;Stripe.js injects the Link Authentication Element&ndash;&gt;-->
        <!--          </div>-->
        <!--          <div id="payment-element" class="w-full">-->
        <!--            &lt;!&ndash;Stripe.js injects the Payment Element&ndash;&gt;-->
        <!--          </div>-->
        <!--          <div class="mb-2 text-xs text-gray-300">By clicking ‘Complete Payment’, you agree to our Terms and Conditions-->
        <!--            and acknowledge our Privacy Policy.-->
        <!--          </div>-->
        <!--          <button id="submit"-->
        <!--                  class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"-->
        <!--                  @click.prevent="submit()">-->
        <!--            <div class="spinner hidden" id="spinner"></div>-->
        <!--            <span id="button-text">Complete Payment</span>-->
        <!--          </button>-->
        <!--          <div id="payment-message" class="hidden"></div>-->
        <!--        </form>-->

        <div>Your payment is secured with SSL encryption.</div>

        <!-- {/* Show any error or success messages */}-->
        <div v-if="error" id="payment-error" class="text-red-600 font-semibold w-full my-2"> {{ error }}</div>
        <!--        <div v-if="message" id="payment-message" class="mt-1 text-white">{{ message }}</div>-->
      </div>

    </div>
  </div>

</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useUserStore } from '@/Stores/UserStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useShopStore } from '@/Stores/ShopStore'
import { useForm } from '@inertiajs/inertia-vue3'
import { loadStripe } from '@stripe/stripe-js'
import FavouriteSelectedImage from '@/Components/Pages/Shop/FavouriteSelectedImage.vue'

usePageSetup('shop/subscribe')

const userStore = useUserStore()
const appSettingStore = useAppSettingStore()
const notificationStore = useNotificationStore()
const shopStore = useShopStore()

let props = defineProps({
  user: Object,
  error: String,
  intent: Object,
  elements: {},
})

const paymentMessage = ref('')
const processing = ref(false)
let stripe
let elements

const stripePromise = loadStripe(process.env.MIX_STRIPE_KEY)

const showPaymentForm = () => {

  document.getElementById('payment-form').style.display = 'block'
}

const initialize = () => {
  const clientSecret = props.intent.client_secret
  const appearance = {
    theme: 'stripe',
    variables: {
      colorPrimary: '#0570de',
      colorBackground: '#ffffff',
      colorBackgroundText: '#ffffff',
      colorText: '#222222',
      colorDanger: '#df1b41',
      fontFamily: 'Ideal Sans, system-ui, sans-serif',
      spacingUnit: '4px',
    },
    rules: {
      '.Label': {
        color: 'var(--colorBackgroundText)',
      },
    },
  }

  elements = stripe.elements({appearance, clientSecret})

  const paymentElementOptions = {
    layout: 'tabs',
  }

  const paymentElement = elements.create('payment', paymentElementOptions)
  paymentElement.mount('#payment-element')
  shopStore.showPaymentForm = true
}


const createPaymentIntent = async () => {
  try {
    const amountInCents = shopStore.donationAmount * 100 // Convert to cents
    const {data} = await axios.post('/shop/payment-intent', {
      amount: amountInCents,
      favourite_item_id: shopStore.selectedFavourite.id, // Include favourite item
      favourite_item_name: shopStore.selectedFavourite.name, // Include favourite item
      favourite_item_type: shopStore.selectedFavouriteType, // Include favourite item
    })
    return data.clientSecret
  } catch (error) {
    console.error('Error creating payment intent:', error)
    showMessage('Failed to create payment intent. Please try again.')
    setLoading(false)
    throw error
  }
}

// Watch for changes in userStore.hasConsentedToCookies
watch(
    () => userStore.hasConsentedToCookies,
    (newVal) => {
      appSettingStore.showCookieBanner = !newVal;
      showPaymentForm()
    },
    { immediate: true } // Execute immediately on load
);



onMounted(async () => {
      if (!userStore.hasConsentedToCookies) {
        appSettingStore.showCookieBanner = true
      }
      shopStore.customer = props.user
      stripe = await stripePromise
      initialize()
    },
)


const submit = async () => {
  setLoading(true)
  paymentMessage.value = ''

  try {
    // Call elements.submit() to gather all required fields
    const {error: submitError} = await elements.submit()
    if (submitError) {
      showMessage(submitError.message)
      setLoading(false)
      return
    }

    const clientSecret = await createPaymentIntent()

    const {error, paymentIntent} = await stripe.confirmPayment({
      elements,
      confirmParams: {
        return_url: 'https://not.tv/contribute/donation_success', // Replace with your success URL
        // receipt_email: props.user.email,
        receipt_email: 'travis@not.tv',
        payment_method_data: {
          billing_details: {
            name: props.user.name, // Provide the customer name
          },
        },
      },
      clientSecret,
    })

    if (error) {
      if (error.type === 'card_error' || error.type === 'validation_error') {
        notificationStore.setGeneralServiceNotification(error.code, error.message)
        showMessage(error.message)
      } else {
        showMessage('An unexpected error occurred.')
      }
      setLoading(false)
    } else {
      let form = useForm({
        paymentMethod: paymentIntent.payment_method,
        plan: shopStore.upgradeStripeId,
      })

      form.post('/contribute/donation_success', {
        onFinish: () => {
          setLoading(false)
        },
      })
    }
  } catch (error) {
    console.error('Error in payment process:', error)
  }
}


// const options = {
//   mode: 'setup',
//   currency: 'cad',
// }
//
// let stripe
// let elements

// const paymentMessage = ref('');
// const processing = ref(false);
// const stripePromise = loadStripe(process.env.MIX_STRIPE_KEY);

// onMounted(async () => {
//   shopStore.customer = props.user
//
//   stripe = await loadStripe(StripeAPIKey)
//
//   initialize()
//
//   showPaymentForm()
//
//   document
//       .querySelector('#payment-form')
//   // .addEventListener("submit", handleSubmit);
//
//
// })

// function showPaymentForm() {
//   shopStore.showPaymentForm = true
// }

// function initialize() {
//
//   const clientSecret = props.intent.client_secret
//   const appearance = {
//     theme: 'stripe',
//     variables: {
//       colorPrimary: '#0570de',
//       colorBackground: '#ffffff',
//       colorBackgroundText: '#ffffff',
//       colorText: '#222222',
//       colorDanger: '#df1b41',
//       fontFamily: 'Ideal Sans, system-ui, sans-serif',
//       spacingUnit: '4px',
//     },
//     rules: {
//       '.Label': {
//         color: 'var(--colorBackgroundText)',
//       },
//     },
//   }
//
//   elements = stripe.elements({appearance, clientSecret})

// this is for linkPayments
// const linkAuthenticationElement = elements.create("linkAuthentication");
// linkAuthenticationElement.mount("#link-authentication-element");
//
// linkAuthenticationElement.on('change', (event) => {
//     emailAddress = event.value.email;
// });

//   const paymentElementOptions = {
//     layout: 'tabs',
//   }
//
//   const paymentElement = elements.create('payment', paymentElementOptions)
//   paymentElement.mount('#payment-element')
//
// }

// async function submit() {
//   if (shopStore.upgradeStripeId === '' || null) {
//     alert('Please select a subscription.')
//     return
//   }
//   setLoading(true)
//
//   const {setupIntent, error} = await stripe.confirmSetup({
//     elements,
//     confirmParams: {
//       // Make sure to change this to your payment completion page
//       return_url: 'https://not.tv/subscription_success',
//       // receipt_email: emailAddress,
//     },
//     redirect: 'if_required',
//
//
//   })

// This point will only be reached if there is an immediate error when
// confirming the payment. Otherwise, your customer will be redirected to
// your `return_url`. For some payment methods like iDEAL, your customer will
// be redirected to an intermediate site first to authorize the payment, then
// redirected to the `return_url`.

// if (error) {
//   if (error.type === 'card_error' || error.type === 'validation_error') {
//     showMessage(error.message)
//   } else {
//     showMessage('An unexpected error occurred.')
//     setLoading(false)
//   }
// } else {
//   // console.log(setupIntent)
//   let form = useForm({
//     paymentMethod: setupIntent.payment_method,
//     plan: shopStore.upgradeStripeId,
//   })
//
//   //Submit the form
//   form.post('/contribute/subscription')
//
// }
// }


// ------- UI helpers -------

function showMessage(messageText) {

  const messageContainer = document.querySelector('#payment-message')

  messageContainer.classList.remove('hidden')
  messageContainer.textContent = messageText

  setTimeout(function () {
    messageContainer.classList.add('hidden')
    messageContainer.textContent = ''
  }, 4000)
}

// Show a spinner on payment submission
function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
    document.querySelector('#submit').disabled = true
    document.querySelector('#spinner').classList.remove('hidden')
    document.querySelector('#button-text').classList.add('hidden')
  } else {
    document.querySelector('#submit').disabled = false
    document.querySelector('#spinner').classList.add('hidden')
    document.querySelector('#button-text').classList.remove('hidden')
  }
}

</script>

<style scoped>

/* Variables */
* {
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, sans-serif;
  font-size: 16px;
  -webkit-font-smoothing: antialiased;
  display: flex;
  justify-content: center;
  align-content: center;
  height: 100vh;
  width: 100vw;
}

form {
  width: 30vw;
  min-width: 500px;
  align-self: center;
  box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
  0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
  border-radius: 7px;
  padding: 40px;
}

.hidden {
  display: none;
}

#payment-message {
  color: rgb(105, 115, 134);
  font-size: 16px;
  line-height: 20px;
  padding-top: 12px;
  text-align: center;
}

#payment-element {
  margin-bottom: 24px;
}

/* Buttons and links */
button {
  background: #5469d4;
  font-family: Arial, sans-serif;
  color: #ffffff;
  border-radius: 4px;
  border: 0;
  padding: 12px 16px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  display: block;
  transition: all 0.2s ease;
  box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
  width: 100%;
}

button:hover {
  filter: contrast(115%);
}

button:disabled {
  opacity: 0.5;
  cursor: default;
}

/* spinner/processing state, errors */
.spinner,
.spinner:before,
.spinner:after {
  border-radius: 50%;
}

.spinner {
  color: #ffffff;
  font-size: 22px;
  text-indent: -99999px;
  margin: 0px auto;
  position: relative;
  width: 20px;
  height: 20px;
  box-shadow: inset 0 0 0 2px;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}

.spinner:before,
.spinner:after {
  position: absolute;
  content: "";
}

.spinner:before {
  width: 10.4px;
  height: 20.4px;
  background: #5469d4;
  border-radius: 20.4px 0 0 20.4px;
  top: -0.2px;
  left: -0.2px;
  -webkit-transform-origin: 10.4px 10.2px;
  transform-origin: 10.4px 10.2px;
  -webkit-animation: loading 2s infinite ease 1.5s;
  animation: loading 2s infinite ease 1.5s;
}

.spinner:after {
  width: 10.4px;
  height: 10.2px;
  background: #5469d4;
  border-radius: 0 10.2px 10.2px 0;
  top: -0.1px;
  left: 10.2px;
  -webkit-transform-origin: 0px 10.2px;
  transform-origin: 0px 10.2px;
  -webkit-animation: loading 2s infinite ease;
  animation: loading 2s infinite ease;
}

@-webkit-keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@media only screen and (max-width: 600px) {
  form {
    width: 80vw;
    min-width: initial;
  }
}


</style>





