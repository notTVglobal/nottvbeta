<template>
    <div>
        <PopUpModal :id="`startCountdownNotice`">
            <template v-slot:header>Set Countdown Timer</template>
            <template v-slot:main><p class="text-orange-500">This feature is coming soon. </p><p>Set how many minutes until you go live. If you have already scheduled the show you will automatically go live at the set time. You can change the set time here if needed. The audience will receive notifications that the live is about to begin 2 minutes in advance.</p></template>
        </PopUpModal>

        <PopUpModal :id="`startLiveStreamNotice`">
            <template v-slot:header>Start Live Stream</template>
            <template v-slot:main><p class="text-orange-500">This feature is coming soon. </p><p>Notifications will immediately go out to subscribers and your show will appear in the shows list with a live badge.</p></template>
        </PopUpModal>

        <PopUpModal :id="`setPushDestinationsNotice`">
            <template v-slot:header>Set Push Destinations</template>
            <template v-slot:main><p class="text-orange-500">This feature is coming soon.</p></template>
        </PopUpModal>

        <PopUpModal :id="`setCommercialBreakNotice`">
            <template v-slot:header>Commercial Breaks</template>
            <template v-slot:main><p class="text-orange-500">This feature is coming soon.</p></template>
        </PopUpModal>

        <PopUpModal :id="`teleprompterNotice`">
            <template v-slot:header>TELEPROMPTER</template>
            <template v-slot:main><span class="text-orange-500">This feature is coming soon.</span></template>
        </PopUpModal>

        <PopUpModal :id="`vtrNotice`">
            <template v-slot:header>VTR</template>
            <template v-slot:main><span class="text-orange-500">This feature is coming soon.</span></template>
        </PopUpModal>

        <PopUpModal :id="`graphicsNotice`">
            <template v-slot:header>Graphics (like Chryon)</template>
            <template v-slot:main><span class="text-orange-500">This feature is coming soon.</span></template>
        </PopUpModal>

        <PopUpModal :id="`scheduleReleaseNotice`">
            <template v-slot:header>Schedule Release</template>
            <template v-slot:main><span class="text-orange-500">This button is in development. You can schedule the release from the Show Edit page.</span></template>
        </PopUpModal>

        <PopUpModal :id="`addBonusContentNotice`">
            <template v-slot:header>Add Bonus Content</template>
            <template v-slot:main><span class="text-orange-500">This feature is coming soon.</span></template>
        </PopUpModal>

        <PopUpModal :id="`editDescriptionNotice`">
            <template v-slot:header>Edit Episode Description</template>
            <template v-slot:main><span class="text-orange-500">This button is in development. You can edit the description on the episode Edit page.</span></template>
        </PopUpModal>

    </div>

</template>

<script setup>
import PopUpModal from "@/Components/Modals/PopUpModal.vue";

</script>
