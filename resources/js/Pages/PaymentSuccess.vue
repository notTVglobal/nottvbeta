<template>
    <Head title="Thanks for your subscription!" />
    <div>
        We appreciate your business! If you have any questions, please email
        <a href="mailto:hello@not.tv">hello@not.tv</a>.
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { usePageSetup } from '@/Utilities/PageSetup'
// import Message from "@/Components/Global/Modals/Messages"

usePageSetup('paymentSuccess')

let props = defineProps({
    can: Object,
})

</script>
