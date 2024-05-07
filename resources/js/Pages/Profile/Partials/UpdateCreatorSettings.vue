<template>
  <JetFormSection @submitted="updateCreatorSettings">
    <template #title>
      Creator Profile Settings<br><span class="text-green-500 tracking-wide">(creators only)</span>
    </template>

    <template #description>
      Here you can customize your creator profile settings. Adjust visibility preferences, manage notification settings, and update other personal options to tailor how you interact with the platform and how others see your profile.
    </template>

    <template #form>
      <!-- Profile Visibility -->
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="profile_is_public" value="Profile Visibility:"/>
        <!-- Update the v-model to bind with the form object -->
        Private <input type="checkbox" id="profile_is_public" class="toggle toggle-success"
               v-model="profileIsPublic" ref="checkboxRef" @click="handleCheckboxClick"/> Public
        <!-- Display error message if there is an error related to profile_is_public -->
        <JetInputError :message="form.errors['settings.profile_is_public']" class="mt-2"/>
      </div>
    </template>

    <template #actions>
      <JetActionMessage :on="savedMessage" class="mr-3">
        <span class="text-green-500">Saved.</span>
      </JetActionMessage>

      <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Save
      </JetButton>
    </template>
  </JetFormSection>
</template>
<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-vue3'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import JetButton from '@/Jetstream/Button.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetActionMessage from '@/Jetstream/ActionMessage.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

const props = usePage().props

// Initialize the form with useForm, include initial settings possibly coming from props
const form = useForm({
  settings: {
    profile_is_public: settings?.profile_is_public || false, // default to false if undefined
  },
})

const checkboxRef = ref(null);

const handleCheckboxClick = () => {
  // You may perform other actions here if needed before blurring the checkbox
  if (checkboxRef.value) {
    checkboxRef.value.blur();
  }
};

const settings = reactive({
  profile_is_public: null, // Default value until fetched from server
})

const savedMessage = ref(false)

const profileIsPublic = computed({
  get() {
    // You can add any additional logic here if necessary
    return settings.profile_is_public;
  },
  set(value) {
    settings.profile_is_public = value;
    // Any additional logic when setting the value can also be included here
    // For example, you might want to debounce an update request to the server
  }
})

const fetchCreatorSettings = async () => {
  const userId = props.value.user.id // Assuming props are passed directly without 'value'

  try {
    console.log(`Fetching settings for user ID: ${userId}`) // Debug log
    const response = await axios.get(`/user/creator/get-settings/${userId}`)

    // Update the entire settings object
    for (const key in response.data) {
      if (response.data.hasOwnProperty(key)) {
        settings[key] = response.data[key];
      }
    }
    // If settings are fetched later and need to sync with useForm
    form.settings.profile_is_public = settings.profile_is_public;
    console.log('Settings fetched successfully:', settings.profile_is_public);

  } catch (error) {
    console.error('Failed to fetch creator settings:', error)
    // Handle loading state or display error message in UI as needed
  }
}

// After fetching settings and updating the `settings` object
watch(() => settings.profile_is_public, (newValue) => {
  form.settings.profile_is_public = newValue;
}, { immediate: true });

// Fetch settings immediately when the component is created/mounted
onMounted(fetchCreatorSettings)



const updateCreatorSettings = () => {
  console.log('Submitting:', form.settings.profile_is_public);  // Log what is actually being submitted
  // Check if the profile_is_public property is undefined
  if (settings.profile_is_public === undefined) {
    console.error('Cannot update settings because they have not been fetched yet.');
    return;
  }

  const userId = props.value.user.id  // Ensure userId is available
  form.patch(`/user/creator/update-settings/${userId}`, {
    onSuccess: (response) => {
      console.log('Settings updated successfully!', response.message)
      savedMessage.value = true; // Update local data to show message
      setTimeout(() => {
        savedMessage.value = false  // Optionally clear the message after a few seconds
      }, 3000)
    },
    onFinish: () => {
      form.reset('settings')  // Reset the settings field after successful update
      console.log('Form has been reset.')
    },
    onError: (errors) => console.error("Update errors:", errors),
    preserveScroll: true,
    errorBag: 'updateCreatorSettings'
  })
}

</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>