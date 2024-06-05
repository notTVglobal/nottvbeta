<template>
  <JetFormSection @submitted="updateNewsPersonSettings">
    <template #title>
      News Profile Settings<br><span class="text-yellow-500 tracking-wide">(news people only)</span>
    </template>

    <template #description>
      Here you can customize your news profile settings. Adjust visibility preferences, manage notification settings,
      and update other personal options to tailor how you interact with the platform and how others see your profile.
    </template>

    <template #form>
      <!-- List Profile Roles -->
      <div class="col-span-6 sm:col-span-4">
        <h3 class="text-lg font-semibold mb-2">Roles</h3>
        <ul class="list-disc list-inside">
          <li v-for="role in roles" :key="role.id">
            {{ role.name }}
          </li>
        </ul>
      </div>

      <!-- Profile Image Section -->
      <div class="col-span-6 sm:col-span-4">
        <button @click.prevent="toggleSection('profileImage')" class="toggle-button">
          Profile Image
        </button>
        <div v-show="sections.profileImage" class="transition-all duration-500 ease-in-out mt-2">
          <SingleImageWithModal v-if="image"
                                :image="image"
                                :key="image.id"
                                :alt="`News Profile Image`"
                                :class="`w-20 h-20 rounded-lg object-cover mb-6`"/>
          <JetLabel for="news_profile_image" value="Upload News Person Profile Picture (optional):"/>
          <ImageUpload :modelType="'newsPerson'"
                       :modelId="newsPersonId"
                       :name="''"
                       :maxSize="'30MB'"
                       :fileTypes="'image/jpg, image/jpeg, image/png'"
                       @imageUploaded="updateImage"
          />
        </div>
      </div>

      <!-- Biography Section -->
      <div class="col-span-6 sm:col-span-4">
        <button @click.prevent="toggleSection('biography')" class="toggle-button">
          Biography
        </button>
        <div v-show="sections.biography" class="transition-all duration-500 ease-in-out mt-2">
          <JetLabel for="biography" value="Biography"/>
          <TabbableTextarea id="biography" class="mt-1 block w-full" v-model="form.biography"/>
          <JetInputError :message="form.errors.biography" class="mt-2"/>
        </div>
      </div>

      <!-- Contact Info Section -->
      <div class="col-span-6 sm:col-span-4">
        <button @click.prevent="toggleSection('contactInfo')" class="toggle-button">
          Contact Info
        </button>
        <div v-show="sections.contactInfo" class="transition-all duration-500 ease-in-out mt-2">
          <JetLabel for="contact_info" value="Contact Info"/>
          <TabbableTextarea id="contact_info" class="mt-1 block w-full" v-model="form.contact_info"/>
          <JetInputError :message="form.errors.contact_info" class="mt-2"/>
        </div>
      </div>

      <!-- Other Details Section -->
      <div class="col-span-6 sm:col-span-4">
        <button @click.prevent="toggleSection('otherDetails')" class="toggle-button">
          Other Details
        </button>
        <div v-show="sections.otherDetails" class="transition-all duration-500 ease-in-out mt-2">
          <JetLabel for="other_details" value="Other Details"/>
          <TabbableTextarea id="other_details" class="mt-1 block w-full" v-model="form.other_details"/>
          <JetInputError :message="form.errors.other_details" class="mt-2"/>
        </div>
      </div>

      <!-- Social Media Section -->
      <div class="col-span-6 sm:col-span-4">
        <button @click.prevent="toggleSection('socialMedia')" class="toggle-button">
          Social Media
        </button>
        <div v-show="sections.socialMedia" class="transition-all duration-500 ease-in-out mt-2">
          <JetLabel for="facebook" value="Facebook"/>
          <JetInput id="facebook" type="text" class="mt-1 block w-full" v-model="form.social_media.facebook"/>
          <JetInputError :message="form.errors.social_media?.facebook" class="mt-2"/>

          <JetLabel for="twitter" value="X (Twitter)" class="mt-4"/>
          <JetInput id="twitter" type="text" class="mt-1 block w-full" v-model="form.social_media.twitter"/>
          <JetInputError :message="form.errors.social_media?.twitter" class="mt-2"/>

          <JetLabel for="instagram" value="Instagram" class="mt-4"/>
          <JetInput id="instagram" type="text" class="mt-1 block w-full" v-model="form.social_media.instagram"/>
          <JetInputError :message="form.errors.social_media?.instagram" class="mt-2"/>

          <JetLabel for="linkedin" value="LinkedIn" class="mt-4"/>
          <JetInput id="linkedin" type="text" class="mt-1 block w-full" v-model="form.social_media.linkedin"/>
          <JetInputError :message="form.errors.social_media?.linkedin" class="mt-2"/>

          <JetLabel for="snapchat" value="Snapchat" class="mt-4"/>
          <JetInput id="snapchat" type="text" class="mt-1 block w-full" v-model="form.social_media.snapchat"/>
          <JetInputError :message="form.errors.social_media?.snapchat" class="mt-2"/>

          <JetLabel for="discord" value="Discord" class="mt-4"/>
          <JetInput id="discord" type="text" class="mt-1 block w-full" v-model="form.social_media.discord"/>
          <JetInputError :message="form.errors.social_media?.discord" class="mt-2"/>

          <JetLabel for="substack" value="Substack" class="mt-4"/>
          <JetInput id="substack" type="text" class="mt-1 block w-full" v-model="form.social_media.substack"/>
          <JetInputError :message="form.errors.social_media?.substack" class="mt-2"/>
        </div>
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
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { Link, useForm } from '@inertiajs/vue3'
import JetButton from '@/Jetstream/Button.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetActionMessage from '@/Jetstream/ActionMessage.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import ImageUpload from '@/Components/Global/Uploaders/ImageUpload.vue'
import TabbableTextarea from '@/Components/Global/TextEditor/TabbableTextarea.vue'
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'

const props = usePage().props
const savedMessage = ref(false)
const newsPersonId = Number(props.user.newsPersonId)
const newsPersonSlug = props.user.newsPersonSlug

const socialMedia = reactive({
  profile_is_public: null, // Default value until fetched from server
})

let roles = reactive([])

// Use reactive for the image object
let image = ref()

// Initialize the form with useForm, include initial settings possibly coming from props
const form = useForm({
  biography: '',
  contact_info: '',
  other_details: '',
  social_media: {
    facebook: '',
    twitter: '',
    instagram: '',
    linkedin: '',
    snapchat: '',
    discord: '',
    substack: '',
  },
})

const fetchNewsPersonSettings = async () => {
  try {
    console.log(`Fetching settings for News Person ID: ${newsPersonId}`) // Debug log
    const response = await axios.get(`/api/news/news-person-fetch-settings/${newsPersonSlug}`)
    const data = response.data
    image = data.image
    roles.splice(0, roles.length, ...data.roles || [])
    form.biography = data.biography
    form.contact_info = data.contact_info
    form.other_details = data.other_details
    form.social_media.facebook = data.social_media.facebook
    form.social_media.twitter = data.social_media.twitter
    form.social_media.instagram = data.social_media.instagram
    form.social_media.linkedin = data.social_media.linkedin
    form.social_media.snapchat = data.social_media.snapchat
    form.social_media.discord = data.social_media.discord
    form.social_media.substack = data.social_media.substack
    console.log('News Person Settings fetched successfully.')
  } catch (error) {
    console.error('Failed to fetch News Person settings:', error)
    // Handle loading state or display error message in UI as needed
  }
}

// Fetch settings immediately when the component is created/mounted
onMounted(fetchNewsPersonSettings)

const updateNewsPersonSettings = () => {
  form.patch(route('newsPerson.update', newsPersonSlug), {
    onSuccess: (response) => {
      console.log('Settings updated successfully!', response.message)
      savedMessage.value = true // Update local data to show message
      setTimeout(() => {
        savedMessage.value = false  // Optionally clear the message after a few seconds
      }, 3000)
    },
    onFinish: () => {
      // form.reset('settings')  // Reset the settings field after successful update
      // console.log('Form has been reset.')
    },
    onError: (errors) => console.error("Update errors:", errors),
    preserveScroll: true,
    errorBag: 'updateNewsPersonSettings'
  })
}

// Watch for changes to the image object and log changes
watch(() => image, (newImage) => {
  console.log('Image updated:', newImage)
}, { deep: true })

// Function to handle image updates
const updateImage = (newImage) => {
  console.log('Image uploaded successfully:', newImage)
  Object.assign(image, newImage)  // Use Object.assign to ensure reactivity
}

const removeImage = async () => {
  try {
    await axios.post('/api/remove-image', {
      modelId: newsPersonId,
      modelType: 'newsPerson',
      imageId: image.id,
    })
    image = null
    notificationStore.setToastNotification('Image removed.', 'info')
  } catch (error) {
    console.error('Error removing image:', error)
  }
}

// watch(() => image, (newValue) => {
//   image = newValue;
// }, { immediate: true });

// Collapsible sections state
const sections = reactive({
  profileImage: false,
  biography: false,
  contactInfo: false,
  otherDetails: false,
  socialMedia: false,
})

const toggleSection = (section) => {
  const currentState = sections[section]
  Object.keys(sections).forEach(key => {
    sections[key] = false
  })
  sections[section] = !currentState
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */
{
  opacity: 0;
}

.toggle-button {
  background-color: #4a90e2;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  transition: background-color 0.3s ease;
}

.toggle-button:hover {
  background-color: #357ab8;
}
</style>
