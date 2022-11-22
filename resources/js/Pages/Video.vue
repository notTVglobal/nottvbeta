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

            <div class="">Status: <span class="font-semibold">{{ videoPlayer.status }}</span></div>

            <button class="ml-2 py-2 my-2 px-4 text-white bg-orange-800 hover:bg-orange-500 mr-2 rounded-xl" @click.prevent="getStatus">
                Get Status
            </button>

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
                    <input class="mb-2" type="text" name="username" v-model="form.username" />

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

                <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 mr-2 rounded-xl" @click.prevent="getMistStats">
                    Get Server Stats
                </button>

                <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl" @click.prevent="getActiveStreams">
                    Get Active Streams
                </button>

                <div class="mt-2">Active Streams:</div>
                <div class="">
                    {{videoPlayer.apiActiveStreams}}
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

videoPlayer.apiActiveStreams = null;

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    apiReturn: Object,
    message: ref(String),
    mistNewHashedPassword: ref(String),
});

let form = reactive({
    challenge: videoPlayer.challenge,
    status: videoPlayer.status,
    username: '',
    password: '',
})

const password = ref('');

let md5 = require('md5');


////////////////////  MIST SERVER ADDRESS //////////////////////////////
// Keep this here to change which MistServer is used for testing purposes
//
// let mistAddress = 'http://localhost:4242/api'
let mistAddress = 'https://beta-staging.not.tv/mistserver/api'
// let mistAddress = 'http://mist.nottv.io:4242/api'
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

async function authenticateMistServer() {
    let hashedPassword = md5(form.password)
    console.log("Hashed password: " + hashedPassword);
    let authReturn = md5(hashedPassword+videoPlayer.challenge)
    console.log("Final hashed password: " + authReturn)
    await axios.get(mistAddress+'?command=%7B%0A%22authorize%22%3A%20%7B%0A%22username%22%3A%20%22'+form.username+'%22,%0A%22password%22%3A%20%22'+authReturn+'%22%0A%7D%0A%7D')
        .then(response => {
            videoPlayer.apiRequest = response.data;
            videoPlayer.challenge = videoPlayer.apiRequest.authorize.challenge;
            videoPlayer.status = videoPlayer.apiRequest.authorize.status;
            console.log(response.data);
        })
        .catch(error => {
            console.log(error);
        })
    console.log('mistServer API authorization sent.');
}

async function getMistStats() {
    await axios.get(mistAddress+'?command=', {"capabilities": "true"})
        .then(response => {
            videoPlayer.apiRequest = response.data;
            videoPlayer.challenge = videoPlayer.apiRequest.authorize.challenge;
            videoPlayer.status = videoPlayer.apiRequest.authorize.status;
        })
        .catch(error => {
            console.log(error);
        })
    console.log('Get MistServer Stats');
}

async function getActiveStreams(password) {
    // await axios.get(mistAddress+'?command=%7B%0A%22minimal%22%3A%20%221%22,%0A%22active_streams%22%3A%20%22true%22%0A%7D')
    await axios.get(mistAddress+'?command=%7B%20%22authorize%22%3A%20%7B%0A%20%20%20%20%22'+form.username+'%22%3A%20%22USERNAME%22,%0A%20%20%20%20%22'+password+'%22%3A%20%22PASSWORD%22%0A%20%20%20%20%7D,%0A%7B%20%22minimal%22%3A%201%20%7D,%0A%7B%20%22active_streams%22%3A%20true%20%7D%0A%7D')
        .then(response => {
            videoPlayer.apiActiveStreams = response.data
            videoPlayer.apiRequest = response.data;
            videoPlayer.challenge = videoPlayer.apiRequest.authorize.challenge;
            videoPlayer.status = videoPlayer.apiRequest.authorize.status;
        })
        .catch(error => {
            console.log(error);
        })
    console.log('mistServer API request sent. Get Active Streams.');
}

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
//     },
// { "minimal": 1 },
// { "active_streams": true }
// }

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




