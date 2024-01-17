<template>
  <Head title="Go Live"/>
  <!--        <template #header>-->
  <!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
  <!--                Dashboard-->
  <!--            </h2>-->
  <!--        </template>-->

  <!--        <div class="py-12">-->
  <!--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
  <!--                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">-->

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between">
        <div class="grid grid-cols-1 grid-rows-2">
          <h1 class="text-3xl font-semibold">Go Live</h1>
        </div>
        <div>
          <CancelButton/>
        </div>
        <!--                <div class="grid grid-cols-1 grid-rows-2">-->
        <!--                    <div class="justify-self-end">-->
        <!--                        <Link :href="`/dashboard`"><button-->
        <!--                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"-->
        <!--                        >Dashboard</button>-->
        <!--                        </Link>-->
        <!--                    </div>-->
        <!--                </div>-->
      </div>
      <p>
        Here we will place instructions on how to go live..
      </p>
      <ul class="list-disc list-inside pt-4 space-y-1">
        <li>
          Add a dropdown of your shows to select which show you want to go live on.
        </li>
        <li>
          Display go live instructions here.
        </li>
        <li>
          If you want to stream from a mobile device we will include a video here to show you how to download and setup
          the Larix Broadcaster app.
        </li>
        <li>
          If you want to stream from software like Open Broadcaster or vMix we will include a video to show you how
          here.
        </li>

      </ul>
    </div>
  </div>
  <!--                </div>-->
  <!--            </div>-->
  <!--        </div>-->
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'
import CancelButton from '@/Components/Global/Buttons/CancelButton'

usePageSetup('goLive')

const appSettingStore = useAppSettingStore()

</script>

