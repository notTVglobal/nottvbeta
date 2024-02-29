<template>

  <Head :title="`Edit Show: ${props.show.name}`"/>

  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 px-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <ShowEditHeader :show="show" :team="team" :form="form" @submit="submit"/>

      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

              <div v-if="form.errors.name" v-text="form.errors.name"
                   class="bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"></div>
              <div v-if="form.errors.description" v-text="form.errors.description"
                   class="bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"></div>

              <!-- Begin grid 2-col -->
              <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">

                <!--Left Column-->
                <div>
                  <div class="flex space-y-3">
                    <div class="mb-6">
                      <SingleImage :image="image" :key="image" :alt="'show poster'" class=""/>
                    </div>
                  </div>

                  <div class="w-full">

                    <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                           for="name"
                    >
                      Change Show Poster
                    </label>

                    <ImageUpload :image="image"
                                 :server="'/showsUploadPoster'"
                                 :name="'Upload Show Poster'"
                                 :maxSize="'30MB'"
                                 :fileTypes="'image/jpg, image/jpeg, image/png'"
                                 @reloadImage="reloadImage"
                    />

                  </div>

                </div>


                <!--Right Column-->
                <div>
                  <!--                                    <ShowPosterUpload-->
                  <!--                                        :team="props.show"-->
                  <!--                                        :images="props.images"-->
                  <!--                                    />-->
                  <form @submit.prevent="submit">

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="name"
                      >
                        Show Notes (only visible to team members)
                      </label>

                      <!--                      <input v-model="form.notes"-->
                      <!--                             class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
                      <!--                             type="text"-->
                      <!--                             name="notes"-->
                      <!--                             id="notes"-->

                      <!--                      >-->
                      <TabbableTextarea v-model="form.notes"
                                        class="border border-gray-400 p-2 w-full rounded-lg text-black"
                      />
                      <div v-if="form.errors.notes" v-text="form.errors.notes"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                             for="status"
                      >
                        Show Runner
                      </label>

                      <select required
                              class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg block mb-2 uppercase font-bold text-xs "
                              v-model="form.show_runner"
                      >
                        <option v-for="member in teamMembers"
                                :key="member.creator_id" :value="member.creator_id">{{ member.name }}
                        </option>


                      </select>
                      <div v-if="form.errors.show_runner" v-text="form.errors.show_runner"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                             for="status"
                      >
                        Status
                      </label>

                      <select required
                              class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg block mb-2 uppercase font-bold text-xs "
                              v-model="form.show_status_id"
                      >
                        <option v-for="status in statuses"
                                :key="status.id" :value="status.id">{{ status.name }}
                        </option>


                      </select>
                      <div v-if="form.errors.show_status_id" v-text="form.errors.show_status_id"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                             for="episode_play_order"
                      >
                        Episode Play Order
                      </label>

                      <select required
                              class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg block mb-2 uppercase font-bold text-xs "
                              v-model="form.episode_play_order"
                              id="episode_play_order"
                      >
                        <option disabled value="">Please select one</option> <!-- Disabled placeholder option -->
                        <option value="newest">Start with the Newest Episode</option>
                        <option value="oldest">Start with the First Episode</option>

                      </select>
                      <div v-if="form.errors.episode_play_order" v-text="form.errors.episode_play_order"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="category"
                      >
                        Category
                      </label>

                      <select
                          class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg block my-2 uppercase font-bold text-xs "
                          v-model="selectedCategoryId" @change="chooseCategory"
                      >
                        <option v-for="category in categories"
                                :key="category.id" :value="category.id">{{ category.name }}
                        </option>

                      </select>
                      <div v-if="form.errors.category" v-text="form.errors.category"
                           class="text-xs text-red-600 mt-1">
                      </div>
                      <span class="">{{ showStore.category_description }}</span>

                    </div>

                    <div class="mb-6">
                      <label class="block mb-1 uppercase font-bold text-xs text-light text-red-700"
                             for="sub_category"
                      >
                        Sub-category
                      </label>

                      <select
                          class="border border-gray-400 text-gray-800 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs"
                          v-model="selectedSubCategoryId" :disabled="!selectedCategoryId" @change="chooseSubCategory"
                      >
                        <option disabled value="">Select a subcategory</option>
                        <option v-for="subCategory in subCategories" :key="subCategory.id" :value="subCategory.id">
                          {{ subCategory?.name }}
                        </option>
                      </select>
                      <span class="">{{ showStore.sub_category_description }}</span>
                      <div v-if="form.errors.sub_category" v-text="form.errors.sub_category"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="name"
                      >
                        Show Name
                      </label>

                      <input v-model="form.name"
                             class="border border-gray-400 p-2 w-full rounded-lg text-black"
                             type="text"
                             name="name"
                             id="name"
                             required
                      >
                      <div v-if="form.errors.name" v-text="form.errors.name"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="description"
                      >
                        Description
                      </label>
                      <TabbableTextarea v-model="form.description"
                                        class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                        name="description"
                                        id="description"
                                        rows="10" cols="30"
                                        required
                      />
                      <div v-if="form.errors.description" v-text="form.errors.description"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="name"
                      >
                        Website URL
                      </label>

                      <input v-model="form.www_url"
                             class="border border-gray-400 p-2 w-full rounded-lg text-black"
                             type="text"
                             name="www_url"
                             id="www_url"
                      >
                      <div v-if="form.errors.www_url" v-text="form.errors.www_url"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="name"
                      >
                        Instagram Handle
                      </label>

                      <input v-model="form.instagram_name"
                             class="border border-gray-400 p-2 w-full rounded-lg text-black"
                             type="text"
                             name="instagram_name handle"
                             id="instagram_name"
                      >
                      <div v-if="form.errors.instagram_name" v-text="form.errors.instagram_name"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="name"
                      >
                        Telegram URL
                      </label>

                      <input v-model="form.telegram_url"
                             class="border border-gray-400 p-2 w-full rounded-lg text-black"
                             type="text"
                             name="telegram_url"
                             id="telegram_url"
                      >
                      <div v-if="form.errors.telegram_url" v-text="form.errors.telegram_url"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="name"
                      >
                        X (formerly Twitter) @
                      </label>

                      <input v-model="form.twitter_handle"
                             class="border border-gray-400 p-2 w-full rounded-lg text-black"
                             type="text"
                             name="twitter_handle"
                             id="twitter_handle"
                      >
                      <div v-if="form.errors.twitter_handle" v-text="form.errors.twitter_handle"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>

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


            </div>
          </div>
        </div>
      </div>


    </div>
  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useTeamStore } from '@/Stores/TeamStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import ShowEditHeader from '@/Components/Pages/Shows/Layout/EditShowHeader'
import TabbableTextarea from '@/Components/Global/TextEditor/TabbableTextarea'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import ImageUpload from '@/Components/Global/Uploaders/ImageUpload'
import Message from '@/Components/Global/Modals/Messages'
import { computed, onMounted, ref, watch } from 'vue'

usePageSetup('shows/slug/edit')

const appSettingStore = useAppSettingStore()
const showStore = useShowStore()
const teamStore = useTeamStore()

let props = defineProps({
  user: Object,
  show: Object,
  team: Object,
  image: Object,
  teamMembers: Array,
  categories: Object,
  statuses: Object,
})


let selectedCategoryId = ref(props?.show?.category?.id)
let selectedSubCategoryId = ref(props?.show?.subCategory?.id)

const subCategories = computed(() => {
  const category = props.categories.find(cat => cat.id === selectedCategoryId.value)
  return category ? category.sub_categories : []
})

// Watchers to update the store based on category and subcategory selections
watch(selectedCategoryId, () => {
  showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
}, {immediate: true})

watch(selectedSubCategoryId, () => {
  showStore.updateSubCategoryDescription(selectedSubCategoryId.value)
})

onMounted(() => {
  showStore.categories = props.categories
  showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
})

const chooseCategory = () => {
  // Update the selected category ID based on the new selection
  // Vue automatically updates selectedCategoryId due to v-model binding
  // So, there is no need to manually set it here

  // Call the store method to update descriptions and subcategories
  showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
}

const chooseSubCategory = () => {
  // Update the store state based on the new subcategory selection
  showStore.updateSubCategoryDescription(selectedSubCategoryId.value)
}

let form = useForm({
  name: props.show.name,
  description: props.show.description,
  show_status_id: props.show.show_status_id,
  category: props.show.category,
  sub_category: props.show.subCategory,
  www_url: props.show.www_url,
  instagram_name: props.show.instagram_name,
  telegram_url: props.show.telegram_url,
  twitter_handle: props.show.twitter_handle,
  notes: props.show.notes,
  episode_play_order: props.show.episode_play_order,
  show_runner: props?.show?.showRunner?.creator_id,
})

// let showCategoryDescription = props.showCategory?.Description

let reloadImage = () => {
  Inertia.reload({
    only: ['image'],
  })
}

let submit = () => {
  console.log('what\'s the show Runner?? ' + form.show_runner)
  form.category = showStore.category_id
  form.sub_category = showStore.sub_category_id
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
