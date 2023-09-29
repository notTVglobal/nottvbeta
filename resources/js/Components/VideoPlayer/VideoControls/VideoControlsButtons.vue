<template>

    <div>
        <div class="icon-container">
            <button
                @click="videoPlayerStore.fullscreen()">

                <svg class="fill-current icon"
                     xmlns="http://www.w3.org/2000/svg"
                     width="48"
                     height="48"
                     viewBox="0 0 24 24">
                    <path d="M3,3H21V21H3V3M5,5V19H19V5H5Z"
                          stroke="#000"
                          stroke-width="0.5"/>
                    <g transform="scale(0.5) translate(12, 12)">
                        <path
                            d="M13.8995 4.10052V2.10052H21.8995V10.1005H19.8995V5.51477L14.1213 11.293L12.7071 9.87878L18.4854 4.10052H13.8995Z"
                            stroke="#000"
                            stroke-width="0.5"
                        />
                        <path
                            d="M4.10046 13.8995H2.10046V21.8995H10.1005V19.8995H5.51468L11.2929 14.1212L9.87872 12.707L4.10046 18.4853V13.8995Z"
                            stroke="#000"
                            stroke-width="0.5"
                        />
                    </g>
                </svg>
            </button>
        </div>

        <div class="icon-container">
            <label class="swap">

                <!-- this hidden checkbox controls the state -->
                <input type="checkbox"
                       v-model="videoPlayerStore.muted"
                       @input="videoPlayerStore.toggleMute"
                       class="sr-only" />

                <!-- volume on icon -->
                <svg class="swap-off fill-current icon"
                     xmlns="http://www.w3.org/2000/svg"
                     width="48"
                     height="48"
                     viewBox="0 0 24 24">
                    <path d="M14,3.23V5.29C16.89,6.15 19,8.83 19,12C19,15.17 16.89,17.84 14,18.7V20.77C18,19.86 21,16.28 21,12C21,7.72 18,4.14 14,3.23M16.5,12C16.5,10.23 15.5,8.71 14,7.97V16C15.5,15.29 16.5,13.76 16.5,12M3,9V15H7L12,20V4L7,9H3Z"
                          stroke="#000"
                          stroke-width="0.5"/></svg>

                <!-- volume off icon -->
                <svg class="swap-on fill-current icon"
                     xmlns="http://www.w3.org/2000/svg"
                     width="48"
                     height="48"
                     viewBox="0 0 24 24">
                    <path d="M3,9H7L12,4V20L7,15H3V9M16.59,12L14,9.41L15.41,8L18,10.59L20.59,8L22,9.41L19.41,12L22,14.59L20.59,16L18,13.41L15.41,16L14,14.59L16.59,12Z"
                          stroke="#000"
                          stroke-width="0.5"
                    /></svg>

            </label>
        </div>

        <div class="icon-container">
            <button
                class="cursor-help"
                @click="prev"
            >
                <svg
                    width="48"
                    height="48"
                    viewBox="0 0 24 24"
                    class="fill-curren icon"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path d="M18 17L10 12L18 7V17Z"
                          stroke="#000"
                          stroke-width="0.5"/>
                    <path d="M6 7H9V17H6V7Z"
                          stroke="#000"
                          stroke-width="0.5"/>
                </svg>
            </button>
        </div>

        <div class="icon-container">
            <label class="swap">

                <!-- this hidden checkbox controls the state -->
                <input type="checkbox"
                       v-model="videoPlayerStore.paused"
                       @input="videoPlayerStore.togglePlay"
                       class="sr-only" />

                <!-- play icon -->
                <svg class="swap-on fill-current icon"
                     xmlns="http://www.w3.org/2000/svg"
                     width="48"
                     height="48"
                     viewBox="0 0 24 24">
                    <path d="M8,5.14V19.14L19,12.14L8,5.14Z"
                          stroke="#000"
                          stroke-width="0.5"/></svg>

                <!-- pause icon -->
                <svg class="swap-off fill-current icon"
                     xmlns="http://www.w3.org/2000/svg"
                     width="48"
                     height="48"
                     viewBox="0 0 24 24">
                    <path d="M6,19H10V5H6V19M12,5V19H16V5H12Z"
                          stroke="#000"
                          stroke-width="0.5"/></svg>

            </label>
        </div>

        <div class="icon-container">
            <button
                class="cursor-help"
                @click="next"
            >
                <svg
                    width="48"
                    height="48"
                    viewBox="0 0 24 24"
                    class="fill-current icon"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path d="M6 17L14 12L6 7V17Z"
                          stroke="#000"
                          stroke-width="0.5"/>
                    <path d="M18 7H15V12V17H18V7Z"
                          stroke="#000"
                          stroke-width="0.5"/>
                </svg>
            </button>
        </div>

        <!-- Temporary Info Modal -->
        <dialog id="nextPrevButtonsInfoModal" class="modal">
            <div class="modal-box text-center my-auto" data-theme="dark">
                <h3 class="font-bold text-3xl">Hello!</h3>
                <p class="py-4 text-xl">Playlists and channel navigation are coming soon.</p>
                <div class="modal-action justify-center w-full">
                    <form method="dialog">
                        <!-- if there is a button in form, it will close the modal -->
                        <button class="btn">Okay</button>
                    </form>
                </div>
            </div>
        </dialog>

    </div>

</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"

let videoPlayerStore = useVideoPlayerStore()

function next() {
    document.getElementById('nextPrevButtonsInfoModal').showModal()
    // videoPlayerStore.next()
}

function prev() {
    document.getElementById('nextPrevButtonsInfoModal').showModal()
    // videoPlayerStore.back()
}

</script>

<style scoped>

.sr-only {
    position: absolute;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    width: 1px;
    height: 1px;
    margin: -1px;
    padding: 0;
    border: 0;
}

/* Normal state styles for the icon */
.icon-container {
    display: inline-block;
    color: #f59e0b;
}

.icon {
    fill: currentColor; /* Use the current text color as the icon color */
    transition: fill 0.3s ease; /* Smooth color transition */
}

/* Hover state styles for the icon */
.icon-container:hover .icon {
    fill: #d08509; /* Change to text-yellow-500 color on hover */
}


</style>
