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

            <div class="">Status: {{ videoPlayer.status }}</div>

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

                    <button class="ml-2 py-2 px-4 text-white bg-green-800 hover:bg-green-500 rounded-xl" @click.prevent="submit">
                        Submit
                    </button>

                </form>

            </div>

            <div v-if="videoPlayer.status === 'OK'" class="mb-8">
                <div  class="py-3 px-4 mb-4 bg-green-900 text-white rounded">MistServer is connected</div>

                <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 mr-2 rounded-xl" @click="getMistStats">
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

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    apiReturn: Object,
    message: ref(String),
    mistNewHashedPassword: ref(String),
});


// const challengeValue = ref(videoPlayer.challenge)
// const statusValue = ref('')

let form = reactive({
    challenge: videoPlayer.challenge,
    status: videoPlayer.status,
    username: '',
    password: '',
})

const password = ref('');

let md5 = require('md5');
// console.log(md5(props.message));


// let mistAddress = 'http://localhost:4242/api'
// let mistAddress = 'https://beta-staging.not.tv/mistserver/api'
let mistAddress = 'http://mist.nottv.io:4242/api'



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
    console.log('get API');
}

let setMistHashedPassword = () => {
    if (props.message) {
        props.mistNewHashedPassword = md5(props.message);
    }
}

setMistHashedPassword();
// axios.get('http://mist.nottv.io:4242/api').then(console.log);

// tec21: Either we get one of these 3 options working,
// or we create a web application that sits on top
// of the MistServer and sends API responses to the
// main server when new videos are created,
// and it will need to receive a notification
// to setup new streams when new shows are created
// and when new creators register.

async function getStatus() {
    // this one is the localhost for the staging server.
    //
    // await axios.get('https://beta-staging.not.tv/mistserver/api')
        // this one sasys CORS Preflight did not succeed.
        // Cross-Origin Request Blocked. The Same Origin
        // Policy disallows reading the remote resource.
        //
    // await axios.get('https://mist.not.tv/mistserver/api')
        //
        //
        // this one returns a challenge response and
        // I haven't been able to get through the
        // authorization with a JSON request.
        // The mistserver just keeps returning
        // a challenge response.
        //
    await axios.get(mistAddress)
        //
        //
        // this one works, but it has to be installed
        // on the same machine as the webserver.
        // in production, this presents a problem
        // as not.tv needs to be served over HTTPS.
        //
    // await axios.get('http://localhost:4242/api')
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

function submit() {
    let hashedPassword = md5(form.password)
    console.log("Hashed password: " + hashedPassword);
    let authReturn = md5(hashedPassword+videoPlayer.challenge)
    console.log("Final hashed password: " + authReturn)
    authenticateMistServer(authReturn);

    // Inertia.post(route('mistApi', {challenge: videoPlayer.challenge, password: password}));
    // Inertia.post(route('mistApi', {challenge: videoPlayer.challenge, password: hashedPassword}));
}


// This is a properly formatted HTTP API call to the MistServer:
//
// {
//     "addstream": {
//     "streamname_here": {},
// }
// }{}

videoPlayer.apiActiveStreams = null;

// tec21: this returns the pattern that mistServer is apparently looking for.
async function authenticateMistServer(password) {
    await axios.get(mistAddress+'?command=%7B%0A%22authorize%22%3A%20%7B%0A%22username%22%3A%20%22'+form.username+'%22,%0A%22password%22%3A%20%22'+password+'%22%0A%7D%0A%7D')
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

async function getActiveStreams() {
    await axios.get(mistAddress+'?command=%7B%0A%22minimal%22%3A%20%221%22,%0A%22active_streams%22%3A%20%22true%22%0A%7D')
        .then(response => {
            videoPlayer.apiActiveStreams = response.data
        })
        .catch(error => {
            console.log(error);
        })
    console.log('mistServer API request sent. Get Active Streams.');
}


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




