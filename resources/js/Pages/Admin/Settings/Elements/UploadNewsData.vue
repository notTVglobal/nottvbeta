  <!-- resources/js/Pages/Admin/UploadNewsData.vue -->
  <template>
    <div class="container mb-6 rounded-lg bg-gray-100 p-4">
      <h2 class="mb-4">Upload Data for News Cities, Provinces, Districts</h2>
      <form @submit.prevent="uploadFile">
        <div class="flex flex-col form-group gap-2 mb-4 w-fit">
          <label class="block uppercase font-bold text-xs text-gray-700 dark:text-gray-300">Select Model</label>
          <select id="model" v-model="selectedModel" class="form-select block w-full" required @change="updateColumns">
            <option value="" disabled>Select a model</option>
            <option value="NewsCity">News Cities</option>
            <option value="NewsProvince">News Provinces</option>
            <option value="NewsFederalElectoralDistrict">News Federal Electoral Districts</option>
            <option value="NewsSubnationalElectoralDistrict">News Subnational Electoral Districts</option>
          </select>
        </div>
        <div v-if="selectedModel" class="mb-4">
          <h3 class="mb-2 font-bold text-xs text-gray-700 dark:text-gray-300">CSV Format Requirements</h3>
          <p v-if="selectedModel === 'NewsCity'" class="text-xs text-gray-700 dark:text-gray-300">
            name, slug, description, type, province_id, country_id, population, area, latitude, longitude, economic_indicators, cultural_significance, city_mayor, tourism_attractions, city_website, year_incorporated, airport_code, time_zone, gmt_offset, gmt_offset_dst, dst_observed
          </p>
          <!-- Add similar descriptions for other models if needed -->
        </div>
        <div v-if="selectedModel" class="mb-4">
          <h3 class="mb-2 font-bold text-xs text-gray-700 dark:text-gray-300">Select Columns to Update</h3>
          <div v-for="(label, column) in columns[selectedModel]" :key="column" class="flex items-center gap-2 space-y-2">
            <input type="checkbox" :id="column" v-model="selectedColumns" :value="column" />
            <label :for="column" class="text-xs text-gray-700 dark:text-gray-300">{{ label }}</label>
          </div>
        </div>
        <div class="flex flex-col form-group gap-2 mb-4 w-fit">
          <label class="block uppercase font-bold text-xs text-gray-700 dark:text-gray-300">Upload CSV File</label>
          <input type="file" id="dataFile" @change="handleFileUpload" accept=".csv" required />
        </div>
        <button type="submit" class="btn btn-primary" :disabled="isUploading">Upload</button>
      </form>
    </div>
  </template>

  <script setup>
  import { reactive, ref } from 'vue'
  import axios from 'axios';

  const isUploading = ref(false);
  const file = ref(null);
  const selectedModel = ref('');
  const selectedColumns = ref([]);

  const columns = reactive({
    NewsCity: {
      name: 'Name',
      slug: 'Slug',
      description: 'Description',
      type: 'Type',
      province_id: 'Province ID',
      country_id: 'Country ID',
      population: 'Population',
      area: 'Area',
      latitude: 'Latitude',
      longitude: 'Longitude',
      economic_indicators: 'Economic Indicators',
      cultural_significance: 'Cultural Significance',
      city_mayor: 'City Mayor',
      tourism_attractions: 'Tourism Attractions',
      city_website: 'City Website',
      year_incorporated: 'Year Incorporated',
      airport_code: 'Airport Code',
      time_zone: 'Time Zone',
      gmt_offset: 'GMT Offset',
      gmt_offset_dst: 'GMT Offset DST',
      dst_observed: 'DST Observed'
    },
    // Add columns for other models if needed
  });

  const handleFileUpload = (event) => {
    file.value = event.target.files[0];
  };

  const updateColumns = () => {
    selectedColumns.value = [];
  };

  const uploadFile = async () => {
    if (!file.value || !selectedModel.value) return;

    isUploading.value = true;

    const formData = new FormData();
    formData.append('dataFile', file.value);
    formData.append('model', selectedModel.value);
    formData.append('columns', JSON.stringify(selectedColumns.value));

    try {
      await axios.post('/admin/upload-news-data', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      alert('File uploaded successfully!');
    } catch (error) {
      console.error('Error uploading file:', error);
      alert('There was an error uploading the file.');
    } finally {
      isUploading.value = false;
      file.value = null;
      selectedModel.value = '';
      selectedColumns.value = [];
    }
  };
  </script>
