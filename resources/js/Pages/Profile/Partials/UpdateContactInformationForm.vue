<template>
  <JetFormSection @submitted="updateProfileInformation">
    <template #title>
      Contact Information
    </template>

    <template #description>
      Update your account's contact information.
    </template>


    <template #form>

      <!--            <form @submit.prevent="submit" class="mt-6">-->

      <!--            <div class="col-span-6 sm:col-span-4 hidden">-->
      <!--                <JetLabel for="name" value="Name" />-->
      <!--                <JetInput-->
      <!--                    id="name"-->
      <!--                    v-model="form.name"-->
      <!--                    type="text"-->
      <!--                    class="mt-1 block w-full"-->
      <!--                    autocomplete="name"-->
      <!--                />-->
      <!--                <JetInputError :message="form.errors.name" class="mt-2" />-->
      <!--            </div>-->

      <!--            <div class="col-span-6 sm:col-span-4 hidden">-->
      <!--                <JetLabel for="email" value="Email" />-->
      <!--                <JetInput-->
      <!--                    id="email"-->
      <!--                    v-model="form.email"-->
      <!--                    type="email"-->
      <!--                    class="mt-1 block w-full"-->
      <!--                    autocomplete="email"-->
      <!--                />-->
      <!--                <JetInputError :message="form.errors.email" class="mt-2" />-->
      <!--            </div>-->

      <!-- Address -->
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="address1" value="Address 1"/>
        <JetInput
            id="address1"
            v-model="form.address1"
            type="text"
            class="mt-1 block w-full"
            autocomplete="address1"
        />
        <JetInputError :message="form.errors.address1" class="mt-2"/>
      </div>
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="address2" value="Address 2"/>
        <JetInput
            id="address2"
            v-model="form.address2"
            type="text"
            class="mt-1 block w-full"
            autocomplete="address2"
        />
        <JetInputError :message="form.errors.address2" class="mt-2"/>
      </div>
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="city" value="City"/>
        <JetInput
            id="city"
            v-model="form.city"
            type="text"
            class="mt-1 block w-full"
            autocomplete="city"
        />
        <JetInputError :message="form.errors.city" class="mt-2"/>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="province" value="Province/State"/>
        <JetInput
            id="province"
            v-model="form.province"
            type="text"
            class="mt-1 block w-full"
            autocomplete="province"
        />
        <JetInputError :message="form.errors.province" class="mt-2"/>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="country" value="Country"/>
        <select id="country" v-model="form.country" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          <option v-for="country in countries" :key="country.id" :value="country.name">
            {{ country.name }}
          </option>
        </select>
        <JetInputError :message="form.errors.country" class="mt-2"/>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="postal_code" value="Postal Code/Zip"/>
        <JetInput
            id="postalCode"
            v-model="form.postalCode"
            type="text"
            class="mt-1 block w-full"
            autocomplete="postalCode"
        />
        <JetInputError :message="form.errors.postalCode" class="mt-2"/>
      </div>
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="phone" value="Phone Number"/>
        <JetInput
            id="phone"
            v-model="form.phone"
            type="text"
            class="mt-1 block w-full"
            autocomplete="phone"
        />
        <JetInputError :message="form.errors.phone" class="mt-2"/>
      </div>

      <!--                <div class="flex justify-between mb-6">-->
      <!--                    <button-->
      <!--                        type="submit"-->
      <!--                        class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"-->
      <!--                        :class="{ 'opacity-25': form.processing }"-->
      <!--                        :disabled="form.processing"-->
      <!--                    >-->
      <!--                        Save-->
      <!--                    </button>-->
      <!--                </div>-->

      <!--            <JetActionMessage :on="form.recentlySuccessful" class="mr-3">-->
      <!--                Saved.-->
      <!--            </JetActionMessage>-->
      <!--            </form>-->


    </template>

    <template #actions>
      <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
        <span class="text-green-500">Saved.</span>
      </JetActionMessage>

      <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Save
      </JetButton>
    </template>
  </JetFormSection>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Link, useForm } from '@inertiajs/vue3'
import JetButton from '@/Jetstream/Button.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetActionMessage from '@/Jetstream/ActionMessage.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

const props = defineProps({
  user: Object,
})

const form = useForm({
  _method: 'PATCH',
  address1: '',
  address2: '',
  city: '',
  province: '',
  country: '',
  postalCode: '',
  phone: '',
})

const countries = ref([])
const error = ref(null)

onMounted(async () => {
  try {
    // Fetch countries
    const countryResponse = await fetch('/api/news-countries-simple-list');
    if (!countryResponse.ok) {
      console.log('Failed to fetch countries');
    }
    countries.value = await countryResponse.json();

    // Fetch user contact information
    const userResponse = await fetch('/user/contact');
    if (!userResponse.ok) {
      console.log('Failed to fetch user contact information');
    }
    const userData = await userResponse.json();

    // Populate form with user data
    form.address1 = userData.address1;
    form.address2 = userData.address2;
    form.city = userData.city;
    form.province = userData.province;
    form.country = userData.country;
    form.postalCode = userData.postalCode;
    form.phone = userData.phone;
  } catch (err) {
    error.value = err.message;
  }
});

const updateProfileInformation = () => {
  form.patch(route('users.updateContact'), {
    errorBag: 'updateProfileInformation',
    preserveScroll: true,
    onSuccess: () => {
      console.log('Form data successfully sent');
    },
    onError: (errors) => {
      console.error('Form submission errors:', errors);
    },
  });
};

// let form = useForm({
//     id: props.userEdit.id,
//     name: props.userEdit.name,
//     email: props.userEdit.email,
//     role_id: props.userEdit.role_id,
//     address1: props.userEdit.address1,
//     address2: props.userEdit.address2,
//     city: props.userEdit.city,
//     province: props.userEdit.province,
//     country: props.userEdit.country,
//     postalCode: props.userEdit.postalCode,
//     phone: props.userEdit.phone,
// });

// const verificationLinkSent = ref(null);

// const updateContactInformation = () => {
//     form.post(route('user-profile-information.update'), {
//         errorBag: 'updateContactInformation',
//         preserveScroll: true,
//     });
// };


// let submit = () => {
//     form.post(route('users.updateUser', props.user.id));
// };

// const sendEmailVerification = () => {
//     verificationLinkSent.value = true;
// };

</script>