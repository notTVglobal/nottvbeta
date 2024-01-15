<template>
    <div v-if="teamStatusId === 12" class="team-transfer-container">
        <h2>Request Sent</h2>
        <p class="description">
            Your transfer request has been sent. You will receive a notification on your dashboard and by email when the transfer has either been accepted or rejected.
        </p>
    </div>

    <div v-if="can.transferTeam && teamStatusId !== 12" class="team-transfer-container">
        <div v-if="showTransferProcess" class="team-transfer-section">
            <h2>Team Transfer Process</h2>
            <button @click="startTransferRequest" class="transfer-button">Initiate Team Transfer</button>
            <p class="description">
                Click this button to start the process of transferring your team. This action will allow you to select a recipient and set terms for the transfer. Please review all terms and conditions carefully before proceeding, as this action will initiate formal negotiations and potential changes in team ownership and management.
            </p>
        </div>
        <div v-if="showTransferRequest" class="transfer-request-section">
            <h2>Send Transfer Request</h2>
            <TeamTransferProcessSendTransferRequest :creators="creators" :creatorFilters="creatorFilters" @transferInitiated="handleTransferInitiated"/>
            <p class="description">
                Search and select a creator to whom you want to transfer the team. After selecting, click 'Send Request' to initiate the transfer process.
            </p>
        </div>
        <div v-if="showConfirmation" class="confirmation-section">
            <h2>Request Sent</h2>
            <p class="description">
                Your transfer request has been sent. You will receive a notification on your dashboard and by email when the transfer has either been accepted or rejected.
            </p>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, onMounted } from 'vue';
import TeamTransferProcessSendTransferRequest
    from "@/Components/Teams/Manage/Elements/TeamTransferProcessSendTransferRequest.vue";

defineProps({
    creators: Object,
    creatorFilters: Object,
    teamStatusId: Number,
    can: Object,
})

const showTransferProcess = ref(true);
const showTransferRequest = ref(false);
const showConfirmation = ref(false);
const selectedCreator = ref('');

const teamStatus = ref(null);
const selectedStatus = ref(null);
const potentialStatuses = ref([
    { id: 3, name: 'Going Concern' },
    { id: 7, name: 'Acquisition' },
    // Add other statuses as needed
]);

const startTransferRequest = () => {
    console.log('start transfer request')
    showTransferProcess.value = false;
    showTransferRequest.value = true;
};

const sendTransferRequest = () => {
    console.log('send transfer request')

    // Implement logic to handle the transfer request
    // For example, sending data to the backend

    // Move to the confirmation stage
    showTransferRequest.value = false;
    showConfirmation.value = true;

};

const handleTransferInitiated = () => {
    // Handle the event
    console.log('Transfer has been initiated');
    // You can add any other logic you want to execute when the event is emitted
}

onMounted(async () => {
    // Fetch team data from your API
    // teamStatus.value = ...
});

const updateTeamStatus = async (newStatus) => {
    // Call your API to update the team status
};

const sendNotification = async (targetUserId, message) => {
    // Call your API to send a notification
};

// Define props and emits if needed
// const props = defineProps({});
const emits = defineEmits(['transferInitiated']);

// Method to handle the initiation of the transfer
const initiateTransfer = () => {
    // Implement your logic here
    // For example, emitting an event or routing to a different page
    emits('transferInitiated');
};
</script>

<style scoped>

.team-transfer-container {
    margin: 20px;
}

.team-transfer-section, .transfer-request-section {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
}

.transfer-button, .request-button {
    /* Shared button styles */
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
}

.description {
    margin-top: 10px;
    color: #555;
}

.confirmation-section {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
}
</style>
