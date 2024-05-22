<template>
    <Head title="Two-factor Confirmation" />

  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
      <PublicResponsiveNavigationMenu/>
    </div>

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">

      <div
          class="flex flex-col mt-36 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
        <div>
          <ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/>
        </div>
        <div class="mt-8 uppercase">Two-Factor Confirmation</div>
      </div>

      <main class="pb-8  hide-scrollbar">
        <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

          <div
              class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-full md:w-3/4 max-w-96 text-gray-900 rounded">


<!--          <JetAuthenticationCard>-->
<!--        <template #logo>-->
<!--            <JetAuthenticationCardLogo />-->
<!--        </template>-->

        <div class="mb-4 text-sm text-gray-600">
            <template v-if="! recovery">
                Please confirm access to your account by entering the authentication code provided by your authenticator application.
            </template>

            <template v-else>
                Please confirm access to your account by entering one of your emergency recovery codes.
            </template>
        </div>

        <JetValidationErrors class="mb-4" />

        <form @submit.prevent="submit">
            <div v-if="! recovery">
                <JetLabel for="code" value="Code" />
                <JetInput
                    id="code"
                    ref="codeInput"
                    v-model="form.code"
                    type="text"
                    inputmode="numeric"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="one-time-code"
                />
            </div>

            <div v-else>
                <JetLabel for="recovery_code" value="Recovery Code" />
                <JetInput
                    id="recovery_code"
                    ref="recoveryCodeInput"
                    v-model="form.recovery_code"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="one-time-code"
                />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer" @click.prevent="toggleRecovery">
                    <template v-if="! recovery">
                        Use a recovery code
                    </template>

                    <template v-else>
                        Use an authentication code
                    </template>
                </button>

                <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </JetButton>
            </div>
        </form>
<!--    </JetAuthenticationCard>-->

    </div>

  </div>
  </main>

      <Footer/>

  </div>
  </div>
</template>
<script setup>
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { nextTick, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'two-factor-confirmation'

const recovery = ref(false);

const form = useForm({
  code: '',
  recovery_code: '',
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
  recovery.value ^= true;

  await nextTick();

  if (recovery.value) {
    recoveryCodeInput.value.focus();
    form.code = '';
  } else {
    codeInput.value.focus();
    form.recovery_code = '';
  }
};

const submit = () => {
  form.post(route('two-factor.login'), {
  });
  appSettingStore.pageReload = true;
};
</script>
