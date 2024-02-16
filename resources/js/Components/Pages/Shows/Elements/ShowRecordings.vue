<template>

  <table class="min-w-full divide-y divide-gray-200">
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
    <tbody class="bg-white divide-y divide-gray-200">
    <tr>
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
          <div>
            <div class="text-sm font-medium pl-14">
              <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
              MMMM, dd, yyyy
            </div>
          </div>
        </div>
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        12:00:00
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        12:00:00
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        00h 00m 00s
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
        3 days
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right space-x-1">
        <button class="btn btn-xs">Add To Episode</button>
        <button class="btn btn-xs">Download</button>
        <button class="btn btn-xs">Save to Premium Storage</button>
      </td>
    </tr>
    </tbody>

  </table>

</template>

<script setup>


</script>
