<template>
  <Head title="Invite Creators and Audience Members"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-5 min-h-screen">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between">
        <div class="grid grid-cols-1 grid-rows-2 pt-4">
          <h1 class="text-3xl font-semibold">Invite Creators and Audience Members</h1>
        </div>
        <div class="grid grid-cols-1 grid-rows-2">
          <div>
            <button
                @click="appSettingStore.btnRedirect('/dashboard')"
                class="bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
            >Dashboard
            </button>
          </div>
        </div>
      </div>

      <section class="flex flex-row- flex-wrap justify-center gap-4">
        <div class="card w-96 bg-primary/30 text-accent-content text-center">
          <div class="card-body">
            <h2 class="">Creator Invites</h2>
            <p>You currently have</p>
            <span class="text-4xl">{{ totalAvailableCreatorInvites }}</span>
            <p>{{ creatorInvitationsText }}</p>
          </div>
        </div>
        <div class="card w-96 bg-accent/20 text-accent-content text-center">
          <div class="card-body">
            <h2 class="">Audience Invites</h2>
            <p>You currently have</p>
            <span class="text-4xl">{{ totalAvailableAudiencePlusVipInvites }}</span>
            <p>{{ audienceInvitationsText }}</p>
          </div>
        </div>
      </section>

      <section v-if="filteredInviteCodes.length === 0" class="mt-8 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold my-4">No Creator Invites Available</h2>
        <p class="text-lg mb-2">
          We appreciate your eagerness to grow the community! Rest assured, you'll be the first to know when new invites
          are ready.
        </p>
      </section>

      <form v-if="filteredInviteCodes.length > 0 && !$page.props.flash.success && !$page.props.flash.error"
            @submit.prevent="submit" class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold my-4">How to Invite a Fellow Creator</h2>
        <p class="text-lg mb-2">
          📧 <strong>Option 1:</strong> Direct Email Invitation
        </p>
        <p>
          Simply fill out our handy form, and we'll whisk off an email invite to your chosen creator. It's quick, easy,
          and perfect for sending personalized invites directly from notTV.
        </p>
        <div class="mt-5 space-y-3">
          <div>
            <!-- Conditionally render the select if more than one invite -->
            <select v-if="filteredInviteCodes.length > 1" class="select select-bordered w-full max-w-xs bg-white dark:bg-gray-800 dark:text-white"
                    v-model="selectedInvite" required>
              <option disabled :value="''">Select an invite</option>
              <option v-for="invite in filteredInviteCodes" :key="invite.ulid" :value="invite.ulid">
                {{ invite.code }} <!-- Assuming each invite has a unique code or some identifier -->
              </option>
            </select>

            <!-- If only one invite, you could display it directly or use it as the default model value -->
            <p v-else>{{
                filteredInviteCodes.length === 1 ? `Invite code: ${filteredInviteCodes[0].code}` : 'No invites available'
              }}</p>
          </div>
          <label class="input input-bordered flex items-center gap-2 bg-white dark:bg-gray-800 dark:text-white">
            Name
            <input type="text"
                   name="name"
                   id="name"
                   v-model="form.name"
                   class="grow border-none bg-white dark:bg-gray-800 dark:text-white"
                   placeholder="Daisy"
                   required/>
          </label>
          <div v-if="form.errors.name" v-text="form.errors.name"
               class="text-xs text-red-600 mt-1"></div>
          <label class="input input-bordered flex items-center gap-2 bg-white dark:bg-gray-800 dark:text-white">
            Email
            <input type="email"
                   name="email"
                   id="email"
                   v-model="form.email"
                   class="grow border-none bg-white dark:bg-gray-800 dark:text-white"
                   placeholder="daisy@site.com"
                   required/>
          </label>
          <div v-if="form.errors.email" v-text="form.errors.email"
               class="text-xs text-red-600 mt-1"></div>
          <label class="flex items-center gap-2 bg-white dark:bg-gray-800 dark:text-white">
            <textarea id="message"
                      v-model="form.message"
                      class="textarea textarea-bordered grow bg-white dark:bg-gray-800 dark:text-white"
                      placeholder="Message..."
                      required></textarea>
          </label>
          <div v-if="form.errors.message" v-text="form.errors.message"
               class="text-xs text-red-600 mt-1"></div>

          <button type="submit"
                  class="btn btn-primary"
                  :disabled="form.processing">
            Send
          </button>
        </div>

        <p class="text-lg my-8">- OR -</p>

        <p class="text-lg mb-2">
          📋 <strong>Option 2:</strong> Share an Invite Link & Code
        </p>
        <p>
          Prefer a more personal touch? Grab an invite link and code from below and share it through your favorite
          messaging platform, social media, or even a good old-fashioned carrier pigeon. The choice is yours!
        </p>
      </form>

      <div class="mt-4 max-w-4xl mx-auto space-y-6">
        <!-- Creator Invite Codes Table -->
        <section v-if="filteredInviteCodes.length > 0"
                 class="card bg-primary-content/30 w-full px-6 py-4 shadow-xl">
          <h2 class="card-title">Creator Invites</h2>
          <p>Expand the notTV community by inviting more creators to share and create with us.</p>
          <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead>
            <tr class="text-left">
              <th>Invite Link <span class="text-xs">click to copy</span></th>
              <th>Code <span class="text-xs">click to copy</span></th>
              <th>Uses Remaining</th>
              <th>Expires On</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="code in filteredInviteCodes" :key="code.id">
              <td>
                <div class="relative">
                  <button @click="copyToClipboard(`${$page.props.appUrl}/invite/${code.ulid}`, code.id, 'link')">
                    {{ `${$page.props.appUrl}/invite/${code.ulid}` }}
                  </button>
                  <transition name="fade-slide-up">
                    <div v-if="lastCopied.id === code.id && lastCopied.type === 'link'"
                         class="copied-animation text-green-500 absolute">
                      Copied to clipboard!
                    </div>
                  </transition>
                </div>
              </td>
              <td>
                <div class="relative">
                  <button class="" @click="copyToClipboard(code.code, code.id, 'code')">
                    {{ code.code }}
                  </button>
                  <transition name="fade-slide-up">
                    <div v-if="lastCopied.id === code.id && lastCopied.type === 'code'"
                         class="copied-animation text-green-500 absolute">
                      Copied to clipboard!
                    </div>
                  </transition>
                </div>
              </td>
              <td>{{ code.volume - code.used_count }}</td>
              <td>{{ userStore.formatDateInUserTimezone(code.expiry_date) || 'no expiry' }}</td>
            </tr>
            </tbody>
          </table>
        </section>
      </div>

      <section v-if="inviteCodes.audienceInviteCodes.length === 0 && inviteCodes.vipInviteCodes.length === 0"
               class="mt-8 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold my-4">No Audience Invites Currently Available</h2>
        <p class="text-lg mb-2">
          Your enthusiasm for expanding our community is truly inspiring! We'll make sure you're among the first to know
          as soon as more audience invites become available. Together, let's continue to shape a vibrant and inclusive
          media ecosystem.
        </p>
      </section>


      <div v-if="inviteCodes.audienceInviteCodes.length > 0 || inviteCodes.vipInviteCodes.length > 0"
           class="mt-20 mb-10 divider divider-accent text-accent font-semibold">And if you want to grow your
        audience...
      </div>

      <div class="mb-24 max-w-4xl mx-auto space-y-6">

        <!-- Audience Invite Codes Table -->
        <section v-if="inviteCodes.audienceInviteCodes.length > 0"
                 class="card w-full px-6 py-4 bg-accent/10 shadow-xl">
          <h2 class="card-title">Audience Invites</h2>
          <p>To grow your audience and invite new viewers.</p>
          <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead>
            <tr class="text-left">
              <th>Invite Code <span class="text-xs">click to copy</span></th>
              <th>Uses Remaining</th>
              <th>Expires On</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="code in inviteCodes.audienceInviteCodes" :key="code.id">
              <td>
                <div class="relative">
                  <button class="" @click="copyToClipboard(code.code, code.id, 'code')">
                    {{ code.code }}
                  </button>
                  <transition name="fade-slide-up">
                    <div v-if="lastCopied.id === code.id && lastCopied.type === 'code'"
                         class="copied-animation text-green-500 absolute">
                      Copied to clipboard!
                    </div>
                  </transition>
                </div>
              </td>
              <td>{{ code.volume - code.used_count }}</td>
              <td>{{ userStore.formatDateInUserTimezone(code.expiry_date) || 'no expiry' }}</td>
            </tr>
            </tbody>
          </table>
        </section>
        <!-- VIP Invite Codes Table -->
        <section v-if="inviteCodes.vipInviteCodes.length > 0" class="card w-full px-6 py-4 bg-yellow-100 shadow-xl">
          <h2 class="card-title">VIP Invites</h2>
          <p>Grant special contributors and viewers exclusive access to new features and events.</p>
          <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead>
            <tr class="text-left">
              <th>Invite Code <span class="text-xs">click to copy</span></th>
              <th>Uses Remaining</th>
              <th>Expires On</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="code in inviteCodes.vipInviteCodes" :key="code.id">
              <td>
                <div class="relative">
                  <button class="" @click="copyToClipboard(code.code, code.id, 'code')">
                    {{ code.code }}
                  </button>
                  <transition name="fade-slide-up">
                    <div v-if="lastCopied.id === code.id && lastCopied.type === 'code'"
                         class="copied-animation text-green-500 absolute">
                      Copied to clipboard!
                    </div>
                  </transition>
                </div>
              </td>
              <td>{{ code.volume - code.used_count }}</td>
              <td>{{ userStore.formatDateInUserTimezone(code.expiry_date) || 'no expiry' }}</td>
            </tr>
            </tbody>
          </table>
        </section>
      </div>


    </div>
  </div>
  <!--                </div>-->
  <!--            </div>-->
  <!--        </div>-->
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import Message from '@/Components/Global/Modals/Messages'
import { computed, onMounted, ref, watch } from 'vue'
import { useClipboard } from '@vueuse/core'
import { useForm } from '@inertiajs/inertia-vue3'

usePageSetup('invite')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

const props = defineProps({
  inviteCodes: Object,
})

let form = useForm({
  name: '',
  email: '',
  message: '',
})

// Computed property to filter invite codes
const filteredInviteCodes = computed(() => {
  return props.inviteCodes.creatorInviteCodes.filter(code => !code.team_id && !code.claimed);
});

// Model for the selected invite
const selectedInvite = ref(filteredInviteCodes.value.length === 1 ? filteredInviteCodes.value[0].ulid : '');

// Computed property to default to the first invite if more than one
const defaultSelectedInvite = computed(() => {
  return filteredInviteCodes.value.length > 0 ? filteredInviteCodes.value[0].ulid : '';
});

// Dynamically set source for the clipboard
const sourceToCopy = ref('')
const {copy, copied} = useClipboard()
const lastCopied = ref({id: null, type: null})

// Watch for changes in the inviteCodes and set the default selected invite if only one invite code is available
watch(() => props.inviteCodes, (newInviteCodes) => {
  const newFilteredInviteCodes = newInviteCodes.creatorInviteCodes.filter(code => !code.team_id && !code.claimed);
  if (newFilteredInviteCodes.length === 1) {
    selectedInvite.value = newFilteredInviteCodes[0].ulid;
  } else if (newFilteredInviteCodes.length > 1) {
    selectedInvite.value = defaultSelectedInvite.value;
  } else {
    selectedInvite.value = '';
  }
}, { immediate: true });


let submit = () => {
  console.log('form submitted, ulid: ' + selectedInvite.value)
  if (!selectedInvite.value) {
    // Handle the case when no invite is selected
    userStore.setGeneralServiceNotification('Invite Code Not Selected', 'You must select a valid invite code to invite a creator.')
    return
  }

  const inviteCodeUlid = selectedInvite.value

  form.post(`/invite/${inviteCodeUlid}/send-creator-email-invitation`, {
    ...form.data, // Include form data
  })
  form.name = ''
  form.email = ''
  form.message = ''
}

function copyToClipboard(text, id, type) {
  sourceToCopy.value = text
  copy(text)
  lastCopied.value = {id, type}

  setTimeout(() => {
    lastCopied.value = {id: null, type: null} // Reset after the animation
  }, 3000) // Ensure this matches your animation duration
}

const totalAvailableAudienceInvites = computed(() => {
  return props.inviteCodes.audienceInviteCodes.reduce((total, code) => total + (code.volume - code.used_count), 0)
})

const totalAvailableVIPInvites = computed(() => {
  return props.inviteCodes.vipInviteCodes.reduce((total, code) => total + (code.volume - code.used_count), 0)
})

const totalAvailableCreatorInvites = computed(() => {
  return props.inviteCodes.creatorInviteCodes.reduce((total, code) => total + (code.volume - code.used_count), 0)
})

const totalAvailableAudiencePlusVipInvites = computed(() => {
  return totalAvailableAudienceInvites.value + totalAvailableVIPInvites.value
})

const creatorInvitationsText = computed(() => {
  const count = totalAvailableCreatorInvites.value // Assuming this is your computed property for counting available creator invites
  return `creator invitation${count === 1 ? '' : 's'} available`
})

const audienceInvitationsText = computed(() => {
  const count = totalAvailableAudiencePlusVipInvites.value // Assuming this is your computed property for counting available creator invites
  return `audience invitation${count === 1 ? '' : 's'} available`
})


</script>

<style scoped>
@keyframes fadeSlideUp {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  10% {
    opacity: 1;
    transform: translateY(0);
  }
  90% {
    opacity: 1;
    transform: translateY(0);
  }
  100% {
    opacity: 0;
    transform: translateY(-20px);
  }
}

.copied-animation {
  animation: fadeSlideUp 3s ease-in-out forwards;
  position: absolute;
  z-index: 10; /* Ensure it's above other content */
  background: rgba(255, 255, 255, 1); /* Semi-transparent white background */
  border: 1px solid #ccc; /* Optional: adds a subtle border */
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2); /* Optional: adds a slight shadow for depth */
  padding: 4px 8px;
  border-radius: 4px; /* Soften the edges */
  top: -30px; /* Adjust this value to position it closer or further */
  left: 0; /* Center horizontally relative to the button */
  transform: translateX(-50%); /* Center it */
  white-space: nowrap; /* Keep the message on one line */
}
</style>
