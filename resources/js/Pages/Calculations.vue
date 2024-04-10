<template>

    <Head title="Shop" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

            <header class="flex justify-between mb-3">
                <div id="topDiv">
                    <h1 class="text-3xl font-semibold pb-3">Calculations</h1>
                </div>
            </header>

            <div class="mb-4">
                <p class="mt-4">
                    A page for keeping track of notes and calculations.
                </p>
            </div>

            <div>
                <a href="https://www.digitalrebellion.com/webapps/videocalc" target="_blank" class="hover:text-blue-500">Video Space Calculator.</a>
                <div>
                    1920x1080, H.264 1080, 23.98 fps = 2.44 TB per channel (at 24x7 streaming for 72 hours total)
                </div>
            </div>

            <div>
                <a href="https://www.digitalrebellion.com/webapps/videocalc" target="_blank" class="hover:text-blue-500">Digital Ocean Storage Pricing</a>
                <div>
                    1,000 GiB, $0.150 $/HR, $100 $/MO (2.44 TB storage = $244/mo, $0.363/hour)
                </div>
            </div>


<!--            <div class="flex flex-row justify-end gap-x-4 mb-4">-->

<!--                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />-->
<!--            </div>-->

            <div class="px-2">
<!--                Display items, services and events here.-->
            </div>

        </div>
    </div>

</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import Message from "@/Components/Global/Modals/Messages"

usePageSetup('calculations')

const appSettingStore = useAppSettingStore()

let props = defineProps({
    can: Object,
})

</script>

