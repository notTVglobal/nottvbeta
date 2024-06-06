<template>
  <thead class="divide-y divide-gray-200">
  <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
  <tr>
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center">
        <div>
          <div class="text-sm font-semibold pl-14">
            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
            Date
          </div>
        </div>
      </div>
    </td>

    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
      Start Time
    </td>

    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
      End Time
    </td>

    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
      Duration
    </td>

    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right">
      Expires in
    </td>

    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right">

    </td>
  </tr>
  </thead>
</template>
<script setup>
</script>