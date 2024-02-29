<template>

  <Head title="Creator Registration"/>
  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
<!--      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>-->
<!--      <PublicResponsiveNavigationMenu />-->
    </div>

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">

      <div class="flex flex-col mt-36 mb-16 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
        <div><ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/></div>
        <div class="uppercase">
          Creator Registration
        </div>
      </div>
      <main class="pb-8  hide-scrollbar">
        <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">


          <div class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-full md:w-3/4 max-w-96 text-gray-900 rounded">

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


              <div class="mt-4">
                <JetLabel for="address1" value="Address1" />
                <JetInput
                    id="address1"
                    v-model="form.address1"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="address1"
                />
              </div>
              <div v-if="form.errors.address1" v-text="form.errors.address1"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="address2" value="Address2" />
                <JetInput
                    id="address2"
                    v-model="form.address2"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="address2"
                />
              </div>
              <div v-if="form.errors.address2" v-text="form.errors.address2"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="city" value="City" />
                <JetInput
                    id="city"
                    v-model="form.city"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="city"
                />
              </div>
              <div v-if="form.errors.city" v-text="form.errors.city"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="province" value="Province" />
                <JetInput
                    id="name"
                    v-model="form.province"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="province"
                />
              </div>
              <div v-if="form.errors.province" v-text="form.errors.province"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="country" value="Country" />
                <JetInput
                    id="country"
                    v-model="form.country"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="country"
                />
              </div>
              <div v-if="form.errors.country" v-text="form.errors.country"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="postalCode" value="PostalCode" />
                <JetInput
                    id="postalCode"
                    v-model="form.postalCode"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="postalCode"
                />
              </div>
              <div v-if="form.errors.postalCode" v-text="form.errors.postalCode"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="phone" value="Phone" />
                <JetInput
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="phone"
                />
              </div>
              <div v-if="form.errors.phone" v-text="form.errors.phone"
                   class="text-xs text-red-600 mt-1"></div>


              <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <JetLabel for="terms">
                  <div class="flex items-center">
                    <JetCheckbox id="terms" v-model="form.terms" name="terms" required />
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

    </div>
  </div>
</template>

<script setup>
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

import Footer from '@/Components/Global/Layout/Footer.vue'

import { Link, useForm } from '@inertiajs/inertia-vue3'
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import AuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import { onMounted } from 'vue'


const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'creator.registration.show'

const props = defineProps({

})

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: true,
  invite_code: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};



onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
});
</script>
