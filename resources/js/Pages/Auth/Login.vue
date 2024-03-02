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
          <div class="mt-8 uppercase">Login</div>
        </div>
        <main class="pb-8  hide-scrollbar">
          <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

            <div class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-full md:w-3/4 max-w-96 text-gray-900 rounded">


              <div class="">
                <JetValidationErrors class="mb-4" />

<!--                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">-->
<!--                  {{ status }}-->
<!--                </div>-->

                <form @submit.prevent="submit">
                  <div class="pb-6 flex flex-col">
                    <div>Please log in to watch notTV and chat.</div>
                    <div>Need to <Link
                        :href="`/register`"
                        class="text-blue-800 hover:text-blue-500">
                      register
                    </Link>for an account?</div>
                  </div>
                  <div>
                    <label for="email" class="input input-bordered input-primary flex items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                           class="w-4 h-4 opacity-70">
                        <path
                            d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"/>
                        <path
                            d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"/>
                      </svg>
                      <input id="email"
                             v-model="form.email" type="email" class="grow border-none" placeholder="Email" required autofocus/>
                    </label>
                  </div>

                  <div class="mt-4">
                    <label for="password" class="input input-bordered input-primary flex items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                           class="w-4 h-4 opacity-70">
                        <path fill-rule="evenodd"
                              d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                              clip-rule="evenodd"/>
                      </svg>
                      <input id="password"
                             v-model="form.password" type="password" class="grow border-none" value="" required/>
                    </label>
                  </div>

                  <div class="block mt-4">
                    <label class="flex items-center">
                      <JetCheckbox v-model:checked="form.remember" name="remember" />
                      <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                  </div>

                  <div class="flex items-center justify-end mt-4">
                    <Link :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                      Forgot your password?
                    </Link>

                    <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                      Log in
                    </JetButton>
                  </div>
                </form>

              </div>



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
import Footer from '@/Components/Global/Layout/Footer.vue'
import {Link, useForm} from '@inertiajs/inertia-vue3';
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
appSettingStore.currentPage = 'login'

const props = defineProps({
  newsPeople: Array,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(route('login'), {
    onFinish: () => form.reset('password'),
  });
  appSettingStore.pageReload = true
};

</script>
