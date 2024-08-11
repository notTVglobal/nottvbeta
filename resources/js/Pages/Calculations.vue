<template>

  <Head title="Shop"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <header class="flex justify-between items-center mb-3">
        <div id="topDiv">
          <h1 class="text-4xl font-bold text-gray-800 pb-3">Streaming Storage Calculations</h1>
        </div>
      </header>

      <div class="mb-6">
        <p class="text-lg text-gray-600">
          Estimate your storage needs and compare the costs of different storage providers for continuous streaming.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold text-blue-600 mb-4">Storage Requirements</h2>
          <p class="text-gray-700 mb-4">
            For a 24x7 streaming at <strong>1920x1080, H.264 1080, 23.98 fps</strong>:
          </p>
          <ul class="list-disc list-inside text-gray-700">
            <li><strong>Bitrate:</strong> 4.5 Mbps</li>
            <li><strong>Storage per hour:</strong> 2.44 GB</li>
            <li><strong>Total storage (72 hours):</strong> 2.44 TB</li>
          </ul>
          <div class="mt-4">
            <a href="https://www.digitalrebellion.com/webapps/videocalc" target="_blank">
              <button class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600">
                Open Storage Calculator
              </button>
            </a>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold text-green-600 mb-4">Cost Comparison</h2>
          <p class="text-gray-700 mb-4">Cost to store 1 TB and 72 hours of video:</p>
          <ul class="list-disc list-inside text-gray-700">
            <li>
              <a href="https://www.digitalocean.com/pricing/spaces-object-storage" target="_blank"
                 class="text-blue-500 underline hover:text-blue-700">
                DigitalOcean
              </a>:
              <ul class="pl-5 mt-2">
                <li><strong>Cost for 250 GiB:</strong> Included</li>
                <li><strong>Additional Storage Cost:</strong> $0.02/GiB</li>
                <li><strong>Cost for 1 TB (additional 750 GiB):</strong> $15.00/mo</li>
                <li><strong>Cost for 72 hours (2.44 TB):</strong> $36.80/mo</li>
              </ul>
            </li>
            <li class="mt-4">
              <a href="https://wasabi.com/pricing" target="_blank" class="text-blue-500 underline hover:text-blue-700">
                Wasabi
              </a>:
              <ul class="pl-5 mt-2">
                <li><strong>Cost per TB:</strong> $6.99/mo</li>
                <li><strong>Cost for 72 hours (2.44 TB):</strong> $17.05/mo</li>
              </ul>
            </li>
          </ul>
        </div>

      </div>

      <div class="p-6">
        <header class="flex justify-between items-center mb-8">
          <h1 class="text-4xl font-bold text-gray-800">Storage Growth Milestones</h1>
        </header>

        <!-- Growth Milestones for Storing 72 Hours of Live Stream Buffer -->
        <div class="mb-10">
          <h2 class="text-2xl font-semibold text-green-600 mb-4">Growth Milestones for 72 Hours Live Stream Buffer</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
              <thead>
              <tr class="bg-gray-200 text-gray-700 text-left">
                <th class="py-2 px-4">Milestone</th>
                <th class="py-2 px-4">Channel Count</th>
                <th class="py-2 px-4">Total Storage (TB)</th>
                <th class="py-2 px-4">DigitalOcean Cost (Monthly)</th>
                <th class="py-2 px-4">Wasabi Cost (Monthly)</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(milestone, index) in liveStreamBufferMilestones" :key="index" class="border-t">
                <td class="py-2 px-4">{{ milestone.milestone }}</td>
                <td class="py-2 px-4">{{ milestone.streamCount }}</td>
                <td class="py-2 px-4">{{ milestone.totalStorage }}</td>
                <td class="py-2 px-4">{{ milestone.digitalOceanCost }}</td>
                <td class="py-2 px-4">{{ milestone.wasabiCost }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Accumulated Storage of VOD Content -->
        <div class="mb-10">
          <h2 class="text-2xl font-semibold text-blue-600 mb-4">Accumulated Storage of VOD Content</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
              <thead>
              <tr class="bg-gray-200 text-gray-700 text-left">
                <th class="py-2 px-4">Time Period</th>
                <th class="py-2 px-4">VOD Content Added (TB/Month)</th>
                <th class="py-2 px-4">Total Storage After Period (TB)</th>
                <th class="py-2 px-4">DigitalOcean Cost (Monthly)</th>
                <th class="py-2 px-4">Wasabi Cost (Monthly)</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(content, index) in vodStorageMilestones" :key="index" class="border-t">
                <td class="py-2 px-4">{{ content.timePeriod }}</td>
                <td class="py-2 px-4">{{ content.vodContentAdded }}</td>
                <td class="py-2 px-4">{{ content.totalStorage }}</td>
                <td class="py-2 px-4">{{ content.digitalOceanCost }}</td>
                <td class="py-2 px-4">{{ content.wasabiCost }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>


        <!-- Content Equivalents -->
        <div>
          <h2 class="text-2xl font-semibold text-purple-600 mb-4">Content Equivalents for Accumulated VOD Storage</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
              <thead>
              <tr class="bg-gray-200 text-gray-700 text-left">
                <th class="py-2 px-4">Time Period</th>
                <th class="py-2 px-4">Total Storage (TB)</th>
                <th class="py-2 px-4">Hours of HD Content</th>
                <th class="py-2 px-4">Equivalent to:</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(equivalent, index) in contentEquivalents" :key="index" class="border-t">
                <td class="py-2 px-4">{{ equivalent.timePeriod }}</td>
                <td class="py-2 px-4">{{ equivalent.totalStorage }}</td>
                <td class="py-2 px-4">{{ equivalent.hoursOfContent }}</td>
                <td class="py-2 px-4">
                  {{ equivalent.equivalentTo }} <br>
                  ({{ equivalent.thirtyMinEpisodes }} 30-min episodes)
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>


        <!-- Explanation -->
        <div class="mt-10">
          <h2 class="text-xl font-semibold text-gray-800">Explanation of Calculations</h2>
          <p class="text-gray-600 mt-4">
            The equivalents in the Content Equivalents chart are calculated based on the following assumptions:
          </p>
          <ul class="list-disc list-inside text-gray-600 mt-2">
            <li><strong>Music Videos:</strong> Each music video is assumed to be 5 minutes long.</li>
            <li><strong>30-Minute Shows:</strong> Each show is 30 minutes in duration.</li>
            <li><strong>Movies:</strong> Each movie is considered to be 2 hours (120 minutes) long.</li>
          </ul>
          <p class="text-gray-600 mt-4">
            Using these assumptions, the number of content pieces that can fit into the accumulated storage is calculated as follows:
          </p>
          <ul class="list-disc list-inside text-gray-600 mt-2">
            <li><strong>Music Videos:</strong> 1 hour of content equals 12 music videos (60 minutes / 5 minutes per video).</li>
            <li><strong>30-Minute Shows:</strong> 1 hour of content equals 2 shows (60 minutes / 30 minutes per show).</li>
            <li><strong>Movies:</strong> 1 hour of content equals 0.5 movies (60 minutes / 120 minutes per movie).</li>
          </ul>
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
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'

usePageSetup('calculations')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  can: Object,
})

const liveStreamBufferMilestones = [
  {
    milestone: 'Initial',
    streamCount: '1 Stream',
    totalStorage: '2.44 TB',
    digitalOceanCost: '$36.80',
    wasabiCost: '$17.05',
  },
  {
    milestone: '10 Streams',
    streamCount: '10 Streams',
    totalStorage: '24.4 TB',
    digitalOceanCost: '$368.00',
    wasabiCost: '$170.50',
  },
  {
    milestone: '50 Streams',
    streamCount: '50 Streams',
    totalStorage: '122 TB',
    digitalOceanCost: '$1,840.00',
    wasabiCost: '$852.50',
  },
  {
    milestone: '100 Streams',
    streamCount: '100 Streams',
    totalStorage: '244 TB',
    digitalOceanCost: '$3,680.00',
    wasabiCost: '$1,705.00',
  },
  {
    milestone: '500 Streams',
    streamCount: '500 Streams',
    totalStorage: '1,220 TB',
    digitalOceanCost: '$18,400.00',
    wasabiCost: '$8,525.00',
  },
  {
    milestone: '1,000 Streams',
    streamCount: '1,000 Streams',
    totalStorage: '2,440 TB',
    digitalOceanCost: '$36,800.00',
    wasabiCost: '$17,050.00',
  },
]

const vodStorageMilestones = [
  {
    timePeriod: '1st Month',
    vodContentAdded: '10 TB',
    totalStorage: '10 TB',
    digitalOceanCost: '$150.00',
    wasabiCost: '$69.90',
  },
  {
    timePeriod: '6th Month',
    vodContentAdded: '60 TB',
    totalStorage: '70 TB',
    digitalOceanCost: '$1,350.00',
    wasabiCost: '$489.30',
  },
  {
    timePeriod: '12th Month',
    vodContentAdded: '120 TB',
    totalStorage: '190 TB',
    digitalOceanCost: '$3,300.00',
    wasabiCost: '$1,328.10',
  },
  {
    timePeriod: '18th Month',
    vodContentAdded: '180 TB',
    totalStorage: '370 TB',
    digitalOceanCost: '$6,600.00',
    wasabiCost: '$2,587.50',
  },
  {
    timePeriod: '24th Month',
    vodContentAdded: '240 TB',
    totalStorage: '610 TB',
    digitalOceanCost: '$10,800.00',
    wasabiCost: '$4,266.90',
  },
]

const contentEquivalents = [
  { timePeriod: '1st Month', totalStorage: '10 TB', hoursOfContent: '2,500 hours', equivalentTo: '30,000 music videos or 1,250 movies', thirtyMinEpisodes: '5,000' },
  { timePeriod: '6th Month', totalStorage: '70 TB', hoursOfContent: '17,500 hours', equivalentTo: '210,000 music videos or 8,750 movies', thirtyMinEpisodes: '35,000' },
  { timePeriod: '12th Month', totalStorage: '190 TB', hoursOfContent: '47,500 hours', equivalentTo: '570,000 music videos or 23,750 movies', thirtyMinEpisodes: '95,000' },
  { timePeriod: '18th Month', totalStorage: '370 TB', hoursOfContent: '92,500 hours', equivalentTo: '1,110,000 music videos or 46,250 movies', thirtyMinEpisodes: '185,000' },
  { timePeriod: '24th Month', totalStorage: '610 TB', hoursOfContent: '152,500 hours', equivalentTo: '1,830,000 music videos or 76,250 movies', thirtyMinEpisodes: '305,000' },
];

</script>

