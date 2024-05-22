<template>

  <Head :title="`Edit Show: ${props.show.name}`"/>

  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 px-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <ShowEditHeader :show="show" :team="team" :form="form" @submit="submit"/>

      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mb-10">

            <CreateShowErrorsContainer :errors="form.errors"/>

              <!-- Begin grid 2-col -->
              <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">

                <!--Left Column-->
                <CreateShowSetImage :image="image" :show="show" @reloadImage="reloadImageHandler"/>


                <!--Right Column-->
                <div>
                  <form @submit.prevent="submit">


                    <CreateShowSetShowName :errors="form.errors"/>


                    <CreateShowSetEpisodePlayOrder :errors="form.errors"/>


                    <CreateShowSetShowStatus :statuses="statuses" :errors="form.errors"/>


                    <CreateShowSetDescription :description="show.description" :errors="form.errors"/>


                    <CreateShowSetShowNotes :notes="show.notes" :errors="form.errors"/>



                    <CreateShowSelectShowRunner :defaultShowRunnerId="show?.showRunner?.creator_id"
                                                :teamMembers="teamMembers"
                                                @selectedShowRunnerCreatorId="selectedShowRunnerCreatorIdHandler"
                                                :errors="form.errors"/>



                    <SocialMediaLinksStoreUpdateForForm v-model:form="form"/>


                    <CreateShowSetCategories :errors="form.errors"
                                             :categories="categories" />



                    <div class="flex justify-end mb-6">
                      <JetValidationErrors class="mr-4"/>
                      <button
                          type="submit"
                          class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded-lg py-2 px-4"
                          :disabled="form.processing"
                      >
                        Save
                      </button>
                    </div>
                  </form>

                </div>
                <!-- End Right Column -->
              </div>
              <!-- End grid 2-col -->

              <div class="px-4 py-2">
                <span class="text-xs uppercase font-semibold">Show ID: </span>
                <span class="text-xs">{{ show.ulid }}</span></div>
            </div>
          </div>
        </div>

      </div>


    </div>
  </div>

</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useTeamStore } from '@/Stores/TeamStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import ShowEditHeader from '@/Components/Pages/Shows/Layout/EditShowHeader'
import Message from '@/Components/Global/Modals/Messages'
import { computed, onMounted, ref, watch } from 'vue'
import { useNotificationStore } from '@/Stores/NotificationStore'
import SocialMediaLinksStoreUpdateForForm from '@/Components/Global/SocialMedia/SocialMediaLinksStoreUpdateForForm.vue'
import CreateShowSelectShowRunner from '@/Components/Pages/Shows/Elements/CreateShowSelectShowRunner.vue'
import CreateShowSetCategories from '@/Components/Pages/Shows/Elements/CreateShowSetCategories.vue'
import CreateShowSetShowName from '@/Components/Pages/Shows/Elements/CreateShowSetShowName.vue'
import CreateShowSetImage from '@/Components/Pages/Shows/Elements/CreateShowSetImage.vue'
import CreateShowErrorsContainer from '@/Components/Pages/Shows/Elements/CreateShowErrorsContainer.vue'
import CreateShowSetEpisodePlayOrder from '@/Components/Pages/Shows/Elements/CreateShowSetEpisodePlayOrder.vue'
import CreateShowSetShowStatus from '@/Components/Pages/Shows/Elements/CreateShowSetShowStatus.vue'
import CreateShowSetDescription from '@/Components/Pages/Shows/Elements/CreateShowSetDescription.vue'
import CreateShowSetShowNotes from '@/Components/Pages/Shows/Elements/CreateShowSetShowNotes.vue'

usePageSetup('shows/slug/edit')

const appSettingStore = useAppSettingStore()
const showStore = useShowStore()
const teamStore = useTeamStore()
const notificationStore = useNotificationStore()

let props = defineProps({
  user: Object,
  show: Object,
  team: Object,
  image: Object,
  teamMembers: Array,
  categories: Object,
  statuses: Object,
})

// Reactive property for the selected show_runner ID
const selectedShowRunnerCreatorId = ref(props.defaultShowRunnerId);
const selectedShowRunnerCreatorIdHandler = (id) => {
  selectedShowRunnerCreatorId.value = id;
};

let selectedCategoryId = ref(props?.show?.category?.id)
let selectedSubCategoryId = ref(props?.show?.subCategory?.id)



// const subCategories = computed(() => {
//   const category = props.categories.find(cat => cat.id === selectedCategoryId.value)
//   return category ? category.sub_categories : []
// })

// Watchers to update the store based on category and subcategory selections
// watch(selectedCategoryId, () => {
//   showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
// }, {immediate: true})
//
// watch(selectedSubCategoryId, () => {
//   showStore.updateSubCategoryDescription(selectedSubCategoryId.value)
// })

onMounted(() => {
  document.getElementById('topDiv').scrollIntoView({behavior: 'smooth'})
  showStore.name = props.show.name
  showStore.description = props.show.description
  showStore.notes = props.show.notes
  showStore.categories = props.categories
  showStore.episode_play_order = props.show.episode_play_order
  showStore.show_status_id = props.show.show_status_id
  showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
  if (!props.show.showRunner) {
    notificationStore.setGeneralServiceNotification('Please set the show runner.', 'You must set a show runner before you can edit the show.')
  }

})

// const chooseCategory = () => {
//   // Update the selected category ID based on the new selection
//   // Vue automatically updates selectedCategoryId due to v-model binding
//   // So, there is no need to manually set it here
//
//   // Call the store method to update descriptions and subcategories
//   showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
// }

// const chooseSubCategory = () => {
//   // Update the store state based on the new subcategory selection
//   showStore.updateSubCategoryDescription(selectedSubCategoryId.value)
// }

// const description = ref(props.show.description)

// const handleContentUpdate = (html) => {
//   description.value = html
// }


let form = useForm({
  name: props.show.name,
  description: props.show.description,
  show_status_id: props.show.show_status_id,
  category: props?.show?.category,
  sub_category: props?.show?.subCategory,
  www_url: props?.show.socialMediaLinks?.www_url,
  instagram_name: props?.show.socialMediaLinks?.instagram_name,
  telegram_url: props?.show?.socialMediaLinks?.telegram_url,
  twitter_handle: props?.show?.socialMediaLinks?.twitter_handle,
  notes: props.show.notes,
  episode_play_order: props.show.episode_play_order,
  show_runner_creator_id: props?.show?.showRunner?.creator_id,
})

// let showCategoryDescription = props.showCategory?.Description

const reloadImageHandler = () => {
  router.reload({
    only: ['image'],
  })
}

let submit = () => {
  form.name = showStore.name
  form.description = showStore.description
  form.category = showStore.category_id
  form.sub_category = showStore.sub_category_id
  form.episode_play_order = showStore.episode_play_order
  form.show_status_id = showStore.show_status_id
  form.notes = showStore.notes
  form.show_runner_creator_id = selectedShowRunnerCreatorId.value;
  form.patch(route('shows.update', props.show.slug))
}

teamStore.setActiveTeam(props.team)
teamStore.setActiveShow(props.show)

// function chooseCategory(event) {
//   showCategoryDescription = props.categories[event.target.selectedIndex].description;
// }

// let getCategory = ref(null);
// onBeforeMount(async () => {
//     getCategory.value = await props.show.category;
// })


</script>

