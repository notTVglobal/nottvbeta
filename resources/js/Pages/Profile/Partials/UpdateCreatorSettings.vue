<template>
    <JetFormSection @submitted="updateCreatorSettings">
        <template #title>
            Creator Profile Settings<br><span class="text-green-500 tracking-wide">(creators only)</span>
        </template>

        <template #description>
          Here you can customize your creator profile settings. Adjust visibility preferences, manage notification settings, and update other personal options to tailor how you interact with the platform and how others see your profile.
        </template>


        <template #form>


            <!-- Address -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="profile_is_public" value="Profile Visibility:" />
              Private <input type="checkbox" class="toggle toggle-success" /> Public
                <JetInputError :message="form.errors.profile_is_public" class="mt-2" />
            </div>



        </template>

        <template #actions>
            <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </JetActionMessage>

            <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </JetButton>
        </template>
    </JetFormSection>
</template>
<script setup>
import { onMounted, reactive, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';

const creatorSettings = ref(null)

const fetchCreatorSettings = async () => {
  axios.post('/user/creator/get-settings/')
      .then(response => {
        creatorSettings.value = response.data;
      })
      .catch(error => {
        console.error("Failed to fetch creator settings:", error);
      });
}


// Fetch settings immediately when the component is created/mounted
onMounted(fetchCreatorSettings);

// Watch for changes in creatorSettings and update form data accordingly
watch(creatorSettings, (newSettings) => {
  if (newSettings) {
    form.profile_is_public = newSettings.profile_is_public;
  }
}, { immediate: true });

// const form = reactive({
//   _method: 'PUT',
//   profile_is_public: null, // default value, will be updated by watch effect
// });


const form = useForm({
  _method: 'PUT',
  profile_is_public: null,
});


const updateCreatorSettings = () => {
  if (creatorSettings.value) {
    form.patch(route('creators.updateSettings'), {
      onError: (errors) => console.error("Update errors:", errors),
      onSuccess: () => console.log("Settings updated successfully!"),
      errorBag: 'updateCreatorSettings',
      preserveScroll: true,
    });
  } else {
    console.error("Cannot update settings because they have not been fetched yet.");
  }
};

// let submit = () => {
//     form.post(route('users.updateUser', props.user.id));
// };

// const sendEmailVerification = () => {
//     verificationLinkSent.value = true;
// };

</script>
