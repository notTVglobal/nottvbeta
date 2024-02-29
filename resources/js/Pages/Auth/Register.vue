<template>

  <Head title="Login"/>
  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
      <PublicResponsiveNavigationMenu />
    </div>

      <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">



        <div class="flex flex-col mt-36 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
          <div><ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/></div>
          <div class="uppercase">Start Your Experience</div>
        </div>
        <main class="pb-8 hide-scrollbar">
          <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

            <div class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-full md:w-3/4 max-w-96 text-gray-900 rounded">

              <JetValidationErrors class="mb-4" />

              <div class="my-4 pb-4 text-center">
                Create Your Account to Start Exploring notTV!
              </div>

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
                  <JetLabel for="invite_code" value="Invite Code" />
                  <JetInput
                      id="invite_code"
                      v-model="form.invite_code"
                      type="password"
                      class="mt-1 block w-full"
                      required
                  />
                </div>

                <div v-if="form.errors.invite_code" v-text="form.errors.invite_code"
                     class="text-xs text-red-600 mt-1"></div>

                <!--            Jetstream/Fortify Multi-Auth: Roles, Permissions and Guards-->
                <!--            https://www.youtube.com/watch?v=NiQSNjWKLfU-->

                <!--            <div class="mt-4">-->
                <!--                <JetLabel  for="role_id" value="Register as" />-->
                <!--                <select name="role_id" v-model="form.role_id" class="mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">-->
                <!--                    <option value="2" selected>Viewer</option>-->
                <!--                    <option value="3">Creator</option>-->
                <!--                </select>-->
                <!--            </div>-->

                <!--            <div class="mt-4" v-if="form.role_id == 3">-->
                <!--                <JetLabel for="user_phone" value="Phone Number" />-->
                <!--                <JetInput-->
                <!--                    id="user_phone"-->
                <!--                    v-model="form.user_phone"-->
                <!--                    type="phone"-->
                <!--                    class="mt-1 block w-full"-->
                <!--                    required-->
                <!--                    autocomplete="new-password"-->
                <!--                />-->
                <!--            </div>-->


                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                  <JetLabel for="terms">
                    <div class="flex items-center">
                      <JetCheckbox id="terms" v-model="form.terms" name="terms" required />
                      <div class="ml-2">
                        I agree to the <a :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a> and <a :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                      </div>
                    </div>
                  </JetLabel>
                  <div v-if="form.errors.terms" v-text="form.errors.terms"
                       class="text-xs text-red-600 mt-1"></div>
                </div>


                <div class="flex items-center justify-end mt-4">
                  <Link :href="`/login`" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Already registered?
                  </Link>

                  <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                  </JetButton>
                </div>

                <div class="modal-footer">
                  <div class="mt-4 pt-4 ml-4 border-t border-t-gray-200 text-center font-semibold">
                    For a chance to get an invite code<br> <a
                      href="https://not.tv/subscribe" target="_blank" class="text-blue-600 hover:text-blue-400 hover:cursor-pointer">subscribe
                    to our newsletter</a>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </main>


        <Footer />

      </div>
    </div>
</template>

<script setup>
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Button from '@/Jetstream/Button.vue'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import Login from '@/Pages/Auth/Login'
import { Inertia } from '@inertiajs/inertia'
import {Link, useForm} from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import { onMounted } from 'vue'


const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'register'

const props = defineProps({
  newsPeople: Array,
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

// const submit = () => {
//   form.transform(data => ({
//     ...data,
//     remember: form.remember ? 'on' : '',
//   })).post(route('login'), {
//     onFinish: () => form.reset('password'),
//   });
//   // Inertia.reload()
// };
</script>
