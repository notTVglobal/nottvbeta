<template>

  <Head title="Login"/>
  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
    </div>

      <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">

        <div class="mt-36 text-gray-50 text-center text-3xl font-semibold tracking-widest">LOGIN</div>

        <main class="pb-8  hide-scrollbar">
          <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

            <div class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-3/4 max-w-96 text-gray-900 rounded">


              <div class="">
                <JetValidationErrors class="mb-4" />

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                  {{ status }}
                </div>

                <form @submit.prevent="submit">
                  <div class="pb-6 flex flex-col">
                    <div>Please log in to watch notTV and chat.</div>
                    <div>Need to <Link
                        :href="`/public/register`"
                        class="text-blue-800 hover:text-blue-500">
                      register
                    </Link>for an account?</div>
                  </div>
                  <div>
                    <JetLabel for="email" value="Email" />
                    <JetInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full text-black"
                        required
                        autofocus
                    />
                  </div>

                  <div class="mt-4">
                    <JetLabel for="password" value="Password" />
                    <JetInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full text-black"
                        required
                        autocomplete="current-password"
                    />
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


const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'public.login'

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
