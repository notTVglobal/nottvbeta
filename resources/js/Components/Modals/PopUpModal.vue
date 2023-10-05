<template>
    <div>
        <dialog :id="id" class="modal">
        <div class="modal-box text-center my-auto" data-theme="dark">
            <h2 class="font-bold text-3xl"><slot name="header"></slot></h2>
            <p class="py-4 text-xl"><slot name="main"></slot></p>
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

defineProps({
    id: String,
})
</script>
