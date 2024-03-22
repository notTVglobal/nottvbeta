<template>
  <div>
    <div v-if="!userStore.isAdmin">
      <button v-if="can.editNewsStory && newsStory.status !== 9 && newsStory.status !== 10"
              :class="newsStoryStatusClass" @click="openNewsStoryStatuses">{{ newsStory.status.name }}
      </button>
      <div v-if="!can.editNewsStory || props.episodeStatusId === 9 || props.episodeStatusId === 10"
           class="cursor-not-allowed" :class="newsStoryStatusClass">{{ newsStory.status.name }}
      </div>
    </div>
    <div v-else>
      <button :class="newsStoryStatusClass" @click="openNewsStoryStatuses()">{{ newsStory.status.name }}</button>
    </div>

    <dialog :id="`confirmPublishModal.${newsStory.id}`" class="modal">
      <div class="modal-box">
        <h3 class="text-center font-bold text-lg">Are you sure you want to publish?</h3>
        <p class="text-center py-4">This action cannot be undone. When you publish an News Story it registers it on the
          blockchain and distributes it on the network.</p>
        <div class="modal-action">
          <form method="dialog">
            <button @click="changeStatus(newsStory, 6)"
                    class="btn text-white bg-green-600 hover:bg-green-500 mr-2">Yes! Publish Now!
            </button>
            <button class="btn text-white bg-orange-600 hover:bg-orange-500">No, cancel</button>
          </form>
        </div>
      </div>
    </dialog>

    <dialog :id="dialogId" class="modal">
      <div class="modal-box h-fit bg-white text-black">
        <h2 class="text-center mb-2">Change the News Story Status:</h2>
        <div v-for="(status, key) in newsStoryStatuses" :key="key" class="text-center">
          <div class="btn btn-wide my-1" @click="checkNewsStoryStatus(newsStory, status.id)">{{ status.name }}</div>
        </div>
      </div>

      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>

  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed, ref } from 'vue'
import { format } from 'date-fns'
import { useTeamStore } from '@/Stores/TeamStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useUserStore } from '@/Stores/UserStore'

const teamStore = useTeamStore()
const showStore = useShowStore()
const userStore = useUserStore()

let props = defineProps({
  newsStory: Object,
  newsStoryStatuses: Object,
  can: Object,
})

const errorMessage = ref('')
// const dialogId = props.newsStory.id + 'newsStoryStatuses'


// async function changeNewsStoryStatus(newsStoryId, statusId) {
//   try {
//     const response = await axios.patch('/newsStory/changeNewsStoryStatus', {
//       newsStory_id: newsStoryId,
//       new_status_id: statusId,
//     })
//     // Handle success response as needed
//     // ...
//     // Check if the response contains an "alert" message
//     if (response.data.alert) {
//       // Display the alert message
//       alert(response.data.alert)
//     }
//   } catch (error) {
//     if (error.response) {
//       showStore.errorMessage = error.response.data.error
//     } else {
//       // console.error(error);
//     }
//   }
//

const dialogId = props.newsStory.id+'newsStoryStatuses'

  const openNewsStoryStatuses = () => {
    document.getElementById(dialogId).showModal()
  }


  // return response
//   document.getElementById(dialogId).close()
//   Inertia.reload()
// }

const checkNewsStoryStatus = (newsStory, statusId) => {
  if (statusId === 6) {
    // open modal to confirm they want to publish.
    document.getElementById('confirmPublishModal.'+props.newsStory.id).showModal()
  }
  else if (statusId !== 6) {
    changeStatus(newsStory, statusId)
  }
};


// function changeStatus() {
//   // Example of sending a PATCH request to a server route
//   Inertia.patch('/newsStoryChangeNewsStoryStatus', { /* your data */ })
//       .then(() => {
//         message.value = 'News Story Status Updated Successfully';
//         // Update any other local state as necessary
//       });
// }

const changeStatus = (newsStory, statusId) => {
  document.getElementById(dialogId).close()
  Inertia.patch(route('news.story.changeStatus'), {
    newsStory_id: newsStory.id, // Assuming you have the ID available in `newsStory`
    new_status_id: statusId
  }, {
    onError: (errors) => {
      console.error('Error changing status:', errors);
    }
  });
}

// async function changeNewsStoryStatus(newsStoryId, statusId) {



//   try {
//     const response = await axios.patch('/newsStoryChangeNewsStoryStatus', {
//       newsStory_id: newsStoryId,
//       new_status_id: statusId,
//     });
//     // Handle success response as needed
//     // ...
//     // Check if the response contains an "alert" message
//     if (response.data.alert) {
//       // Display the alert message
//       alert(response.data.alert);
//     }
//   } catch (error) {
//     if (error.response) {
//       showStore.errorMessage = error.response.data.error
//     } else {
//       // console.error(error);
//     }
//   }
//   // return response
//   document.getElementById(dialogId).close()
//   Inertia.reload()
// }

const closeModals = () => {
  document.getElementById(dialogId).close()
  document.getElementById('confirmPublishModal.' + props.newsStory.id).close()
  Inertia.reload()
}

const newsStoryStatusClass = computed(() => ({
  'btn btn-sm px-6 w-fit font-semibold text-medium text-yellow-600': props.newsStory.status.id === 1,
  'btn btn-sm px-3 w-fit font-semibold font-italic text-medium text-red-700': props.newsStory.status.id === 2,
  'btn btn-sm px-3 w-fit font-semibold text-medium text-green-600': props.newsStory.status.id === 3,
  'btn btn-sm px-3 w-fit font-semibold text-medium text-orange-400': props.newsStory.status.id === 4,
  'btn btn-sm px-3 w-fit font-semibold text-medium text-purple-700': props.newsStory.status.id === 5,
  'btn btn-sm px-3 w-fit font-italic text-medium text-pink-500': props.newsStory.status.id === 6,
  'btn btn-sm px-3 w-fit font-bold text-medium light:text-black dark:text-white': props.newsStory.status.id === 7,
  'btn btn-sm px-3 w-fit font-medium text-medium text-gray-500': props.newsStory.status.id === 8,
  // 'btn btn-sm px-3 w-fit font-semibold font-italic text-xl text-red-700': props.newsStory.status.id === 9,
  'btn btn-sm px-3 w-fit font-bold text-medium text-red-800': props.newsStory.status.id === 10,
}))

</script>
