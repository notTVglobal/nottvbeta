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

            <div v-if="videoPlayer.status === 'CHALL'" class="mb-8">
                <div  class="py-3 px-4 mb-4 bg-orange-800 text-white rounded">MistServer needs to be authenticated</div>
            </div>

            <div v-if="videoPlayer.status === 'OK'" class="mb-8">
                <div  class="py-3 px-4 mb-4 bg-green-900 text-white rounded">MistServer is connected</div>

                <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 mr-2 rounded-xl" @click="getMistStats">
                    Get Server Stats
                </button>

            </div>






            <div class="bg-orange-300 px-2">
           Connect to the MistServer API here.
            </div>

<div class="my-3 text-sm w-1/2">If the MistServer Status will either be OK, CHALL, NOACC or ACC_MADE.
If it's "CHALL" then you need to re-authenticate with the username and password.</div>






<!--            if the response contains status: CHALL -->
<!--            then make another request with the response -->
<!--            and the md hashed sums together... this will -->
<!--            have to be done in the controller. -->



            <form @submit.prevent class="mt-2">
                <div class="">Status: </div>
                <input type="text" name="status" v-model="videoPlayer.status" disabled/>
                <button class="ml-2 py-2 px-4 text-white bg-orange-800 hover:bg-orange-500 mr-2 rounded-xl" @click.prevent="getStatus">
                    Get Status
                </button>
                <div class="mt-2">Challenge:</div>
                <input type="text" name="challenge" id="challenge" v-model="videoPlayer.challenge" disabled/>
                <div class="font-semibold mt-2">MistServer Username:</div>
                <input class="mb-2" type="text" name="username" v-model="videoPlayer.mistUsername" />
                <div class="font-semibold mt-2">MistServer Password:</div>
                <input type="password" name="password" v-model="password" />
                <button class="ml-2 py-2 px-4 text-white bg-green-800 hover:bg-green-500 rounded-xl" @click.prevent="submit(password)">
                    Submit
                </button>
            </form>

            <div class="mt-2">The MD5 Hashed Password will go here:</div>

        <div class="flex mb-2">
            <input type="text" v-model="message" class="" disabled />
        </div>
        <div class="mt-4 mb-2 text-sm w-1/2 text-orange-600"><span class="font-semibold">NOTE:</span> The MD5 Hashed password is currently stored as a prop. This is not secure.</div>
        <div class="mb-4 w-1/2 text-sm"> Credit to Jeff Mott for his work on a pure JS implementation of the MD5 algorithm.
            You can find the npm package <a href="https://www.npmjs.com/package/md5" target="_blank" class="text-blue-800 hover:text-gray-500">here.</a></div>


            <button class="ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl" @click.prevent="getApi">
                Get API
            </button>

            <div class="mt-2">The API reply is:</div>
            <textarea type="text" v-model="videoPlayer.apiResponse" class="" disabled />
            <div class="">
                {{videoPlayer.apiResponse}}
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
});


// const challengeValue = ref(videoPlayer.challenge)
// const statusValue = ref('')

let form = reactive({
    challenge: videoPlayer.challenge,
    status: videoPlayer.status,
})

const password = ref('');

let md5 = require('md5');
console.log(md5('message'));


async function getMistStats() {
    await axios.get('https://beta-staging.not.tv/mistserver/api?command=', {"capabilities": "true"})
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
    await axios.get('https://beta-staging.not.tv/mistserver/api')
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
    // await axios.get('http://mist.nottv.io:4242/api')
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

function submit(password) {
    let hashedPassword = md5(password);
    console.log('sent to backend');
    Inertia.post(route('mistApi', {challenge: videoPlayer.challenge, password: hashedPassword}));
}




// tec21: this returns the pattern that mistServer is apparently looking for.
async function getApi() {
    // await axios.post('http://mist.nottv.io:4242/api', {command: {authorize: {username: 'nottvadmin', password: '2791d4458fc1701506f7138e9f2b50b74a123815ae40a943b4758e0902fbf41f'}}})
    // await axios.get('http://mist.nottv.io:4242/api', {authorize: {username: 'nottvadmin', password: '2791d4458fc1701506f7138e9f2b50b74a123815ae40a943b4758e0902fbf41f'}})
    // await axios.get('http://mist.nottv.io:4242/api?command=', {authorize: {username: 'nottvadmin', password: '2791d4458fc1701506f7138e9f2b50b74a123815ae40a943b4758e0902fbf41f'}}, {
    //     preserveScroll: true
    // })
    await axios.get('https://beta-staging.not.tv/mistserver/api?command=', {"authorize": {"username": "nottvadmin", "password": props.message}})
        .then(response => {
            videoPlayer.apiResponse = response.data
        })
        .catch(error => {
            console.log(error);
        })
    console.log('mistServer API request sent.');
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




