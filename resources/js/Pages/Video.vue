<template>

    <Head title="MistServer API" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-6">
                <div class="grid grid-cols-1 grid-rows-2">
                    <h1 class="text-3xl font-semibold">MistServer API</h1>
                </div>
                <span class="text-xs font-semibold text-red-700">Admin Mode</span>
                <div class="grid grid-cols-1 grid-rows-2">
                    <div class="justify-self-end mb-4">
                        <Link :href="`/dashboard`"><button
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Dashboard</button>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <div>
                    <div class="">Status: <span class="font-semibold">{{ videoPlayer.status }}</span></div>

                    <button class="ml-2 py-2 my-2 px-4 text-white bg-orange-800 hover:bg-orange-500 mr-2 rounded-xl" @click.prevent="getStatus">
                        Get Status
                    </button>

                    <button v-if="videoPlayer.status === 'OK'"
                            class="ml-2 py-2 my-2 px-4 text-white bg-green-800 hover:bg-green-500 mr-2 rounded-xl"
                            @click.prevent="displayPushForm">
                        Start Push
                    </button>
                </div>
                <div v-if="videoPlayer.status === 'OK'" class="">

                </div>

            </div>

            <div v-if="videoPlayer.status === 'CHALL'" class="mb-8">
                <div  class="py-3 px-4 mb-4 bg-orange-800 text-white rounded">MistServer needs to be authenticated</div>

                <div class="font-semibold text-2xl px-2">
                    Connect to the MistServer
                </div>

                <div class="my-3 pl-2 text-sm w-1/2">If the MistServer Status will either be OK, CHALL, NOACC or ACC_MADE.
                    If it's "CHALL" then you need to re-authenticate with the username and password.</div>

                <form @submit.prevent class="mt-2 pl-2">

                    <div class="mt-2">Challenge:</div>
                    <input type="text" name="challenge" id="challenge" v-model="videoPlayer.challenge" disabled/>

                    <div class="font-semibold mt-2">MistServer Username:</div>
                    <input class="mb-2" type="text" name="username" v-model="videoPlayer.mistUsername" />

                    <div class="font-semibold mt-2">MistServer Password:</div>
                    <input type="password" name="password" v-model="form.password" />

                    <div class="mb-4 w-1/2 text-sm"> Credit to Jeff Mott for his work on a pure JS implementation of the MD5 algorithm.
                        You can find the npm package <a href="https://www.npmjs.com/package/md5" target="_blank" class="text-blue-800 hover:text-gray-500">here.</a></div>

                    <button class="ml-2 py-2 px-4 text-white bg-green-800 hover:bg-green-500 rounded-xl" @click.prevent="authenticateMistServer">
                        Authenticate
                    </button>

                </form>

            </div>

            <div v-if="videoPlayer.status === 'OK'" class="mb-8">
                <div  class="py-3 px-4 mb-4 bg-green-900 text-white rounded">MistServer is connected</div>

                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="flex flex-col w-1/2 space-y-2">

                        <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl" @click.prevent="checkUpdates">
                           Check for Updates
                        </button>

                        <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl" @click.prevent="getCapabilities">
                            Get Server Capabilities
                        </button>

                        <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 mr-2 rounded-xl" @click.prevent="getTotals">
                            Get Totals
                        </button>

                        <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 mr-2 rounded-xl" @click.prevent="getClients">
                            Get Clients
                        </button>

                        <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl" @click.prevent="getActiveStreams">
                            Get Active Streams
                        </button>

                        <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl" @click.prevent="getLog">
                            Get Log
                        </button>

                        <button class="ml-2 py-2 px-4 text-white bg-orange-800 hover:bg-orange-500 rounded-xl" @click.prevent="clearLog">
                            Clear Log
                        </button>

                        <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl" @click.prevent="browseRecordings">
                            Recordings
                        </button>

                    </div>
                    <div>
                        <div v-if="videoPlayer.mistStatus">
                            <div class="mt-2">Returned data:</div>
                            <div class="">
                                {{videoPlayer.apiResponse}}
                            </div>
                        </div>
                        <div v-if="videoPlayer.mistDisplayPushForm">
                            <div class="font-semibold my-2">Push a Stream:</div>
                            <div class="">
                                <label for="streamName" class="mb-1">Choose stream:</label>
                                <select
                                    id="streamName"
                                    v-model="streamName"
                                    class="w-full mb-2" >
                                    <option v-for="stream in videoPlayer.apiResponse.active_streams" :key=stream>
                                        {{ stream }}
                                    </option>
                                </select>
                                <label for="rtmpDestination" class="mb-1">Set destination:</label>
                                <input type="text"
                                       id="rtmpDestination"
                                       v-model="rtmpDestination"
                                       placeholder="RTMP destination..."
                                       class="w-full my-2">
                                <button @click.prevent="startPush"
                                        class="ml-2 py-2 px-4 text-white bg-green-800 hover:bg-green-500 rounded-xl">
                                    Push
                                </button>
                                <video src="https://streams.not.tv/vmixsource03.mp4" class="mt-20 w-96" controls></video>
                                <br>Preview video is hardcoded to "vmixsource03"
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import {useChatStore} from "@/Stores/ChatStore";
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import { onMounted, ref, watch, reactive } from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.apiActiveStreams = null
videoPlayer.mistStatus = false

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});


let props = defineProps({
    apiReturn: Object,
    message: ref(String),
    mistNewHashedPassword: ref(String),
    streamName: ref(),
    rtmpDestination: ref()
});

let form = reactive({
    challenge: videoPlayer.challenge,
    status: videoPlayer.status,
    username: '',
    password: '',
})

videoPlayer.mistUsername = 'nottvadmin';
form.password = '20y$!PwX12S';

const password = ref('');

let md5 = require('md5');


////////////////////  MIST SERVER ADDRESS //////////////////////////////
// Keep this here to change which MistServer is used for testing purposes
//
// let mistAddress = 'http://localhost:4242/api'
// let mistAddress = 'https://beta-staging.not.tv/mistserver/api'
let mistAddress = 'https://mist.not.tv/'
// let mistAddress = 'http://mist.nottv.io:4242/api'
// let mistAddressWs = 'ws://mist.nottv.io:4242/ws'
//
///////////////////////////////////////////////////////////////////////



async function getStatus() {
    await axios.get(mistAddress)
        .then(response => {
            videoPlayer.apiRequest = response.data;
            videoPlayer.challenge = videoPlayer.apiRequest.authorize.challenge;
            videoPlayer.status = videoPlayer.apiRequest.authorize.status;
        })
        .catch(error => {
            console.log(error);
        })
    console.log('get API');

}

// The Websocket connection will give us near real-time
// info from the server.
//
// Create the header for the MistServer WS API Request
// const mistWsHeader = {
//     headers: {
//         "Authorization": "json "+AuthenticationGoesHere,
//     },
// };

async function authenticateMistServer() {
    let hashedPassword = md5(form.password)
    console.log("Hashed password: " + hashedPassword)
    let authReturn = md5(hashedPassword+videoPlayer.challenge)
    videoPlayer.mistPassword = authReturn
    console.log("Final hashed password: " + authReturn)
    await axios.get(mistAddress+'?command=%7B%0A%22authorize%22%3A%20%7B%0A%22username%22%3A%20%22'+videoPlayer.mistUsername+'%22,%0A%22password%22%3A%20%22'+authReturn+'%22%0A%7D%0A%7D')
        .then(response => {
            videoPlayer.apiRequest = response.data
            videoPlayer.challenge = videoPlayer.apiRequest.authorize.challenge
            videoPlayer.status = videoPlayer.apiRequest.authorize.status
            console.log(response.data);
        })
        .catch(error => {
            console.log(error)
        })
    console.log('mistServer API authorization sent.');

}

let checkUpdates = () => {
    let request = "\"update\": true"
    getApi(request)
}

let getCapabilities = () => {
    let request = "%22capabilities%22%3A%20true"
    getApi(request)
}

let getTotals = () => {
    let request = "\"totals\": {}"
    getApi(request)
}

let getActiveStreams = () => {
    let request = "\"active_streams\": true"
    getApi(request)
}

let getClients = () => {
        // This request delivers information about each client connected
        // to a specific stream name.
        //
    // let request = "\"clients\": [{\"streams\": [\"vmixsource03\"],},{}]}"

    // This request delivers all clients
    let request = "\"clients\": {}"
    getApi(request)
}

let getLog = () => {
    let request = "\"log\": {}"
    getApi(request)
}

let clearLog = () => {
    let request = "\"clearstatlog\": true"
    getApi(request)
}

let browseRecordings = () => {
    let request = "\"path\": \"/media/upload\""
    getApi(request)
}

async function getApi(request) {
    videoPlayer.mistStatus = true
    videoPlayer.mistDisplayPushForm = false
    // let apiRequest = '%7B%20%22authorize%22%3A%20%7B%0A%20%20%20%20%22username%22%3A%20%22'+videoPlayer.mistUsername+'%22,%0A%20%20%20%20%22password%22%3A%20%22'+videoPlayer.mistPassword+'%22%0A%20%20%20%7D,%0A%20'+request+'%0A%7D'
    let apiRequest = '%7B%20%22authorize%22%3A%20%7B%0A%20%20%20%20%22username%22%3A%20%22'+videoPlayer.mistUsername+'%22,%0A%20%20%20%20%22password%22%3A%20%22'+videoPlayer.mistPassword+'%22%0A%20%20%20%7D,%0A%20'+request+'%0A%0A%7D'
        await axios.get(mistAddress+'?command='+apiRequest)
            .then(response => {
                videoPlayer.apiResponse = response.data;
                videoPlayer.challenge = videoPlayer.apiResponse.authorize.challenge;
                videoPlayer.status = videoPlayer.apiResponse.authorize.status;
            })
            .catch(error => {
                console.log(error);
            })
    console.log('mistServer API request sent.');
}

// Create method to push a stream somewhere
//

videoPlayer.mistStatus = true
videoPlayer.mistDisplayPushForm = false
function displayPushForm() {
    getActiveStreams()
    videoPlayer.mistStatus = false
    videoPlayer.mistDisplayPushForm = true
}
function startPush() {
    // api call to mist server.
    // "push_start":["STREAMNAME", "URI"]
    // let request = "\"push_start\":[\""+props.streamName+", \""+props.rtmpDestination+"\"]"
    let request = "{\"push_start\":{\"stream\":\""+props.streamName+",\"target\": \""+props.rtmpDestination+"\"}}"
    // setTimeout(() => {  getApi(request); console.log("World!"); }, 2000);
    getApi(request)
    videoPlayer.mistStatus = false
    videoPlayer.mistDisplayPushForm = true
    // log output
    console.log("stream push started: " + request)
}
//


// let setMistHashedPassword = () => {
//     if (props.message) {
//         props.mistNewHashedPassword = md5(props.message);
//     }
// }
//
// setMistHashedPassword();



/////////////// EXAMPLES OF MISTSERVER API CALLS ///////////////
//
// Keep these here. The MistServer API Call needs to be Url Encoded.
//
// This is a properly formatted HTTP API call to the MistServer:
//
// {
//     "addstream": {
//     "streamname_here": {},
// }
// }

// { "authorize": {
//     "username": "USERNAME",
//     "password": "PASSWORD"
//     }
// },
// { "minimal": 1 },
// { "active_streams": true }
//
// https://beta-staging.not.tv/mistserver/api?command=%7B%20%22authorize%22%3A%20%7B%0A%20%20%20%20%22username%22%3A%20%22USERNAME%22,%0A%20%20%20%20%22password%22%3A%20%22PASSWORD%22%0A%20%20%20%20%7D%0A%7D,%0A%7B%20%22minimal%22%3A%201%20%7D,%0A%7B%20%22active_streams%22%3A%20true%20%7D
// https://beta-staging.not.tv/mistserver/api?command=%7B%20%22authorize%22%3A%20%7B%0A%20%20%20%20%22username%22%3A%20%22USERNAME%22,%0A%20%20%20%20%22password%22%3A%20%22PASSWORD%22%0A%20%20%20%20%7D,%0A%7B%20%22minimal%22%3A%201%20%7D,%0A%7B%20%22active_streams%22%3A%20true%20%7D%0A%7D

//
///////////////////////////////////////////////////////////////








// {
//     "authorize":
//     {
// //Username to login as
//         "username":
//         "test",
// //Hash of password to login with. Send empty value when no challenge for the hash is known yet.
// //When the challenge is known, the value to be used here can be calculated as follows:
// // MD5( MD5("secret") + challenge)
// //Where "secret" is the plaintext password.
//             "password": ""
//     }
// }


// tec21: this returns the pattern that mistServer is apparently looking for.
// let submit = () => {
//     console.log(form.response);
//     Inertia.post(route('mistApi', {authorization: {challenge: videoPlayer.challenge, status: videoPlayer.status}}));
// }

// tec21: this works... it returns $request->challenge and $request->status to Laravel
// let submit = () => {
//     console.log('sent to backend');
//     Inertia.post(route('mistApi', {challenge: videoPlayer.challenge, status: videoPlayer.status}));
// }




</script>


