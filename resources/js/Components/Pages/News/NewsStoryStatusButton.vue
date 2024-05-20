<template>
  <div>
    <div v-if="!userStore.isAdmin">
      <button v-if="can.editNewsStory && newsStory.status.id !== 9 && newsStory.status.id !== 10"
              :class="newsStoryStatusClass" @click="openNewsStoryStatuses(newsStory.id)">{{ newsStory.status.name }}
      </button>
      <div v-if="!can.editNewsStory"
           class="cursor-not-allowed" :class="newsStoryStatusClass">{{ newsStory.status.name }}
      </div>
    </div>
    <div v-else>
      <button :class="newsStoryStatusClass" @click="openNewsStoryStatuses(newsStory.id)">{{ newsStory.status.name }}</button>
    </div>

    <!--    <dialog :id="`confirmPublishModal.${newsStory.id}`" class="modal">-->
    <!--      <div class="modal-box">-->
    <!--        <h3 class="text-center font-bold text-lg">Are you sure you want to publish?</h3>-->
    <!--        <p class="text-center py-4">This action cannot be undone. When you publish an News Story it registers it on the-->
    <!--          blockchain and distributes it on the network.</p>-->
    <!--        <div class="modal-action">-->
    <!--          <form method="dialog">-->
    <!--            <button @click="changeStatus(newsStory, 6)"-->
    <!--                    class="btn text-white bg-green-600 hover:bg-green-500 mr-2">Yes! Publish Now!-->
    <!--            </button>-->
    <!--            <button class="btn text-white bg-orange-600 hover:bg-orange-500">No, cancel</button>-->
    <!--          </form>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </dialog>-->



  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed, ref } from 'vue'
import { useUserStore } from '@/Stores/UserStore'
import { useNewsStore } from '@/Stores/NewsStore'

const userStore = useUserStore()
const newsStore = useNewsStore()

let props = defineProps({
  newsStory: Object,
  can: Object,
})

const errorMessage = ref('')

const dialogId = props.newsStory.id + 'newsStoryStatuses'

const openNewsStoryStatuses = (newsStoryId) => {
  console.log('Opening News Story Statuses dialog:', dialogId)
  newsStore.newsStoryId = newsStoryId
  document.getElementById('newsStoryStatusChangeModal').showModal()
}

// const checkNewsStoryStatus = (newsStory, statusId) => {
//   console.log('Opening News Story Statuses dialog:', dialogId)
//   // if (statusId === 6) {
//   //   // open modal to confirm they want to publish.
//   //   document.getElementById('confirmPublishModal.'+props.newsStory.id).showModal()
//   // }
//   // else if (statusId !== 6) {
//     changeStatus(newsStory, statusId)
//   // }
// };

const changeStatus = (newsStory, statusId) => {
  console.log('Opening Change Status dialog:', dialogId)
  // document.getElementById(newsStoryStatusChangeModal).close()
  // Inertia.patch(route('news.story.changeStatus'), {
  //   newsStory_id: newsStory.id, // Assuming you have the ID available in `newsStory`
  //   new_status_id: statusId
  // }, {
  //   onError: (errors) => {
  //     console.error('Error changing status:', errors);
  //   }
  // });
}

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
