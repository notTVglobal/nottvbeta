<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase font-bold dark:text-gray-200"
           for="category"
    >
      Category <span :class="errors.category ? 'text-red-500' : 'text-indigo-500'">* REQUIRED</span>
    </label>


    <select
        class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs "
        v-model="showStore.category_id"
        @change="chooseCategory"
    >
      <option disabled :value="null">Choose a category...</option>
      <option v-for="category in categories"
              :key="category.id" :value="category.id">{{ category.name }}
      </option>

    </select>
    <div v-if="errors.category" v-text="errors.category"
         class="text-xs text-red-600 mt-1"></div>

    <span class="">{{ showStore.category_description }}</span>
  </div>

  <div class="mb-6">
    <label class="block mb-1 uppercase font-bold dark:text-gray-200"
           for="sub_category"
    >
      Sub-category
    </label>

    <select
        class="border border-gray-400 text-gray-800 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs"
        v-model="showStore.sub_category_id"
        @change="chooseSubCategory"
    >
      <option v-if="!showStore.category_id" disabled :value="null">Choose a category first</option>
      <option v-else disabled :value="null">Choose a subcategory...</option>
      <option v-for="subCategory in showStore.sub_categories" :key="subCategory.id" :value="subCategory.id">
        {{ subCategory?.name }}
      </option>
    </select>
    <span class="">{{ showStore.sub_category_description }}</span>
    <div v-if="errors.sub_category" v-text="errors.sub_category"
         class="text-xs text-red-600 mt-1"></div>
  </div>
</template>
<script setup>
import { useShowStore } from '@/Stores/ShowStore'
import { computed } from 'vue'

const showStore = useShowStore()

const props = defineProps({
  categories: Object,
  errors: Object,
})

const subCategories = computed(() => {
  const category = props.categories.find(cat => cat.id === showStore.category_id)
  return category ? category.sub_categories : []
})

const chooseCategory = () => {
  showStore.initializeDescriptions(showStore.category_id, showStore.sub_category_id)
}

const chooseSubCategory = () => {
  showStore.updateSubCategoryDescription(showStore.sub_category_id)
}

</script>