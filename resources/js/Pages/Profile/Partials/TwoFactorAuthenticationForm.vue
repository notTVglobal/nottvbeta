<template>
  <JetActionSection>
    <template #title>
      Two Factor Authentication
    </template>

    <template #description>
      Add additional security to your account using two factor authentication.
    </template>

    <template #content>
      <h3 v-if="twoFactorEnabled && !confirming" class="text-lg font-medium text-gray-900">
        You have enabled two factor authentication.
      </h3>

      <h3 v-else-if="twoFactorEnabled && confirming" class="text-lg font-medium text-gray-900">
        Finish enabling two factor authentication.
      </h3>

      <h3 v-else class="text-lg font-medium text-gray-900">
        You have not enabled two factor authentication.
      </h3>

      <div class="mt-3 max-w-xl text-sm text-gray-600">
        <p>
          When two factor authentication is enabled, you will be prompted for a secure, random token during
          authentication. You may retrieve this token from your phone's Google Authenticator application.
        </p>
      </div>

      <div v-if="twoFactorEnabled">
        <div v-if="qrCode">
          <div class="mt-4 max-w-xl text-sm text-gray-600">
            <p v-if="confirming" class="font-semibold">
              To finish enabling two factor authentication, scan the following QR code using your phone's authenticator
              application or enter the setup key and provide the generated OTP code.
            </p>

            <p v-else>
              Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator
              application or enter the setup key.
            </p>
          </div>

          <div class="mt-4" v-html="qrCode"/>

          <div class="mt-4 max-w-xl text-sm text-gray-600" v-if="setupKey">
            <p class="font-semibold">
              Setup Key: <span v-html="setupKey"></span>
            </p>
          </div>

          <div v-if="confirming" class="mt-4">
            <JetLabel for="code" value="Code"/>

            <JetInput
                id="code"
                v-model="confirmationForm.code"
                type="text"
                name="code"
                class="block mt-1 w-1/2"
                inputmode="numeric"
                autofocus
                autocomplete="one-time-code"
                @keyup.enter="confirmTwoFactorAuthentication"
            />

            <JetInputError :message="confirmationForm.errors.code" class="mt-2"/>
          </div>
        </div>

        <div v-if="recoveryCodes.length > 0 && !confirming">
          <div class="mt-4 max-w-xl text-sm text-gray-600">
            <p class="font-semibold">
              Store these recovery codes in a secure password manager. They can be used to recover access to your
              account if your two factor authentication device is lost.
            </p>
          </div>

          <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
            <div v-for="code in recoveryCodes" :key="code">
              {{ code }}
            </div>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <div v-if="!twoFactorEnabled">
          <JetConfirmsPassword @confirmed="enableTwoFactorAuthentication">
            <JetButton type="button" :class="{ 'opacity-25': enabling }" :disabled="enabling">
              Enable
            </JetButton>
          </JetConfirmsPassword>
        </div>

        <div v-else>
          <JetConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
            <JetButton
                v-if="confirming"
                type="button"
                class="mr-3"
                :class="{ 'opacity-25': enabling }"
                :disabled="enabling"
            >
              Confirm
            </JetButton>
          </JetConfirmsPassword>

          <JetConfirmsPassword @confirmed="regenerateRecoveryCodes">
            <JetSecondaryButton
                v-if="recoveryCodes.length > 0 && !confirming"
                class="mr-3"
            >
              Regenerate Recovery Codes
            </JetSecondaryButton>
          </JetConfirmsPassword>

          <JetConfirmsPassword @confirmed="showRecoveryCodes">
            <JetSecondaryButton
                v-if="recoveryCodes.length === 0 && !confirming"
                class="mr-3 mb-2"
            >
              Show Recovery Codes
            </JetSecondaryButton>
          </JetConfirmsPassword>

          <JetConfirmsPassword @confirmed="disableTwoFactorAuthentication">
            <JetSecondaryButton
                v-if="confirming"
                :class="{ 'opacity-25': disabling }"
                :disabled="disabling"
            >
              Cancel
            </JetSecondaryButton>
          </JetConfirmsPassword>

          <JetConfirmsPassword @confirmed="disableTwoFactorAuthentication">
            <JetDangerButton
                v-if="!confirming"
                :class="{ 'opacity-25': disabling }"
                :disabled="disabling"
            >
              Disable
            </JetDangerButton>
          </JetConfirmsPassword>
        </div>
      </div>
    </template>
  </JetActionSection>
</template>
<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useForm, usePage } from '@inertiajs/vue3';
import JetActionSection from '@/Jetstream/ActionSection.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetConfirmsPassword from '@/Jetstream/ConfirmsPassword.vue';
import JetDangerButton from '@/Jetstream/DangerButton.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import axios from 'axios';

const props = defineProps({
  requiresConfirmation: Boolean,
});

const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
  code: '',
});

const twoFactorEnabled = computed(() => !enabling.value && usePage().props.auth.user?.two_factor_enabled);

watch(twoFactorEnabled, () => {
  if (!twoFactorEnabled.value) {
    confirmationForm.reset();
    confirmationForm.clearErrors();
  }
});

const enableTwoFactorAuthentication = () => {
  // console.log('Starting two-factor authentication enable process...');
  enabling.value = true;

  router.post('/user/two-factor-authentication', {}, {
    preserveScroll: true,
    onSuccess: () => {
      // console.log('Two-factor authentication enabled successfully.');
      return Promise.all([
        showQrCode(),
        showSetupKey(),
        showRecoveryCodes(),
      ]);
    },
    onFinish: () => {
      // console.log('Finished two-factor authentication enable process.');
      enabling.value = false;
      confirming.value = props.requiresConfirmation;
    },
    onError: (errors) => {
      console.error('Error enabling two-factor authentication:', errors);
      enabling.value = false;
    },
  });
};

const showQrCode = () => {
  // console.log('Fetching QR code...');
  return axios.get('/user/two-factor-qr-code').then(response => {
    qrCode.value = response.data.svg;
  }).catch(error => {
    console.error('Error fetching QR code:', error);
  });
};

const showSetupKey = () => {
  // console.log('Fetching setup key...');
  return axios.get('/user/two-factor-secret-key').then(response => {
    // console.log('Setup key fetched successfully.');
    setupKey.value = response.data.secretKey;
  }).catch(error => {
    console.error('Error fetching setup key:', error);
  });
};

const showRecoveryCodes = () => {
  // console.log('Fetching recovery codes...');
  return axios.get('/user/two-factor-recovery-codes').then(response => {
    // console.log('Recovery codes fetched successfully.');
    recoveryCodes.value = response.data;
  }).catch(error => {
    console.error('Error fetching recovery codes:', error);
  });
};

const confirmTwoFactorAuthentication = () => {
  // console.log('Confirming two-factor authentication...');
  confirmationForm.post('/user/confirmed-two-factor-authentication', {
    errorBag: 'confirmTwoFactorAuthentication',
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      // console.log('Two-factor authentication confirmed successfully.');
      confirming.value = false;
      qrCode.value = null;
      setupKey.value = null;
    },
    onError: (errors) => {
      console.error('Error confirming two-factor authentication:', errors);
    },
  });
};

const regenerateRecoveryCodes = () => {
  // console.log('Regenerating recovery codes...');
  axios.post('/user/two-factor-recovery-codes').then(() => {
    // console.log('Recovery codes regenerated successfully.');
    showRecoveryCodes();
  }).catch(error => {
    console.error('Error regenerating recovery codes:', error);
  });
};

const disableTwoFactorAuthentication = () => {
  // console.log('Disabling two-factor authentication...');
  disabling.value = true;

  router.delete('/user/two-factor-authentication', {
    preserveScroll: true,
    onSuccess: () => {
      // console.log('Two-factor authentication disabled successfully.');
      disabling.value = false;
      confirming.value = false;
    },
    onError: (errors) => {
      console.error('Error disabling two-factor authentication:', errors);
      disabling.value = false;
    },
  });
};
</script>