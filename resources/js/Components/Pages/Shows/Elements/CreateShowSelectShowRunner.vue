<template>
  <div class="mb-6 border-2 p-3">
    <label class="block mb-2 uppercase font-bold dark:text-gray-200"
           for="show_runner_creator_id"
    >
      Show Runner <span class="text-indigo-500">* REQUIRED</span>
    </label>
    <select
        class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-800"
        v-model="selectedShowRunnerCreatorId"
        @change="handleSelectChange"
        required
    >
      <option disabled value="">Select Show Runner</option>
      <option
          v-for="member in teamMembers"
          :key="member.creator_id"
          :value="member.creator_id"
          class="bg-white text-black border-b dark:text-gray-50 dark:bg-gray-800 dark:border-gray-600"
      >
        {{ member.name }}
      </option>
    </select>

    <!-- Button to toggle the explanation -->
    <button @click.prevent="toggleShowRunnerInfo"
            class="btn mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold uppercase text-xs">
      👉 Who is a Show Runner?
    </button>

    <!-- Conditional rendering of the show runner explanation -->
    <div v-if="showRunnerInfoVisible" class="mt-2 text-gray-800 dark:text-gray-200">
      <div class="mb-2">
        <strong>Show Runner:</strong> The chief architect behind a show, responsible for overseeing every element
        of production. Comparable to an event planner, but for new media production, the show runner handles the
        creative vision and daily operations, ensuring the show’s vision is realized through managing everything
        from scriptwriting to final edits. They lead the production team, make critical decisions on content and
        direction, and maintain the show's consistency and quality across episodes.
      </div>
      <div>
        <div class="mt-2 text-gray-800 dark:text-gray-200">
          <h3 class="font-bold">Show Runner for Informal and Community-Driven Productions:</h3>
          <p class="mt-1">
            A show runner in informal settings acts much like an event coordinator, emphasizing flexibility and
            audience engagement. They adapt quickly to live interactions and maintain the creative vision,
            ensuring the production is engaging and cohesive. Key responsibilities include:
          </p>
          <ul class="list-disc pl-5 mt-1">
            <li>Adjusting plans on-the-fly in response to audience dynamics and unscripted moments.</li>
            <li>Integrating audience feedback in real-time to guide the show’s direction.</li>
            <li>Managing schedules and coordinating with participants, akin to event planning.</li>
            <li>Maintaining the show's tone and style to align with overarching goals.</li>
            <li>Leading the production team effectively in dynamic, less controlled environments.</li>
          </ul>
        </div>
      </div>
    </div>

    <div v-if="errors.show_runner_creator_id" v-text="errors.show_runner_creator_id"
         class="text-xs text-red-600 mt-1"></div>
  </div>
</template>
<script setup>

import { ref } from 'vue'

const emits = defineEmits(["selectedShowRunnerCreatorId"]);

const props = defineProps({
  defaultShowRunnerId: Number,
  teamMembers: Object,
  errors: Object,
})

const selectedShowRunnerCreatorId = ref(props.defaultShowRunnerId);

const handleSelectChange = () => {
  emits("selectedShowRunnerCreatorId", selectedShowRunnerCreatorId.value);
};

const showRunnerInfoVisible = ref(false)

const toggleShowRunnerInfo = () => {
  showRunnerInfoVisible.value = !showRunnerInfoVisible.value
}
</script>