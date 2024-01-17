<template>
    <Head title="Checkout canceled" />
    <div>
        Forgot to add something to your cart? Shop around then come back to pay!
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { usePageSetup } from '@/Utilities/PageSetup'
// import Message from "@/Components/Global/Modals/Messages"

usePageSetup('paymentCancelled')

appSettingStore.currentPage = 'paymentCancelled'

let props = defineProps({
    can: Object,
    message: String,
})

let showMessage = ref(true);

</script>
