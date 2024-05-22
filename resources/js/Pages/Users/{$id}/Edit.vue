<template>
  <Head title="Edit User"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="light:bg-white light:text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between mb-6">

        <div>
          <div class="font-bold mb-4 text-red-700">EDIT USER</div>
          <h1 class="text-3xl">
            <Link :href="`/users/${props.userEdit.id}`" class="text-red-700 font-bold uppercase">
              {{ props.userEdit.name }}
            </Link>
          </h1>
        </div>
        <div class="flex flex-row justify-end">
          <div>
            <button
                @click="submit"
                class="h-fit bg-blue-600 text-white rounded-lg py-2 px-4 hover:bg-blue-500"
                :disabled="form.processing"
            >
              Save
            </button>
          </div>
          <CancelButton />
        </div>

      </div>

      <div class="max-w-md mx-auto mt-8">
        <div class="flex flex-row justify-between">
          <div>
            <div class="mb-6"><img :src="props.userEdit.profile_photo_url" class="rounded-full h-20 w-20 object-cover"/>
            </div>
            <div class=""><span class="text-xs uppercase">User ID: </span><span
                class="font-semibold">{{ props.userEdit.id }}</span></div>
            <div class=""><span class="text-xs uppercase">Subscription Status: </span><span
                class="font-semibold">{{ props.subscriptionStatus }}</span></div>
            <div class="" v-if="props.userEdit.role_id === 4"><span class="text-xs uppercase">Creator #: </span><span
                class="font-semibold">{{ props.userEdit.creatorNumber }}</span></div>
          </div>
          <div class="flex flex-col space-y-2 align-bottom">
            <button v-if="!isNewsPerson"
                    @click.prevent="openSelectNewsPersonRoleModal"
                    class="text-white font-semibold bg-yellow-600 hover:bg-yellow-800 hover:text-gray-100 rounded px-4 py-2 w-fit h-12">
              Add User to Newsroom
            </button>
            <button v-if="isNewsPerson"
                    @click.prevent="removeUserFromNewsroom"
                    class="text-white font-semibold bg-yellow-600 hover:bg-yellow-800 hover:text-gray-100 rounded px-4 py-2 w-fit h-12">
              Remove User from Newsroom
            </button>
            <button v-if="isNewsPerson"
                    @click.prevent="openChangeNewsPersonRoleModal"
                    class="text-white font-semibold bg-yellow-600 hover:bg-yellow-800 hover:text-gray-100 rounded px-4 py-2 w-fit h-12">
              Change User News Role(s)
            </button>

            <button v-if="!isVip"
                    @click.prevent="addUserToVip"
                    class="text-white font-semibold bg-indigo-600 hover:bg-indigo-800 hover:text-gray-100 rounded px-4 py-2 w-fit h-12">
              Make User a VIP
            </button>
            <button v-if="isVip"
                    @click.prevent="removeUserFromVip"
                    class="text-white font-semibold bg-orange-600 hover:bg-orange-800 hover:text-gray-100 rounded px-4 py-2 w-fit h-12">
              Remove User from VIP
            </button>
            <button v-if="!hasSubscription"
                    @click.prevent="getUserSubscriptionFromStripe"
                    class="text-white font-semibold bg-blue-600 hover:bg-blue-800 hover:text-gray-100 rounded px-4 py-2 w-fit h-12">
              Get Subscription From Stripe
            </button>
          </div>
        </div>

        <form @submit.prevent="submit" class="mt-6">
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="name"
            >
              User Role
            </label>
            <select
                class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs bg-white text-gray-700"
                v-model="form.role_id"
            >
              <option value="1">Standard User</option>
              <option value="4">Creator</option>
            </select>

            <div v-if="form.errors.role_id" v-text="form.errors.role_id" class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="my-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="name"
            >
              Name
            </label>

            <input v-model="form.name"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="name"
                   id="name"
                   required
            >
            <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="email"
            >
              Email
            </label>

            <input v-model="form.email"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="email"
                   name="email"
                   id="email"
                   required
            >
            <div v-if="form.errors.email" v-text="form.errors.email" class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="phone"
            >
              Phone Number
            </label>

            <input v-model="form.phone"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="phone"
                   id="phone"
            >
            <div v-if="form.errors.phone" v-text="form.errors.phone" class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="address1"
            >
              Address 1
            </label>

            <input v-model="form.address1"
                   class="border border-gray-400 p-2 mb-2 w-full rounded-lg text-black"
                   type="text"
                   name="address1"
                   id="address1"
            >
          </div>

          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="address2"
            >
              Address 2
            </label>

            <input v-model="form.address2"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="address2"
                   id="address2"
            >
            <div v-if="form.errors.address1" v-text="form.errors.address1" class="text-xs text-red-600 mt-1"></div>
            <div v-if="form.errors.address2" v-text="form.errors.address2" class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="city"
            >
              City
            </label>

            <input v-model="form.city"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="city"
                   id="city"
            >
            <div v-if="form.errors.city" v-text="form.errors.city" class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="province"
            >
              Province
            </label>

            <input v-model="form.province"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="province"
                   id="province"
            >
            <div v-if="form.errors.province" v-text="form.errors.province" class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="country"
            >
              Country
            </label>

            <input v-model="form.country"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="country"
                   id="country"
            >
            <div v-if="form.errors.country" v-text="form.errors.country" class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                   for="postal_code"
            >
              Postal Code
            </label>

            <input v-model="form.postalCode"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="postalCode"
                   id="postalCode"
            >
            <div v-if="form.errors.postalCode" v-text="form.errors.postalCode" class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-50"
                   for="text"
            >
              Stripe ID
            </label>

            <input v-model="form.stripe_id"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="stripe_id"
                   id="stripe_id"
            >
            <div v-if="form.errors.stripe_id" v-text="form.errors.stripe_id" class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="flex justify-between mb-6">
            <JetValidationErrors class="mr-4"/>
            <button
                @click="submit"
                class="h-fit bg-blue-600 text-white rounded py-2 px-4 hover:bg-blue-500"
                :disabled="form.processing"
            >
              Save
            </button>
            <div v-if="form.errors" v-text="form.errors" class="text-xs text-red-600 mt-1"></div>
          </div>
        </form>
      </div>

    </div>

    <dialog id="newsPersonRoleSelectModal" class="modal">
      <div class="modal-box text-black bg-white dark:text-gray-100 dark:bg-gray-900">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-2">Select News Person Role</h3>
        <select v-model="selectedRoles" class="select select-bordered w-full max-w-xs text-black bg-white dark:text-gray-100 dark:bg-gray-900" multiple>
          <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
        </select>

        <button class="btn btn-primary mt-4" @click="addUserToNewsroom">Add User to Newsroom</button>

      </div>
    </dialog>

    <dialog id="changeNewsPersonRoleModal" class="modal">
      <div class="modal-box text-black bg-white dark:text-gray-100 dark:bg-gray-900">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-2">Change News Person Role</h3>
        <select v-model="selectedRoles" class="select select-bordered w-full max-w-xs text-black bg-white dark:text-gray-100 dark:bg-gray-900" multiple>
          <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
        </select>

        <button class="btn btn-primary mt-4" @click="addUserToNewsroom">Change User Role(s)</button>

      </div>
    </dialog>

  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import CancelButton from '@/Components/Global/Buttons/CancelButton.vue'
import { ref } from 'vue'

usePageSetup('users.edit')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  userEdit: Object,
  isNewsPerson: Boolean,
  newsPersonId: Number,
  isVip: Number,
  isSubscriber: Boolean,
  hasSubscription: null,
  subscriptionStatus: String,
})

let form = useForm({
  id: props.userEdit.id,
  name: props.userEdit.name,
  email: props.userEdit.email,
  role_id: props.userEdit.role_id,
  address1: props.userEdit.address1,
  address2: props.userEdit.address2,
  city: props.userEdit.city,
  province: props.userEdit.province,
  country: props.userEdit.country,
  postalCode: props.userEdit.postalCode,
  phone: props.userEdit.phone,
  stripe_id: props.userEdit.stripe_id,
})

const roles = ref([]);
const selectedRoles = ref([]);

function reset() {
  form.reset()
}

let submit = () => {
  form.patch(route('users.update', props.userEdit.id))
}

async function openSelectNewsPersonRoleModal() {
  document.getElementById('newsPersonRoleSelectModal').showModal()
  await fetchRoles();
}

async function openChangeNewsPersonRoleModal() {
  await fetchRoles();
  axios.get(`/newsroom/newsPerson/${props.newsPersonId}/roles`)
      .then(response => {
        selectedRoles.value = response.data.map(role => role.id);
        document.getElementById('changeNewsPersonRoleModal').showModal();
      })
      .catch(error => {
        console.error("There was an error fetching the user's roles: ", error);
      });
}

// Fetch roles from the backend
async function fetchRoles() {
  await axios.get('/api/news-people-roles')
      .then(response => {
        roles.value = response.data;
      })
      .catch(error => {
        console.error("There was an error fetching the roles: ", error);
      });
}

function addUserToNewsroom() {
  router.visit('/newsroom/newsPerson', {
    method: 'post',
    data: {
      id: props.userEdit.id,
      name: props.userEdit.name,
      role_ids: selectedRoles.value, // Send an array of selected role IDs
    },
  })
}

function updateUserNewsRoles() {
  router.post('/newsroom/newsPerson/updateRoles', {
    id: props.userEdit.id,
    role_ids: selectedRoles.value, // Send an array of selected role IDs
  })
}

function removeUserFromNewsroom() {
  if (confirm('Are you sure you want to remove this person from the news team?')) {
    form.delete(route('newsPerson.destroy', props.userEdit.id))
  }
}

function addUserToVip() {
  if (confirm('Are you sure you want to add this person to VIP?')) {
    form.patch(route('user.vip.add'))
  }
}

function removeUserFromVip() {
  if (confirm('Are you sure you want to remove this person from VIP?')) {
    form.patch(route('user.vip.remove', props.userEdit.id))
  }
}

function getUserSubscriptionFromStripe() {
  if (!props.userEdit.stripe_id && !form.stripe_id) {
    alert('User must have a Stripe ID')
  } else if (!props.userEdit.stripe_id && form.stripe_id) {
    alert('Please save the Stripe ID before getting the subscription.')
  } else if (confirm('Are you sure you want to retrieve the subscription from Stripe? This will take a minute.')) {
    router.post(route('getUserSubscriptionsFromStripe', {'id': form.id}))
  }
}

function back() {
  let urlPrev = usePage().props.urlPrev
  if (urlPrev !== 'empty') {
    router.visit(urlPrev)
  }
}

</script>
