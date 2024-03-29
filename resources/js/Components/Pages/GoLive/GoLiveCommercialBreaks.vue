<template>
  <div class="mt-4 mb-8 px-6 py-1 w-full ">
    <div
        class="text-sm font-semibold bg-orange-600 text-white text-center w-full border-2 border-orange-600 rounded uppercase px-6 py-1 ">
      Commercial Breaks
    </div>
    <div class="shadow bg-orange-100 overflow-hidden border-2 border-orange-600 rounded py-6 px-10 space-y-3 w-full">
      <div></div>
      <div>Click the <span class="font-bold">Trigger Commercial Break</span> button below to go to commercial.</div>
      <div>You will see a countdown timer until the show resumes. This will be 1-2 minutes.</div>
      <div>As a notTV creator, your voice matters. How can this feature be improved to better serve you and your
        sponsors?
        Please email and let us know: <a href="mailto:hello@not.tv" target="_blank"
                                         class="text-blue-500 hover:text-blue-600">hello@not.tv</a>
      </div>
      <div class="w-full flex justify-center pt-4">
        <button onclick="setCommercialBreakNotice.showModal()"
                class="bg-orange-600 hover:bg-orange-500 text-white font-semibold rounded-lg px-4 py-2">Trigger
          Commercial Break
        </button>
      </div>
    </div>

  </div>
</template>
<script setup>
</script>