<template>

    <Teleport to="body">
        <Modal :show="teamStore.confirmManagerDialog" @close="teamStore.confirmManagerDialog = false" >

            <template #header>
                Are you sure?
            </template>


            <template #default>
                You are about to <slot /> <span class="font-bold">{{ teamStore.selectedManagerName }}</span> as a manager of the team.
            </template>
            <template #footer>
                <button @click.prevent="teamStore.confirmTeamManagerCancel" class="bg-gray-500 hover:bg-gray-600 py-2 px-4 text-white rounded-lg mr-2">Cancel</button>
                <button v-if="teamStore.addManager" @click.prevent="managerAddConfirmed" class="bg-blue-500 hover:bg-blue-600 py-2 px-4 text-white rounded-lg">Confirm Add</button>
                <button v-if="!teamStore.addManager" @click.prevent="managerRemoveConfirmed" class="bg-blue-500 hover:bg-blue-600 py-2 px-4 text-white rounded-lg">Confirm Remove</button>

            </template>
        </Modal>
    </Teleport>




</template>

<script setup>
import Modal from "@/Components/Modal";
import {useTeamStore} from "@/Stores/TeamStore";
import {ref} from "vue";

let teamStore = useTeamStore();

let props = defineProps({
    member: Object,
})

const emit = defineEmits(['confirmAddManager', 'confirmRemoveManager'])

function managerAddConfirmed() {
    emit('confirmAddManager')
}

function managerRemoveConfirmed() {
    emit('confirmRemoveManager')
}

</script>
