<template>
  <header>
    <div class="flex justify-between mb-6 pt-6">

      <div>
        <div class="font-bold mb-4 text-red-700">EDIT TEAM</div>
        <h1 class="text-3xl">
          <Link :href="`/teams/${team.slug}`" class="text-red-700 font-bold uppercase">{{ team.name }}</Link>
        </h1>
      </div>
      <div class="flex flex-row">
        <button
            @click.prevent="submit"
            class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded-lg py-2 px-4"
            :disabled="form.processing"
        >
          Save
        </button>
        <CancelButton/>
      </div>

    </div>
  </header>

  <div class="mb-6">
    <div><span class="text-xs uppercase font-semibold">Team Creator: </span>
      <span class="font-bold">{{ teamCreator.name }}</span>
    </div>
    <div><span class="text-xs uppercase font-semibold">Team Status: </span>
      <span class="font-bold">{{ team.team_status_name }}</span>
    </div>
  </div>


</template>

<script setup>
import CancelButton from "@/Components/Global/Buttons/CancelButton"

defineProps({
  team: Object,
  teamCreator: Object,
  form: Object,
})

const emit = defineEmits(['submit']);

const submit = () => {
  emit('submit');
};

</script>
