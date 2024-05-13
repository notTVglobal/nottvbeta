<template>
  <Transition
      enter-from-class="opacity-0 scale-100"
      enter-to-class="opacity-100 scale-100"
      enter-active-class="transition duration-300"
      leave-active-class="transition duration-200"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-100"
  >
    <div v-if="showBanner" class="cookie-container">

      <div class="cookie-banner">
        <div class="cookie-banner-content">
          <div class="cookie-banner-header lg:px-48">
            <div class="flex w-full justify-center items-center gap-4">
              <img src="/storage/images/Ping.png" alt="Ping" class="w-20">
              <h2>Cookie Usage Information</h2>
            </div>
            <p>At notTV, we believe in a better, faster, and more transparent web experience. While we strive to eliminate third-party cookies, we currently use a couple of them to process payments securely and keep our site running smoothly.</p>
            <p>By continuing to use our site, you consent to these essential cookies. We're committed to minimizing their use and enhancing your privacy.</p>
          </div>
          <div v-if="showDetails" class="cookie-details">
            <p>As part of our payment processing and security measures, the following cookies are stored:</p>
            <ul>
              <li class="cookie-item">
                <strong>Session Cookie:</strong>
                <p>This cookie is used to maintain your session and enhance your user experience on our site.</p>
              </li>
              <li class="cookie-item">
                <strong>Cloudflare Cookies:</strong>
                <ul>
                  <li>
                    <strong>cf_ob_info:</strong>
                    <p>Contains information about the Railgun network connection, used to speed up content delivery.</p>
                  </li>
                  <li>
                    <strong>cf_use_ob:</strong>
                    <p>Used in conjunction with cf_ob_info to optimize web traffic served via Cloudflare.</p>
                  </li>
                </ul>
              </li>
              <li class="cookie-item">
                <strong>Stripe Cookies:</strong>
                <ul>
                  <li>
                    <strong>__stripe_mid:</strong>
                    <p>Used to identify the user and detect suspicious activity.</p>
                  </li>
                  <li>
                    <strong>__stripe_sid:</strong>
                    <p>Used to process secure payments.</p>
                  </li>
                </ul>
              </li>
              <li class="cookie-item">
                <strong>Brevo (SendInBlue) Cookie:</strong>
                <p>__cfruid: Used on the newsletter signup page to manage the server load and enhance performance.</p>
              </li>
            </ul>
            <p>For more details, please see our <a href="/privacy-policy" target="_blank">Privacy Policy</a> and <a href="https://stripe.com/privacy" target="_blank">Stripe's Privacy Policy</a>.</p>
          </div>
          <div class="cookie-banner-actions">
            <button @click="acceptCookies" class="accept-button font-semibold">Accept</button>
            <button @click="toggleDetails" class="details-button">{{ showDetails ? 'Hide Details' : 'Show Details' }}</button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { computed, onMounted, ref } from 'vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

const showBanner = computed(() => appSettingStore.showCookieBanner)
const showDetails = ref(false)

const acceptCookies = async () => {
  try {
    await axios.post('/users/consent-cookies')
    appSettingStore.showCookieBanner = false
    userStore.hasConsentedToCookies = true  // Update user consent status
  } catch (error) {
    console.error('Error setting cookie consent:', error)
  }
}


const toggleDetails = () => {
  showDetails.value = !showDetails.value
}

</script>

<style scoped>
.cookie-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent black background */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure it is on top */
  overflow: auto; /* Allow scrolling */
}

.cookie-banner {
  padding: 20px;
  background-color: #333; /* Dark background color */
  border: 1px solid #555; /* Slightly lighter border for contrast */
  border-radius: 8px;
  color: #f1f1f1; /* Light text color for readability */
  text-align: center;
  transition: opacity 0.3s, transform 0.3s; /* Ensure transition is applied */
  max-width: 90%;
  max-height: 90vh; /* Ensure it fits within the viewport */
  overflow-y: auto; /* Allow vertical scrolling if needed */
}


.cookie-banner-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.cookie-banner-header {
  text-align: center;
}

.cookie-details {
  text-align: left;
  margin-top: 10px;
}

.cookie-banner h2 {
  margin-top: 0;
  font-size: 1.5em;
}

.cookie-banner p {
  margin: 10px 0;
}

.cookie-banner ul {
  list-style-type: disc;
  padding-left: 20px;
  margin: 10px 0;
}

.cookie-banner ul ul {
  list-style-type: circle;
}

.cookie-banner-actions {
  margin-top: 20px;
}

.cookie-banner-actions button {
  background-color: #1e90ff;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  margin: 5px;
}

.cookie-banner-actions button:hover {
  background-color: #1c86ee; /* Slightly darker blue for hover effect */
}

@media (max-width: 600px) {
  .cookie-banner {
    flex-direction: column;
  }

  .cookie-banner-content {
    align-items: flex-start;
  }
}

.cookie-banner a {
  color: #1e90ff; /* Bright blue color for links */
  text-decoration: none; /* Remove underline from links */
}

.cookie-banner a:hover {
  text-decoration: underline; /* Underline links on hover for better UX */
}

.cookie-item {
  background-color: #444;
  border-radius: 5px;
  padding: 10px;
  margin-top: 10px;
}

.cookie-item ul {
  margin-top: 5px;
  padding-left: 20px;
}


.cookie-banner-actions .accept-button {
  background-color: #1a78d6; /* Slightly darker blue */
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
}

.cookie-banner-actions .accept-button:hover {
  background-color: #165ea8; /* Even darker on hover */
}

.cookie-banner-actions .details-button {
  background-color: #1e90ff; /* Lighter blue */
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
}

.cookie-banner-actions .details-button:hover {
  background-color: #1c86ee; /* Slightly darker blue for hover effect */
}


</style>
