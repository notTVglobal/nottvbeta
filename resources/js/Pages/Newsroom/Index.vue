<template>
  <div>
    <Head title="Newsroom"/>

    <div class="place-self-center flex flex-col gap-y-3">
      <div id="topDiv" class="bg-white text-black dark:bg-gray-900 dark:text-gray-50 p-5 mb-10">

        <Messages v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>


        <NewsHeader :can="can">Newsroom</NewsHeader>

        <!--        OLD STORIES PLACEHOLDER-->
        <section class="mt-10">
          <div class="flex w-full h-fit flex-wrap justify-between align-baseline">
            <h2 class="text-center text-xl md:text-2xl font-medium my-auto align-middle pl-6">News Stories</h2>

              <div class="my-auto">
                <div class="relative">
                  <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full
                            focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
                  <div class="absolute top-0 flex items-center h-full ml-2">
                    <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                      <path
                          d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>
                    </svg>

                  </div>
                </div>
              </div>

            <div class="my-auto pr-6">
              <div class="flex justify-end ">
                <button
                    v-if="can.createNewsStory"
                    @click="appSettingStore.btnRedirect(`newsStory/create`)"
                    class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                >Create News Story
                </button>
              </div>
            </div>
          </div>









          <div class="overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
              <div
                  class="relative overflow-x-auto shadow-md sm:rounded-lg"
              >

                <div
                    class="table w-full text-sm text-left text-gray-500 dark:text-gray-400"
                >
                  <div
                      class="table-header-group text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                  >
                    <div class="table-row">
                      <div scope="col" class="hidden md:table-cell min-w-[8rem] px-6 py-3">
                        Image
                      </div>
                      <div scope="col" class="table-cell px-6 py-3">
                        Title
                      </div>
                      <div scope="col" class="hidden xl:table-cell px-6 py-3">
                        Created On
                      </div>
                      <div scope="col" class="hidden 2xl:table-cell px-6 py-3">
                        Status
                      </div>
                      <div scope="col" class="hidden lg:table-cell px-6 py-3">
                        Published On
                      </div>
                      <div scope="col" class="hidden lg:table-cell px-6 py-3">

                      </div>
                    </div>
                  </div>
                  <div class="table-row-group">
                    <div
                        v-for="newsStory in newsStories.data"
                        :key="newsStory.id"
                        class="table-row bg-white border-b dark:bg-gray-900 dark:border-gray-700 "
                    >
                      <div
                          scope="row"
                          class="hidden md:table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                      >
                        <button
                            @click="appSettingStore.btnRedirect(`/news/story/${newsStory.slug}`)"
                        >
                        <SingleImage :image="newsStory.image" alt="news cover" class="rounded-full h-20 w-20 object-cover" />
                        </button>
                      </div>
                      <div
                          scope="row"
                          class="table-cell px-6 py-4 font-medium text-gray-900 dark:text-gray-50 whitespace-nowrap align-middle"
                      >
                        <button
                            @click="appSettingStore.btnRedirect(`/news/story/${newsStory.slug}`)"
                            class="text-lg font-semibold text-blue-800 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-200"
                        >{{ newsStory.title }}
                        </button>
                        <div>By {{newsStory.user.name}}</div>
                        <div class="flex flex-col pt-2 text-sm uppercase">
                          <div v-if="newsStory.city">
                            <div>{{newsStory.city}}, {{newsStory.province}}</div>
                          </div>
                          <div v-if="newsStory.province && !newsStory.city && !newsStory.federalElectoralDistrict && !newsStory.subnationalElectoralDistrict">
                            <div>{{newsStory.province}}</div>
                          </div>
                          <div v-if="newsStory.federalElectoralDistrict">
                            <div>{{newsStory.federalElectoralDistrict}}</div>
                          </div>
                          <div v-if="newsStory.subnationalElectoralDistrict">
                            <div>{{newsStory.subnationalElectoralDistrict}}</div>
                          </div>
                          <div v-if="newsStory.newsCategory" class="font-thin">
                            {{newsStory.newsCategory}}
                            <span v-if="newsStory.newsCategorySub"> | {{newsStory.newsCategorySub}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                          scope="row"
                          class="hidden xl:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap align-middle"
                      >
                      <span :class="{'text-gray-500':newsStory.published_at, 'italic':newsStory.published_at}">{{
                          formatDate(newsStory.created_at)
                        }}</span>
                      </div>
                      <div class="hidden 2xl:table-cell px-6 py-4 align-middle">
                        <span>{{ newsStory.status.name }}</span>
                      </div>
                      <div class="hidden lg:table-cell px-6 py-4 align-middle space-x-2">
                        <button
                            v-if="props.can.publishNewsStory && !newsStory.published_at && newsStory.status.id === 3"
                            @click="openConfirmPublishDialog(newsStory)"
                            class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                        >Publish
                        </button>
                        <span v-if="!props.can.publishNewsStory && !newsStory.published_at"
                              class="mr-6 text-gray-500 italic"> not yet published</span>
                        <span v-if="newsStory.published_at" class="mr-6 text-gray-800 dark:text-white font-semibold">{{
                            formatDate(newsStory.published_at)
                          }}</span>
                      </div>
                      <div class="hidden lg:table-cell px-6 py-4 align-middle space-x-2 space-y-2">
                        <button
                            v-if="newsStory.can.editNewsStory"
                            @click="appSettingStore.btnRedirect(`/newsStory/${newsStory.slug}/edit`)"
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Edit
                        </button>
                        <button
                            class="px-4 py-2 text-white font-semibold bg-red-600 hover:bg-red-500 rounded-lg"
                            @click="destroy(newsStory.id)"
                            v-if="newsStory.can.deleteNewsStory && newsStory.status.id === 1"
                        >
                          <font-awesome-icon icon="fa-trash-can"/>
                        </button>
                      </div>

                    </div>
                  </div>
                </div>
                <!-- Paginator -->
                <Pagination :data="newsStories" class=""/>
              </div>
            </div>
          </div>

        </section>


        <section class="text-center p-4 md:p-8 mb-8">
          <h1 class="text-2xl md:text-4xl font-bold mb-4">Welcome To Your Digital Newsroom</h1>
          <p class="text-md md:text-lg mb-8 text-indigo-800 ">
            Where seasoned journalism intersects with digital innovation.<br>
          </p>
          <p class="mb-8 mx-auto w-1/2 text-center text-indigo-800 font-semibold">
            Here, we are redefining news for the digital era, cutting-edge tools and collaborative expertise help us
            deliver impactful stories with precision and depth.
          </p>

          <h2 class="text-center text-xl md:text-3xl  mb-4">Core Pillars of the Newsroom</h2>

          <ul class="list-disc list-inside text-left mx-auto w-1/2 md:w-1/2">
            <li class="mb-2"><span class="font-semibold">Advanced Information Gathering:</span> Utilizing a vast network
              of digital resources and on-the-ground insights to capture the full spectrum of news.
            </li>
            <li class="mb-2"><span class="font-semibold">Precision Editing:</span> Combining journalistic rigor with
              digital acumen to sharpen narratives and enhance factual accuracy.
            </li>
            <li class="mb-2"><span class="font-semibold">Innovative Content Production:</span> Crafting stories that
              thrive across mediums – from immersive articles to engaging digital broadcasts.
            </li>
            <li class="mb-2"><span class="font-semibold">Strategic Coverage Planning:</span> Focused on impactful
              journalism, we dissect and deliver stories that matter in real-time.
            </li>
            <li class="mb-2"><span class="font-semibold">Collaborative Dynamics:</span> Our strength lies in our
              collective expertise, fostering a culture of continuous learning and mutual enhancement.
            </li>
            <li><span class="font-semibold">Technology at the Forefront:</span> Harnessing the power of the latest
              digital tools to keep our newsroom ahead in a rapidly evolving media landscape.
            </li>
          </ul>
        </section>


        <section class="bg-white text-gray-800 p-6">
          <h2 class="text-xl font-bold mb-4">Revolutionizing News Delivery: Our Advanced Database System</h2>
          <p class="mb-3">We're thrilled to introduce our cutting-edge news database, a game-changer in how we
            categorize and deliver content. This innovative system uniquely combines detailed geographical data with a
            rich array of categories, setting new standards in news personalization and relevance.</p>
          <ul class="list-disc list-inside mb-3">
            <li><strong>Hyper-Localized Content:</strong> Our database's precision in geographical categorization
              enables us to deliver news tailored to specific localities, right down to postal codes.
            </li>
            <li><strong>Enhanced User Experience:</strong> Users can easily navigate and access news that is not only
              relevant to their interests but also specific to their location.
            </li>
            <li><strong>Data-Driven Approach:</strong> The structure allows sophisticated data analysis, enhancing our
              understanding of reader preferences and enabling targeted content delivery.
            </li>
            <li><strong>Unmatched Local Focus:</strong> Unlike traditional platforms, we emphasize local issues, giving
              a voice to community-specific news and concerns.
            </li>
          </ul>
          <p class="mb-3">Our approach is more than just a technical advancement; it represents our commitment to
            innovative, localized, and impactful journalism. This strategic enhancement positions us at the forefront of
            media, offering unparalleled service and content to our audience.</p>
        </section>

        <section class="bg-gray-100 py-6 px-4">
          <div class="max-w-4xl mx-auto">
            <h2 class="text-xl font-semibold mb-4">Understanding Our Geographical News Structure</h2>

            <p class="mb-4">Our news database offers a detailed and layered approach to categorizing news stories,
              providing users with the ability to access content based on their geographical preferences, from broad
              national levels to more localized political divisions.</p>

            <div class="mb-6">
              <h3 class="text-lg font-semibold mb-2">Hierarchical Geographical Structure</h3>
              <ul class="list-disc pl-5">
                <li class="mb-2">Top Level - <strong>Provinces and Territories</strong>: The broadest categorization,
                  covering all Canadian provinces and territories.
                </li>
                <li class="mb-2">Second Level - <strong>Federal MP Ridings</strong>: Within each province or territory,
                  further classified into Federal MP ridings for national political news.
                </li>
                <li class="mb-2">Third Level - <strong>MLA Ridings</strong>: An additional layer of provincial political
                  divisions, similar to State Senate or Assembly districts in the U.S.
                </li>
              </ul>
            </div>

            <div class="mb-6">
              <h3 class="text-lg font-semibold mb-2">Database Structure and User Experience</h3>
              <p class="mb-4">Our database structure is designed to reflect this hierarchical model, ensuring that news
                stories are accurately categorized for ease of access:</p>
              <ul class="list-disc pl-5">
                <li class="mb-2">The <strong>News Stories Table</strong> links articles to specific ridings or provinces
                  as applicable.
                </li>
                <li class="mb-2">The <strong>Provinces, Federal Ridings, and MLA Ridings Tables</strong> offer multiple
                  layers of geographical categorization.
                </li>
                <li class="mb-2">This structure allows for scalability and adaptation for expansion into other
                  countries, like the U.S.
                </li>
              </ul>
            </div>

            <div>
              <h3 class="text-lg font-semibold mb-2">Important Considerations</h3>
              <ul class="list-disc pl-5">
                <li class="mb-2">Regular updates are essential to keep electoral boundaries and categorizations
                  current.
                </li>
                <li class="mb-2">A user-friendly interface is crucial for navigating these geographical layers
                  effectively.
                </li>
                <li class="mb-2">Robust filtering options enable users to access news from multiple layers
                  simultaneously.
                </li>
              </ul>
            </div>
          </div>
        </section>

        <section class="bg-white p-6 rounded-lg shadow-md">
          <h2 class="text-lg font-semibold text-gray-800">Enhancement Proposal: Mapping Federal Ridings with Spatial
            Data</h2>
          <p class="text-gray-600 mt-4">
            Dear News Team,
          </p>
          <p class="text-gray-600 mt-2">
            We have an exciting opportunity to enhance our data capabilities by integrating the mapping of federal
            ridings using polygon data. By adopting a database that supports spatial data types and Geographic
            Information System (GIS) features, we can accurately sort cities and towns into their corresponding federal
            ridings based on geographic coordinates.
          </p>
          <p class="text-gray-600 mt-2">
            This feature would allow us to:
            <ul class="list-disc ml-6 mt-2 text-gray-600">
              <li>Enhance our demographic analyses and reporting by aligning them with specific electoral districts.
              </li>
              <li>Improve the precision of our political and electoral coverage, offering more localized and relevant
                news to our audience.
              </li>
              <li>Seamlessly integrate other datasets, such as census data or polling results, into the geographical
                context of these ridings.
              </li>
              <li>Provide interactive maps and visualizations for our users, offering a more engaging and informative
                experience.
              </li>
            </ul>
          </p>
          <p class="text-gray-600 mt-2">
            The question arises: Do we need to build this feature? The potential benefits are significant, both in terms
            of enhancing our reporting capabilities and in providing more targeted and meaningful content to our users.
            It's an opportunity to leverage modern data processing and mapping technologies to strengthen our position
            as a leading news provider.
          </p>
          <p class="text-gray-600 mt-4">
            We welcome your thoughts and insights on this proposal.
          </p>
          <p class="text-gray-600 mt-4">
            Best regards,
            <br><br>
            Travis Cross<br>
            notTV Visionary
          </p>
        </section>


      </div>
    </div>
  </div>

  <ConfirmPublishNewsDialog :newsStory="selectedNewsStory" @confirmPublish="publish"/>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import NewsHeader from '@/Components/Pages/News/NewsHeader'
import Pagination from '@/Components/Global/Paginators/Pagination'
import Messages from '@/Components/Global/Modals/Messages'
import NewsStoriesTable from '@/Components/Pages/Newsroom/NewsStoriesTable.vue'
import ConfirmPublishNewsDialog from '@/Components/Global/Modals/ConfirmPublishNewsDialog.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

usePageSetup('newsroom')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

function scrollToCities() {
  document.getElementById('cities').scrollIntoView({behavior: 'smooth'})
}

let props = defineProps({
  filters: Object,
  can: Object,
  // news: {
  //   type: Object,
  //   default: () => ({}),
  // },
  newsStories: Object,
})

const selectedNewsStory = ref(null)
let search = ref(props.filters.search)

let form = useForm({})

watch(search, throttle(function (value) {
  Inertia.get('/newsroom', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

function deleteTeamMember(member) {
  teamStore.deleteMemberName = member.name
  teamStore.deleteMemberId = member.id
  teamStore.confirmDialog = true
}

const openConfirmPublishDialog = (newsStory) => {
  selectedNewsStory.value = newsStory
  appSettingStore.showConfirmationDialog = true
}

function publish() {
  Inertia.patch(route('newsroom.publish', {id: selectedNewsStory.value.id}))
}

function destroy(id) {
  if (confirm('Are you sure you want to Delete')) {
    form.delete(route('newsStory.destroy', id))

  }
}

</script>
