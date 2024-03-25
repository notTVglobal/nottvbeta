<template>

  <Head title="Creator Registration"/>
  <div class="h-[calc(100vh)]">

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">

      <div class="flex flex-col mt-24 mb-8 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
        <div><ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/></div>
        <div class="uppercase">
          Creator Registration
        </div>
      </div>
      <main class="pb-8 hide-scrollbar">
        <div class="mx-auto w-full md:w-3/4 max-w-96 px-4 border-b border-gray-800 hide-scrollbar">
          <div class="text-center mb-8 px-4">
            <h2 class="text-lg md:text-xl text-gray-50 mb-4">Ready to Unleash Your Creativity?</h2>
            <p class="text-md text-gray-300 mb-6">Before you join us as a creator, we highly recommend watching our brief video. It shares the essence of becoming a part of notTV, highlighting how you can make an impact with your unique voice and stories.</p>
            <button @click="toggleVideoModal" class="text-white bg-blue-500 hover:bg-blue-700 font-semibold py-2 px-4 border border-blue-700 rounded">
              Watch the Video
            </button>
          </div>


          <div class="hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-full max-w-96 text-gray-900 rounded">

            <JetValidationErrors class="mb-4" />

            <form @submit.prevent="submit" class="pb-10">

              <div>
                <JetLabel for="name" value="Name" />
                <JetInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="name"
                />
              </div>
              <div v-if="form.errors.name" v-text="form.errors.name"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="email" value="Email" />
                <JetInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                />
              </div>
              <div v-if="form.errors.name" v-text="form.errors.name"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="password" value="Password" />
                <JetInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
              </div>

              <div v-if="form.errors.password" v-text="form.errors.password"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="password_confirmation" value="Confirm Password" />
                <JetInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
              </div>

              <div v-if="form.errors.password_confirmation" v-text="form.errors.password_confirmation"
                   class="text-xs text-red-600 mt-1"></div>


              <div hidden class="mt-4">
                <JetLabel for="address1" value="Address1" />
                <JetInput
                    id="address1"
                    v-model="form.address1"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="address1"
                />
              </div>
              <div v-if="form.errors.address1" v-text="form.errors.address1"
                   class="text-xs text-red-600 mt-1"></div>

              <div hidden class="mt-4">
                <JetLabel for="address2" value="Address2" />
                <JetInput
                    id="address2"
                    v-model="form.address2"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="address2"
                />
              </div>
              <div v-if="form.errors.address2" v-text="form.errors.address2"
                   class="text-xs text-red-600 mt-1"></div>

              <div hidden class="mt-4">
                <JetLabel for="city" value="City" />
                <JetInput
                    id="city"
                    v-model="form.city"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="city"
                />
              </div>
              <div v-if="form.errors.city" v-text="form.errors.city"
                   class="text-xs text-red-600 mt-1"></div>

              <div hidden class="mt-4">
                <JetLabel for="province" value="Province" />
                <JetInput
                    id="name"
                    v-model="form.province"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="province"
                />
              </div>
              <div v-if="form.errors.province" v-text="form.errors.province"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="country" value="Country" />
                <select id="country" v-model="form.country"
                        class="select select-bordered w-full max-w-xs mt-1 block">
                  <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }}</option>
                </select>
              </div>
              <div v-if="form.errors.country" v-text="form.errors.country"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="postalCode" value="Postal Code" />
                <JetInput
                    id="postalCode"
                    v-model="form.postalCode"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="postalCode"
                />
              </div>
              <div v-if="form.errors.postalCode" v-text="form.errors.postalCode"
                   class="text-xs text-red-600 mt-1"></div>

              <div hidden class="mt-4">
                <JetLabel for="phone" value="Phone" />
                <JetInput
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="phone"
                />
              </div>
              <div v-if="form.errors.phone" v-text="form.errors.phone"
                   class="text-xs text-red-600 mt-1"></div>


              <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <JetLabel for="terms">
                  <div class="flex items-center">
<!--                    <JetCheckbox id="terms" v-model="form.terms" name="terms" required />-->
                    <input id="terms"
                           name="terms"
                           type="checkbox"
                           v-model="form.terms"
                           required
                           class="checkbox checkbox-info" />
                    <div class="ml-2">
                      I agree to the <a :href="route('terms.show')" target="_blank" class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a> and <a :href="route('policy.show')" target="_blank" class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                    </div>
                  </div>
                </JetLabel>
                <div v-if="form.errors.terms" v-text="form.errors.terms"
                     class="text-xs text-red-600 mt-1"></div>
              </div>

              <div class="flex items-center justify-end mt-4">
                <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                  Register
                </JetButton>
              </div>

            </form>

          </div>


        </div>
      </main>

      <dialog id="creatorWelcomeVideoModal" class="modal">
        <div class="modal-box bg-black">
          <form method="dialog">
            <button @click="videoActive = false" class="btn btn-sm btn-circle absolute right-2 top-2">✕</button>
          </form>
        <video v-if="videoActive" src="https://cdn.nottv.io/public/2024/03/videos/IMG_6139_5020eb9e3c2bdc89476324e0711cc038.mov"
                controls/>
        </div>
        <form method="dialog" class="modal-backdrop">
          <button @click="videoActive = false">close</button>
        </form>
      </dialog>

    </div>
  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useForm } from '@inertiajs/inertia-vue3'
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import { Inertia } from '@inertiajs/inertia'
import { computed, onMounted, ref, watch } from 'vue'

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'creator.registration.show'

const props = defineProps({
  inviteCodeUlid: String,
})

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  // address1: '', // disabled for MVP
  // address2: '', // disabled for MVP
  // city: '', // disabled for MVP
  // province: '', // disabled for MVP
  // phone: '', // disabled for MVP
  country: '',
  postalCode: '',
  terms: false,
});

const countries = ref([]);

// Computed property to determine if the selected country is Canada
const isCanadaSelected = computed(() => form.country === countries.value.find(country => country.name === 'Canada')?.id);

const videoActive = ref(false)

const toggleVideoModal = () => {
  videoActive.value = true
  console.log('toggle Video Modal')
  document.getElementById('creatorWelcomeVideoModal').showModal()
}

onMounted(async () => {
  try {
    const response = await axios.get('/api/news-countries-simple-list');
    countries.value = response.data;

    // Set Canada as default country if it exists in the list
    const canada = countries.value.find(country => country.name === 'Canada');
    if (canada) {
      form.country = canada.id;
    }
  } catch (error) {
    console.error('Failed to fetch countries:', error);
  }

});

const submit = () => {
  form.post(`/register/creator/${props.inviteCodeUlid}`, {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};

Inertia.on('navigate', (event) => {
  requestAnimationFrame(() => {
    const topDiv = document.getElementById("topDiv");
    if (topDiv) {
      topDiv.scrollIntoView({behavior: 'smooth'});
    } else {
      window.scrollTo(0, 0);
    }
  });
})

</script>
