<template>

  <Head :title="`Edit Team: ${props.team.name}`"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <TeamEditHeader :team="props.team" :teamCreator="props.teamCreator" :form="form" @submit="submit"/>


      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

              <!--                            <TeamEditBody-->
              <!--                                :team="props.team"-->
              <!--                                :logo="props.logo"-->
              <!--                                :images="props.images"-->
              <!--                            />-->


              <div v-if="form.errors.name" v-text="form.errors.name"
                   class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
              <div v-if="form.errors.description" v-text="form.errors.description"
                   class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
              <div v-if="form.errors.totalSpots" v-text="form.errors.totalSpots"
                   class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

              <!-- Begin grid 2-col -->
              <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">

                <!--Left Column-->
                <div>
                  <div class="flex space-y-3">
                    <div class="mb-6">
                      <SingleImage :image="image" :key="image" :alt="'team logo'" class=""/>
                      <!--                                            <img :src="'/storage/images/' + props.logo" :key="logo"/>-->
                    </div>
                  </div>

                  <div>

                    <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                           for="name"
                    >
                      Change Team Logo
                    </label>
                    <ImageUpload :image="image"
                                 :modelType="'team'"
                                 :modelId="team.id"
                                 :name="'Upload Team Logo'"
                                 :maxSize="'10MB'"
                                 :fileTypes="'image/jpg, image/jpeg, image/png'"
                                 @reloadImage="reloadImage"
                    />

                  </div>

                </div>

                <!--Right Column-->
                <div>
                  <!--            Replace this with TeamLogoUpload -->
                  <!--                                    <TeamLogoUpload-->
                  <!--                                        :team="props.team"-->
                  <!--                                        :images="props.images"-->
                  <!--                                    />-->


                  <form @submit.prevent="submit">
                    <div class="mb-6">
                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="name"
                      >
                        Team Name
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

                    <!--                                        tec21: this is to become a searchable list to select a team leader -->
                    <!--                                        <div class="mb-6">-->
                    <!--                                            <label class="block mb-2 uppercase font-bold text-xs text-gray-700"-->
                    <!--                                                   for="teamLeader"-->
                    <!--                                            >-->
                    <!--                                                Team Leader-->
                    <!--                                            </label>-->
                    <!--                                            <input v-model="form.teamLeader"-->
                    <!--                                                   class="border border-gray-400 p-2 w-full rounded-lg"-->
                    <!--                                                   type="text"-->
                    <!--                                                   name="teamLeader"-->
                    <!--                                                   id="teamLeader"-->
                    <!--                                            />-->
                    <!--                                            <div v-if="form.errors.teamLeader" v-text="form.errors.teamLeader"-->
                    <!--                                                 class="text-xs text-red-600 mt-1"></div>-->
                    <!--                                        </div>-->

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="teamLeader"
                      >
                        Team Leader
                      </label>
                      <select
                          class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-800"
                          v-model="selectedTeamLeader"
                      >
                        <option
                            v-if="props.possibleTeamLeaders && props.possibleTeamLeaders.length > 0"
                            v-for="leader in props.possibleTeamLeaders"
                            :key="leader.id"
                            :value="leader.id"
                            class="bg-white text-black border-b dark:text-gray-50 dark:bg-gray-800 dark:border-gray-600"
                        >
                          {{ leader.name }} ({{ leader.role }})
                        </option>
                        <option v-else disabled>
                          There are no eligible team leaders.
                        </option>

                      </select>

                      <div class="text-xs">Only the team creator and team managers are listed, if their creator accounts
                        are in good standing.
                      </div>

                      <div v-if="form.errors.teamLeader" v-text="form.errors.teamLeader"
                           class="text-xs text-red-600 mt-1"></div>


                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="description"
                      >
                        Maximum # of Team Members
                      </label>
                      <input v-model="form.totalSpots"
                             class="border border-gray-400 p-2 w-full rounded-lg text-black"
                             type="text"
                             name="totalSpots"
                             id="totalSpots"
                      />
                      <div v-if="form.errors.totalSpots" v-text="form.errors.totalSpots"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="flex justify-end mb-6">
                      <JetValidationErrors class="mr-4"/>
                      <button
                          type="submit"
                          class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded-lg py-2 px-4 "
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
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useTeamStore } from '@/Stores/TeamStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import TeamEditHeader from '@/Components/Pages/Teams/Edit/TeamEditHeader'
import TeamEditBody from '@/Components/Pages/Teams/Edit/TeamEditBody'
import TeamLogoUpload from '@/Components/Global/FilePond/TeamLogoUpload'
import TabbableTextarea from '@/Components/Global/TextEditor/TabbableTextarea'
import ImageUpload from '@/Components/Global/Uploaders/ImageUpload'
import Message from '@/Components/Global/Modals/Messages'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'

usePageSetup('teams/slug/edit')

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()

let props = defineProps({
  team: Object,
  teamCreator: Object,
  possibleTeamLeaders: Array,
  teamLeader: Object,
  // possibleTeamLeaders: {
  //     type: Object,
  //     default: () => ({ data: [] }) // Provide a default value
  // },
  image: Object,
})

// const selectedTeamLeader = ref(props.teamLeader ? props.teamLeader.id : null);
// const selectedTeamLeader = ref(null);

// watchEffect(() => {
//     if (props.possibleTeamLeaders && props.possibleTeamLeaders.data && props.teamLeader) {
//         const teamLeaderExists = props.possibleTeamLeaders.data.some(leader => leader.id === props.teamLeader.id);
//         if (!teamLeaderExists) {
//             props.possibleTeamLeaders.data.push(props.teamLeader);
//         }
//         selectedTeamLeader.value = props.teamLeader.id;
//     }
// });

let form = useForm({
  id: props.team.id,
  name: props.team.name,
  description: props.team.description,
  totalSpots: props.team.totalSpots,
  teamLeader: props.teamLeader ? props.teamLeader.id : null,
})

const selectedTeamLeader = ref(props.teamLeader ? props.teamLeader.id : null)

// Watch for changes in selectedTeamLeader and update the form
watch(selectedTeamLeader, (newValue) => {
  form.teamLeader = newValue
})

let reloadImage = () => {
  Inertia.reload({
    only: ['image'],
  })
}

let submit = () => {
  form.patch(route('teams.update', props.team.slug))
}

teamStore.setActiveTeam(props.team)
teamStore.logoName = props.image.name

</script>


