<template>
  <div>
    <button
        @click.prevent="toggleLicenseInfo"
        class="btn bg-blue-500 hover:bg-blue-600 text-white mb-4">
      👉 Click here for examples and to read the license information
    </button>

    <div v-if="showLicenseInfo" class="license-info">
      <h2 class="font-semibold mb-6">About The License Options:</h2>

      <h3 class="font-semibold mb-4">Creative Commons Licenses</h3>
      <ul class="mb-6 space-y-3">
        <li v-for="license in licenses.filter(l => l.type === 'Creative Commons')" :key="license.id" class="mb-4">
          <strong>{{ license.name }}:</strong> {{ license.description }}
          <h4 class="font-semibold mt-2">Examples:</h4>
          <ul class="ml-4 list-disc list-inside">
            <li v-for="example in license.examples" :key="example">{{ example }}</li>
          </ul>
        </li>
      </ul>

      <h3 class="font-semibold mb-4">Legacy Copyright</h3>
      <ul class="mb-6">
        <li v-for="license in licenses.filter(l => l.type === 'Legacy Copyright')" :key="license.id" class="mb-4">
          <strong>{{ license.name }}:</strong> {{ license.description }}
          <h4 class="font-semibold mt-2">Examples:</h4>
          <ul class="ml-4 list-disc list-inside">
            <li v-for="example in license.examples" :key="example">{{ example }}</li>
          </ul>
        </li>
      </ul>

      <h3 class="font-semibold mb-4">Public Domain</h3>
      <ul class="mb-6">
        <li v-for="license in licenses.filter(l => l.type === 'Public Domain')" :key="license.id" class="mb-4">
          <strong>{{ license.name }}:</strong> {{ license.description }}
          <h4 class="font-semibold mt-2">Examples:</h4>
          <ul class="ml-4 list-disc list-inside">
            <li v-for="example in license.examples" :key="example">{{ example }}</li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const showLicenseInfo = ref(false);

const toggleLicenseInfo = () => {
  showLicenseInfo.value = !showLicenseInfo.value;
}

const licenses = ref([
  {
    id: 1,
    type: 'Creative Commons',
    name: 'CC BY (Attribution)',
    description: 'Allows others to distribute, remix, adapt, and build upon your work, even commercially, as long as they credit you for the original creation.',
    examples: [
      'A blog post shared with attribution.',
      'A remix of your song credited to you.'
    ]
  },
  {
    id: 2,
    type: 'Creative Commons',
    name: 'CC BY-SA (Attribution-ShareAlike)',
    description: 'Similar to CC BY but requires derivatives to be licensed under identical terms.',
    examples: [
      'An open-source project using your code.',
      'A Wikipedia article citing your work.'
    ]
  },
  {
    id: 3,
    type: 'Creative Commons',
    name: 'CC BY-ND (Attribution-NoDerivs)',
    description: 'Allows for redistribution, commercial and non-commercial, as long as the content is passed along unchanged and in whole, with credit to you.',
    examples: [
      'A photo shared without alterations.',
      'An article distributed as-is with attribution.'
    ]
  },
  {
    id: 4,
    type: 'Creative Commons',
    name: 'CC BY-NC (Attribution-NonCommercial)',
    description: 'Allows others to remix, tweak, and build upon your work non-commercially, and although their new works must also acknowledge you and be non-commercial, they don’t have to license their derivative works on the same terms.',
    examples: [
      'A non-commercial blog using your infographic.',
      'A student project incorporating your research paper.'
    ]
  },
  {
    id: 5,
    type: 'Creative Commons',
    name: 'CC BY-NC-SA (Attribution-NonCommercial-ShareAlike)',
    description: 'Lets others remix, tweak, and build upon your work non-commercially, as long as they credit you and license their new creations under the identical terms.',
    examples: [
      'A non-commercial video incorporating your music.',
      'A free eBook using your illustrations with proper credit.'
    ]
  },
  {
    id: 6,
    type: 'Creative Commons',
    name: 'CC BY-NC-ND (Attribution-NonCommercial-NoDerivs)',
    description: 'Allows others to download your works and share them with others as long as they credit you, but they can’t change them in any way or use them commercially.',
    examples: [
      'A non-commercial website hosting your article as-is.',
      'A presentation including your slide deck without modifications.'
    ]
  },
  {
    id: 7,
    type: 'Legacy Copyright',
    name: 'All Rights Reserved',
    description: 'The creator retains all the privileges provided by copyright law, such as the right to reproduce, distribute, and display the work publicly.',
    examples: [
      'A book published with all rights reserved.',
      'An artwork displayed in a gallery with all rights retained.'
    ]
  },
  {
    id: 8,
    type: 'Public Domain',
    name: 'No Copyright',
    description: 'The work is free for public use; anyone can use, modify, or share it without asking for permission or providing attribution.',
    examples: [
      'A classic novel available for free use.',
      'An old photograph that can be used by anyone for any purpose.'
    ]
  }
]);
</script>

<style scoped>
.license-info {
  margin-bottom: 16px;
}

.license-info h3 {
  margin-top: 24px;
}

.license-info li {
  margin-bottom: 2px;
}
</style>
