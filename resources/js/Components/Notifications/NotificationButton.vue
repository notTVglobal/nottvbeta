<template>
    <div>
        <div class="indicator">
            <span v-if="userStore.newNotifications > 0 && userStore.newNotifications < 100 " class="indicator-item badge badge-secondary">{{userStore.newNotifications}}</span>
            <span v-if="userStore.newNotifications > 99" class="indicator-item badge badge-secondary">99+</span>
            <button class="btn btn-sm primary-content cursor-pointer mx-auto"
                    :class="hasNotifications"
                    @click.prevent="openNotifications()">
                <svg aria-hidden="false"
                     focusable="false"
                     data-prefix="fas"
                     data-icon="bell"
                     class="w-4"
                     role="img" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 448 512">
                    <path
                        style="fill: currentColor;"
                        d="M224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64zm215.39-149.71c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71z"
                    ></path>
                </svg>
            </button>
        </div>
    </div>

</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useUserStore} from "@/Stores/UserStore"
import {Inertia} from "@inertiajs/inertia";

let userStore = useUserStore();

const notifications = ref([]);

const fetchNotifications = async () => {
    // Make an API request to fetch notifications
    const response = await fetch(`/notifications`);
    const data = await response.json();
    userStore.newNotifications = data.notifications.length;
};

// const channel = Echo.private(`user.${userStore.id}`)
// Listen for the event when the component is mounted
onMounted( () => {
    fetchNotifications()

    // Echo.private(`user.${userStore.id}`).subscribed(() => {
    // }).listen('.userNotifications', (event) => {
    //     userStore.newNotifications++;
    //     // Inertia.reload();
    // })
    // const channel = Echo.private('user.1')
    // channel.subscribed(() => {
    // }).listen('.chat', (event) => {
    //     userStore.newNotifications++;
    //     console.log(event);
    // })

    // const channel = Echo.private(channelName)
    // channel.subscribed(() => {
    // }).listen('.userNotifications', (event) => {
    //         userStore.newNotifications++;
    //         Inertia.reload();
    //         // notifications.value.push(event.notification);
    // })

});

const hasNotifications = computed(() => ({
    'bg-white hover:bg-gray-400 dark:text-white text-blue-700 hover:text-blue-600': userStore.newNotifications > 0,
    // 'text-danger': error.value && error.value.type === 'fatal'
}))

function openNotifications() {
    document.getElementById('notifications').showModal()
}

</script>

<style scoped>
.my-icon path {
    fill: blue;
}
.active {
 @apply bg-white hover:bg-gray-400 dark:text-white text-blue-700 hover:text-blue-600;
}

</style>
