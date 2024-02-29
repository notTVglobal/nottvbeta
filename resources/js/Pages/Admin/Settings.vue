<template>

  <Head :title="`Administrative Settings`"/>

  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div
        class="w-fit lg:w-3/4 left-0 right-0 mx-auto bg-white dark:bg-gray-800 rounded text-black dark:text-gray-50 mt-6 p-5">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <header>
        <div class="flex justify-between mb-3">
          <div class="mb-4">
            <h1 class="text-3xl font-semibold">Administrative Settings</h1>
          </div>
          <div>
            <div>
              <button
                  @click="appSettingStore.btnRedirect('/dashboard')"
                  class="bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
              >Dashboard
              </button>
            </div>
          </div>
        </div>
      </header>

      <ServerTime/>


      <div class="admin-container">
        <div class="sticky-sidebar">
          <button @click="appSettingStore.btnRedirect(`/admin/channels`)" class="action-button">Channels</button>
          <button @click="appSettingStore.btnRedirect(`/admin/schedule`)" class="action-button">Schedule</button>
          <button @click="appSettingStore.btnRedirect(`/invite_codes`)" class="action-button">Invite Codes</button>
          <a :href="mistServerUriForManagementInterface.replace(/\/$/, '') + ':4242'" target="_blank"
             class="action-button">MistServer MI</a>
          <button v-if="!secureNotesOpen" @click="openSecureNotes" class="action-button">Secure Notes</button>
          <button v-if="secureNotesOpen" @click="saveSecureNotes" class="action-button save-notes">Save Notes</button>
          <!-- Add the Schedule button here -->
        </div>
        <div v-if="!secureNotesOpen" class="main-actions">
          <div class="actions-group">
            <h3 class="group-title">Content Management</h3>
            <button @click="appSettingStore.btnRedirect(`/admin/episodes`)" class="action-button">All Episodes</button>
            <button @click="appSettingStore.btnRedirect(`/admin/shows`)" class="action-button">All Shows</button>
            <button @click="appSettingStore.btnRedirect(`/admin/movies`)" class="action-button">All Movies</button>
            <button @click="appSettingStore.btnRedirect(`/admin/images`)" class="action-button">Images</button>
            <button @click="appSettingStore.btnRedirect(`/videoupload`)" class="action-button">Video Upload</button>
            <button @click="appSettingStore.btnRedirect(`/movies/create`)" class="action-button">Add a Movie</button>
          </div>

          <div class="actions-group">
            <h3 class="group-title">User & Team Management</h3>
            <button @click="appSettingStore.btnRedirect(`/users`)" class="action-button">All Users</button>
            <button @click="appSettingStore.btnRedirect(`/admin/teams`)" class="action-button">All Teams</button>
          </div>

          <div class="actions-group">
            <h3 class="group-title">System & Backup</h3>
            <button @click="backupMistServerConfig" class="action-button">Backup MistServer Config</button>
            <button @click="restoreMistServerConfig" class="action-button">Restore MistServer Config</button>
            <button @click="restoreAllStreams" class="action-button">Restore All MistStreams</button>
            <button @click="getEpisodesFromEmbedCodes" class="action-button" :disabled="!getAllEpisodesButtonActive">Get
              All Episode Videos From Embed Codes
            </button>
          </div>

          <div class="actions-group">
            <h3 class="group-title">MistServer Management</h3>
            <button @click="appSettingStore.btnRedirect(`/admin/mistServerApi`)" class="action-button">MistServer API
              Test Page
            </button>
            <a :href="mistServerUriForManagementInterface.replace(/\/$/, '') + ':4242'" target="_blank"
               class="action-button">MistServer Management Interface</a>
          </div>
        </div>
        <div v-else class="main-actions">
          <TabbableTextarea v-if="!secureNotesSaving" :modelValue="secureNotes" @update:modelValue="updateSecureNotes" :placeholder="'Secure notes here'"
                            class="w-full h-64"/>
          <div v-if="secureNotesSaving"><span class="loading loading-bars loading-md"></span> Saving...</div>
        </div>
      </div>


      <!--      <div class="bg-gray-300 dark:bg-gray-900 rounded pb-8 p-3 mb-6 mx-2 border-b border-2">-->
      <!--        <div class="font-semibold text-xl pb-2">Administrator only links</div>-->
      <!--        <div class="flex flex-wrap md:flex-row justify-items-start gap-2">-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/users`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"-->
      <!--          >All Users-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/admin/episodes`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"-->
      <!--          >All Episodes-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/admin/shows`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"-->
      <!--          >All Shows-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/admin/movies`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"-->
      <!--          >All Movies-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/admin/teams`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"-->
      <!--          >All Teams-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/admin/channels`)"-->
      <!--              class="bg-orange-600 hover:bg-orange-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"-->
      <!--          >Channels-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/admin/mistServerApi`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"-->
      <!--          >MistServer API Test Page-->
      <!--          </button>-->
      <!--          <a-->
      <!--              :href="mistServerUriForManagementInterface.replace(/\/$/, '') + ':4242'" target="_blank"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"-->
      <!--          >MistServer Management Interface</a>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/admin/images`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"-->
      <!--          >Images-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/videoupload`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"-->
      <!--          >Video Upload-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/movies/create`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"-->
      <!--          >Add a Movie-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="appSettingStore.btnRedirect(`/invite_codes`)"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"-->
      <!--          >Invite Codes-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="backupMistServerConfig"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"-->
      <!--          >Backup MistServer Config-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="restoreMistServerConfig"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"-->
      <!--          >Restore MistServer Config-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="restoreAllStreams"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"-->
      <!--          >Restore All MistStreams-->
      <!--          </button>-->
      <!--          <button-->
      <!--              @click="getEpisodesFromEmbedCodes"-->
      <!--              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400 disabled:no-cursor"-->
      <!--              :disabled="!getAllEpisodesButtonActive"-->
      <!--          >Get All Episode Videos From Embed Codes-->
      <!--          </button>-->
      <!--          &lt;!&ndash;                    <Link&ndash;&gt;-->
      <!--          &lt;!&ndash;                        :href="`/admin/phpmyinfo`"><button&ndash;&gt;-->
      <!--          &lt;!&ndash;                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"&ndash;&gt;-->
      <!--          &lt;!&ndash;                    >phpinfo()</button>&ndash;&gt;-->
      <!--          &lt;!&ndash;                    </Link>&ndash;&gt;-->

      <!--        </div>-->

      <!--      </div>-->

      <div>
        <form @submit.prevent="submit">
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="default_country"
            >
              COUNTRY
            </label>
            <div class="text-xs mb-2">This should probably be baked into the code somewhere. Content, and especially
              News Stories, uploaded and managed to this instance of notTV is specific to this country. The system has a
              list of provinces, cities and postal codes based on the country. This system is designed to only manage
              one country. Eventually, each notTV country will be able to share their content and News Stories, with
              each other.
            </div>

            <select disabled id="default_country" v-model="form.default_country"
                    class="disabled:bg-gray-100 cursor-not-allowed">
              <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }}</option>
            </select>

            <div v-if="form.errors.default_country" v-text="form.errors.default_country"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cdn_endpoint"
            >
              CDN ENDPOINT
            </label>

            <input v-model="form.cdn_endpoint"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="cdn_endpoint"
                   id="cdn_endpoint"
            >
            <div v-if="form.errors.cdn_endpoint" v-text="form.errors.cdn_endpoint"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              CLOUD FOLDER
            </label>

            <div class="flex flex-row">
              <span class="pt-2 mr-2">/ </span>
              <input v-model="form.cloud_folder"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="cloud_folder"
                     id="cloud_folder"
              >
            </div>
            <span
                class="text-xs">NOTE: The forward slash is already entered in the backend. Just type the folder name.</span>

            <div v-if="form.errors.cloud_folder" v-text="form.errors.cloud_folder"
                 class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="mist_server_api_url"
            >
              MIST SERVER URI
            </label>
            <div class="text-xs mb-2">This is the primary mist server. Right now we don't have a load balancer. This URI
              is used for playback. The .env holds the settings for connecting to the MistServer API.
            </div>

            <div class="flex flex-row">
              <input v-model="form.mist_server_uri"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="mist_server_uri"
                     id="mist_server_uri"
              >
            </div>
            <div class="text-xs mb-2">e.g., https://mist.nottv.io/</div>

            <div v-if="form.errors.mist_server_uri" v-text="form.errors.mist_server_uri"
                 class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="mist_server_api_url"
            >
              MIST SERVER RTMP URI
            </label>
            <div class="text-xs mb-2">This is the address users will push their streams to.</div>

            <div class="flex flex-row">
              <input v-model="form.mist_server_rtmp_uri"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="mist_server_rtmp_uri"
                     id="mist_server_rtmp_uri"
              >
            </div>
            <div class="text-xs mb-2">e.g., rtmp://stream.not.tv/ or rtmp://localhost/</div>

            <div v-if="form.errors.mist_server_rtmp_uri" v-text="form.errors.mist_server_rtmp_uri"
                 class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="mb-6 border-t-2 pt-4">

            <div>
              <button @click.prevent="clearFirstPlayCacheData"
                      class="btn btn-warning btn-sm mb-2">
                Clear First Play Data Cache
              </button>
            </div>
            <div class="px-3 pb-2 text-sm font-semibold text-orange-400">Please close your browser window after clearing
              the cache, this only applies to you.
            </div>

            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              FIRST PLAY VIDEO SOURCE
            </label>

            <div class="flex flex-row">
              <input v-model="form.first_play_video_source"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="first_play_video_source"
                     id="first_play_video_source"
              >
            </div>
            <span class="text-xs">e.g., https://mist.nottv.io/hls/test/index.m3u8</span>

            <span class="text-xs"></span>

            <div v-if="form.errors.first_play_video_source" v-text="form.errors.first_play_video_source"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              FIRST PLAY VIDEO SOURCE TYPE
            </label>

            <div class="flex flex-row">
              <input v-model="form.first_play_video_source_type"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="first_play_video_source"
                     id="first_play_video_source"
              >
            </div>
            <span class="text-xs">e.g., video/mp4 or application/x-mpegURL</span>

            <div v-if="form.errors.first_play_video_source" v-text="form.errors.first_play_video_source"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              FIRST PLAY VIDEO NAME
            </label>

            <div class="flex flex-row">
              <input v-model="form.first_play_video_name"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="first_play_video_name"
                     id="first_play_video_name"
              >
            </div>
            <span class="text-xs"></span>

            <div v-if="form.errors.first_play_video_name" v-text="form.errors.first_play_video_name"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6 border-b-2 pb-4">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              CHANNEL ID
            </label>

            <div class="flex flex-row">
              <input v-model="form.first_play_channel_id"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="first_play_channel_id"
                     id="first_play_channel_id"
              >
            </div>
            <span class="text-xs text-orange-500">This needs to become a dropdown of channels to select.</span>

            <div v-if="form.errors.first_play_channel_id" v-text="form.errors.first_play_channel_id"
                 class="text-xs text-red-600 mt-1"></div>
          </div>


          <!--          <div class="mb-6">-->
          <!--            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"-->
          <!--                   for="mist_server_ip"-->
          <!--            >-->
          <!--              MIST SERVER IP ADDRESS-->
          <!--            </label>-->
          <!--            <div class="text-xs mb-2">This is used as part of the hash for Mist Stream Access Control.</div>-->

          <!--            <div class="flex flex-row">-->
          <!--              <input v-model="form.mist_server_ip"-->
          <!--                     class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
          <!--                     type="text"-->
          <!--                     name="mist_server_ip"-->
          <!--                     id="mist_server_ip"-->
          <!--              >-->
          <!--            </div>-->
          <!--            <div v-if="form.errors.mist_server_ip" v-text="form.errors.mist_server_ip"-->
          <!--                 class="text-xs text-red-600 mt-1"></div>-->
          <!--          </div>-->
          <!--          <div class="mb-6">-->
          <!--            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"-->
          <!--                   for="mist_server_api_url"-->
          <!--            >-->
          <!--              MIST SERVER API URL-->
          <!--            </label>-->

          <!--            <div class="flex flex-row">-->
          <!--              <input v-model="form.mist_server_api_url"-->
          <!--                     class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
          <!--                     type="text"-->
          <!--                     name="mist_server_api_url"-->
          <!--                     id="mist_server_api_url"-->
          <!--              >-->
          <!--            </div>-->
          <!--            <div class="text-xs mb-2">e.g., https://mist.nottv.io/api</div>-->

          <!--            <div v-if="form.errors.mist_server_api_url" v-text="form.errors.mist_server_api_url"-->
          <!--                 class="text-xs text-red-600 mt-1"></div>-->
          <!--          </div>-->
          <!--          <div class="mb-6">-->
          <!--            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"-->
          <!--                   for="mist_server_username"-->
          <!--            >-->
          <!--              MIST SERVER USERNAME-->
          <!--            </label>-->

          <!--            <div class="flex flex-row">-->
          <!--              <input v-model="form.mist_server_username"-->
          <!--                     class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
          <!--                     type="text"-->
          <!--                     name="mist_server_username"-->
          <!--                     id="mist_server_username"-->
          <!--              >-->
          <!--            </div>-->
          <!--            <div v-if="form.errors.mist_server_username" v-text="form.errors.mist_server_username"-->
          <!--                 class="text-xs text-red-600 mt-1"></div>-->
          <!--          </div>-->
          <!--          <div class="mb-6">-->
          <!--            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"-->
          <!--                   for="mist_server_password"-->
          <!--            >-->
          <!--              MIST SERVER PASSWORD-->
          <!--            </label>-->

          <!--            <div class="flex flex-row">-->
          <!--              <input v-model="form.mist_server_password"-->
          <!--                     class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
          <!--                     type="password"-->
          <!--                     name="mist_server_password"-->
          <!--                     id="mist_server_password"-->
          <!--              >-->
          <!--            </div>-->
          <!--            <div v-if="form.errors.mist_server_password" v-text="form.errors.mist_server_password"-->
          <!--                 class="text-xs text-red-600 mt-1"></div>-->
          <!--          </div>-->

          <!--          <div class="mb-6">-->
          <!--            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"-->
          <!--                   for="mist_server_password"-->
          <!--            >-->
          <!--              MIST ACCESS CONTROL SECRET-->
          <!--            </label>-->
          <!--            <div class="text-xs mb-2">A string used to create the access control urls for playing streams and videos from the mist server.</div>-->
          <!--            <div class="flex flex-row">-->
          <!--              <input v-model="form.mist_access_control_secret"-->
          <!--                     class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
          <!--                     type="text"-->
          <!--                     name="mist_access_control_secret"-->
          <!--                     id="mist_access_control_secret"-->
          <!--              >-->
          <!--            </div>-->
          <!--            <div v-if="form.errors.mist_access_control_secret" v-text="form.errors.mist_access_control_secret"-->
          <!--                 class="text-xs text-red-600 mt-1"></div>-->
          <!--          </div>-->

          <div class="flex justify-end my-6 mr-6">
            <JetValidationErrors class="mr-4"/>
            <button
                @click.prevent="submit"
                class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                :disabled="form.processing"
            >
              Save
            </button>
          </div>

        </form>

        <div class="card w-full mx-6 bg-neutral text-neutral-content">
          <div class="card-body items-center text-center">
            <h2 class="card-title">Pushing to Production!</h2>
            <div class="mockup-code break-words">
              <pre data-prefix="$"><code>git checkout staging</code></pre>
              <pre data-prefix="$"><code>rm -rf public/js/*</code></pre>
              <pre data-prefix="$"><code>rm -rf public/css/*</code></pre>
              <pre data-prefix="$"><code>git merge development</code></pre>
              <pre data-prefix="$"><code>npm run production</code></pre>
              <pre data-prefix="$"><code>git add .</code></pre>
              <pre data-prefix="$"><code>git commit -m "merge development build production"</code></pre>
              <pre data-prefix="$"><code>git push origin</code></pre>
              <pre data-prefix="$"><code>git checkout development</code></pre>
              <div role="alert" class="mt-5 alert alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     class="stroke-current shrink-0 w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>You may have to clear the cache on Cloudflare if you don't see all of the changes.</span>
              </div>
            </div>

          </div>
        </div>

        <div class="mt-8 w-full">
          <h2 class="ml-4 mb-3">Tasks To Do:</h2>
          <div class="mb-4">
            <ol class="list-decimal pl-5 space-y-2">
              <li class="bg-amber-800 text-white p-2 rounded-md shadow">
                <input type="checkbox" class="mr-2 checkbox checkbox-lg"/>
                <span>Change Max Video Upload Size. Convert Bytes to KB, MB, GB, TB.</span>
                <ul class="list-disc pl-4 mt-1 text-sm">
                  <li>Update Video Uploader with the size.</li>
                </ul>
              </li>
              <li class="bg-amber-800 text-white p-2 rounded-md shadow"><input type="checkbox"
                                                                               class="mr-2 checkbox checkbox-lg"/>Content
                licenses
              </li>
              <li class="bg-amber-800 text-white p-2 rounded-md shadow"><input type="checkbox"
                                                                               class="mr-2 checkbox checkbox-lg"/>Episode
                Statuses
              </li>
              <li class="bg-amber-800 text-white p-2 rounded-md shadow"><input type="checkbox"
                                                                               class="mr-2 checkbox checkbox-lg"/>Show
                Statuses
              </li>
              <li class="bg-amber-800 text-white p-2 rounded-md shadow">
                <input type="checkbox" class="mr-2 checkbox checkbox-lg"/>
                <span>Channels. Display list of channels. Click channel name to go to the channel playlist edit page.</span>
                <div class="mt-2 text-sm">
                  <p>Grid style, 1 column mobile, 2 column tablet, 3 column lg, 4 column xl.</p>
                  <p>Rows: Times (next 24 hours) - can scroll up to 72 hours ahead or 72 hours back.</p>
                  <p>Columns: Days (next 7 days).</p>
                  <p>Need new Pages folder: Channels/Index, Channels/Create, Channels/Edit, ch/channelId (e.g.,
                    ch/01).</p>
                </div>
              </li>
            </ol>
          </div>
        </div>

      </div>

    </div>
  </div>
  <PopUpModal id="AdminSettingsPopUpModal">
    <template #header>
      {{ popUpModalTitle }}
    </template>
    <template #main>
      {{ popUpModalMessage }}
    </template>
  </PopUpModal>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { nextTick, onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import ServerTime from '@/Components/Pages/Admin/ServerTime'
import Message from '@/Components/Global/Modals/Messages'
import PopUpModal from '@/Components/Global/Modals/PopUpModal.vue'
import TabbableTextarea from '@/Components/Global/TextEditor/TabbableTextarea.vue'

usePageSetup('admin.settings')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  id: Number,
  countries: Object,
  default_country: Number,
  cdn_endpoint: String,
  cloud_folder: String,
  first_play_video_source: String,
  first_play_video_source_type: String,
  first_play_video_name: String,
  first_play_channel_id: String,
  // mist_server_ip: String,
  mist_server_uri: String,
  mist_server_rtmp_uri: String,
  // mist_server_api_url: String,
  // mist_server_username: String,
  // mist_server_password: String,
  // mist_access_control_secret: String,
  messageType: String,
})

let form = useForm({
  id: props.id,
  default_country: props.default_country,
  cdn_endpoint: props.cdn_endpoint,
  cloud_folder: props.cloud_folder,
  first_play_video_source: props.first_play_video_source,
  first_play_video_source_type: props.first_play_video_source_type,
  first_play_video_name: props.first_play_video_name,
  first_play_channel_id: props.first_play_channel_id,
  // mist_server_ip: props.mist_server_ip,
  mist_server_uri: props.mist_server_uri,
  mist_server_rtmp_uri: props.mist_server_rtmp_uri,
  // mist_server_api_url: props.mist_server_api_url,
  // mist_server_username: props.mist_server_username,
  // mist_server_password: props.mist_server_password,
  // mist_access_control_secret: props.mist_access_control_secret,
})

const countries = ref([])

const popUpModalTitle = ref('')
const popUpModalMessage = ref('')

// const topDiv = document.getElementById("topDiv")

onMounted(() => {
  countries.value = props.countries
})

nextTick(() => {
  if (topDiv) {
    const topDiv = document.getElementById('topDiv')
    topDiv.scrollIntoView()
  }
})

const secureNotesOpen = ref(false)
const secureNotes = ref('')
const secureNotesSaving = ref(false)

const openSecureNotes = async () => {
  secureNotesOpen.value = true
  // Fetch the encrypted notes from the server
  try {
    const response = await axios.get('/admin/secure-notes');
    secureNotes.value = response.data.content; // Assuming the server sends back decrypted data
  } catch (error) {
    console.error("Failed to fetch secure notes:", error);
    // Handle error (e.g., show a message to the user)
  }
}

const updateSecureNotes = (modelValue) => {
  secureNotes.value = modelValue
}

const saveSecureNotes = async () => {
  try {
    secureNotesSaving.value = true
    await axios.put('/admin/secure-notes', { content: secureNotes.value });
    secureNotesOpen.value = false
    secureNotesSaving.value = false
  } catch (error) {
    console.error("Failed to save secure notes:", "An error occurred while saving the notes."); // Log a generic message
    // Optionally log the detailed error server-side, ensuring sensitive information is not exposed
    // Inform the user in a generic manner
    secureNotesOpen.value = false
    secureNotesSaving.value = false
    alert("We encountered an issue saving your notes. Please try again.");
  }
}

let submit = () => {
  form.patch(route('admin.settings'))
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
}

let clearFirstPlayCacheData = () => {
  Inertia.post(route('admin.clear-first-play-data-cache'))
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
}

let getAllEpisodesButtonActive = ref(false)

function getEpisodesFromEmbedCodes() {
  Inertia.post('getVideosFromEmbedCodes')
  getAllEpisodesButtonActive = false
}

const backupMistServerConfig = async () => {
  // Optional: use confirm dialog to ensure the user wants to proceed with the backup
  if (confirm('Are you sure you want to backup the current Mist Server Config?')) {
    try {
      // Post request to initiate config backup
      const response = await axios.post('/mist-server/config-backup')
      console.log('Config backup successful:', response.data)
      popUpModalTitle.value = 'Backup MistServer Config Success'
      popUpModalMessage.value = response.data.message
      document.getElementById('AdminSettingsPopUpModal').showModal()
    } catch (error) {
      console.error('Error backing up config:', error)
      // Handle error, maybe notify the user that backup failed
      popUpModalTitle.value = 'Backup MistServer Config Error'
      popUpModalMessage.value = error
      document.getElementById('AdminSettingsPopUpModal').showModal()
    }
  } else {
    console.log('Config backup cancelled.')
  }
}


const restoreMistServerConfig = async () => {
  // Use confirm dialog result to decide whether to proceed
  if (confirm('Are you sure you want to restore the Mist Server Config to the last saved version?')) {
    try {
      // Assuming there's a separate API endpoint or function for restoration
      const response = await axios.post('/mist-server/config-restore') // Adjust URL/path as necessary
      console.log('Config restoration successful:', response.data)
      popUpModalTitle.value = 'Restore MistServer Config Success'
      popUpModalMessage.value = response.data.message
      document.getElementById('AdminSettingsPopUpModal').showModal()
      // Handle success response
    } catch (error) {
      console.error('Error restoring config:', error)
      popUpModalTitle.value = 'Restore MistServer Config Error'
      popUpModalMessage.value = error
      document.getElementById('AdminSettingsPopUpModal').showModal()
      // Handle error
    }
  } else {
    console.log('Config restoration cancelled.')
  }
}

async function restoreAllStreams() {
  try {
    const response = await axios.post('/admin/mist-stream/restore-all-streams')
    // Handle success response
    console.log(response.data) // Log or handle the response data
    popUpModalTitle.value = 'Streams restored successfully.'
    popUpModalMessage.value = response.data.message
    document.getElementById('AdminSettingsPopUpModal').showModal()
  } catch (error) {
    console.error(error) // Log or handle error
    popUpModalTitle.value = 'Failed to restore streams.'
    popUpModalMessage.value = error
    document.getElementById('AdminSettingsPopUpModal').showModal()
  }
}


// Sample URL
const mistServerUri = props.mist_server_uri ?? ''

// Function to remove 's' from 'https'
function convertToHttp(url) {
  return url.replace('https://', 'http://')
}

// Convert the URL and store it
const mistServerUriForManagementInterface = ref(convertToHttp(mistServerUri))


</script>
<style scoped>
.admin-container {
  display: flex;
}

.sticky-sidebar {
  flex-basis: 200px;
  position: sticky;
  top: 0; /* Adjust based on header/nav height */
  height: 100vh;
  padding: 20px;
  background-color: #f7fafc;
}

.action-button {
  display: block;
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  background-color: #4a5568; /* Tailwind gray-700 */
  color: white;
  text-align: left;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.action-button:hover {
  background-color: #2d3748; /* Tailwind gray-800 */
}

.action-button.save-notes {
  background-color: #1D4ED8; /* Tailwind blue-700 */
}

.action-button.save-notes:hover {
  background-color: #1E40AF; /* Tailwind blue-800 */
}

.main-actions {
  flex-grow: 1;
  padding: 20px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .admin-container {
    flex-direction: column;
  }

  .sticky-sidebar {
    position: relative;
    height: auto;
  }
}

.actions-group {
  margin-bottom: 20px;
}

.group-title {
  margin-bottom: 10px;
  color: #4a5568; /* Tailwind gray-700 */
  font-size: 1.25rem; /* Tailwind text-lg */
}

.action-button {
  display: block;
  margin-bottom: 8px; /* Adjust spacing between buttons */
  /* Other styles remain the same */
}

</style>