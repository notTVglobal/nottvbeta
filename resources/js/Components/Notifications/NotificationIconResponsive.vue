<template>
    <div>
        <div class="indicator">
            <span class="indicator-item badge badge-secondary">99+</span>
            <button class="btn btn-sm primary-content"
                    @click="openNotifications()">
                <svg aria-hidden="false" focusable="false" data-prefix="fas" data-icon="bell" class="mx-auto dark:text-white w-4 hover:text-blue-600 cursor-pointer" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path style="fill: blue;" d="M224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64zm215.39-149.71c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71z"></path>
                </svg>
            </button>
        </div>
        <dialog id="notificationsResponsive" class="modal">
            <div class="modal-box bg-gray-900">
                <h3 class="font-bold text-lg">Hello!</h3>
                <p class="py-4">Notifications will go here.</p>
                <p class="py-4">Press ESC key or click outside to close</p>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>

</template>

<script setup>
function openNotifications() {
    document.getElementById('notificationsResponsive').showModal()
}

</script>

<style scoped>
.my-icon path {
    fill: blue;
}

</style>
