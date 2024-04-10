<template>

  <dialog id="adminSelectMistStreamModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">

      <div class="flex flex-row justify-between mb-6">
        <div>
          <h3 class="font-bold text-lg">{{ header }}</h3>
        </div>
        <div class="flex items-center mt-6 xl:mt-0">
          <div class="relative">
            <input v-model="searchTerm" @input="fetchItems" type="search" class="bg-gray-50 text-black text-sm rounded-full
                        focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
            <div class="absolute top-0 flex items-center h-full ml-2">
              <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path
                    d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <ul>
        <li v-for="item in items" :key="item.id" @click="selectItem(item)">
          {{ item.name }}
        </li>
      </ul>


      <div>
        <div class="overflow-x-auto">
          <table class="table table-xs">
            <thead>
            <tr>
              <th></th>
              <th>Name</th>
              <th>Job</th>
              <th>company</th>
              <th>location</th>
              <th>Last Login</th>
              <th>Favorite Color</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <th>1</th>
              <td>Cy Ganderton</td>
              <td>Quality Control Specialist</td>
              <td>Littel, Schaden and Vandervort</td>
              <td>Canada</td>
              <td>12/16/2020</td>
              <td>Blue</td>
            </tr>
            <tr>
              <th>2</th>
              <td>Hart Hagerty</td>
              <td>Desktop Support Technician</td>
              <td>Zemlak, Daniel and Leannon</td>
              <td>United States</td>
              <td>12/5/2020</td>
              <td>Purple</td>
            </tr>
            <tr>
              <th>3</th>
              <td>Brice Swyre</td>
              <td>Tax Accountant</td>
              <td>Carroll Group</td>
              <td>China</td>
              <td>8/15/2020</td>
              <td>Red</td>
            </tr>
            <tr>
              <th>4</th>
              <td>Marjy Ferencz</td>
              <td>Office Assistant I</td>
              <td>Rowe-Schoen</td>
              <td>Russia</td>
              <td>3/25/2021</td>
              <td>Crimson</td>
            </tr>
            <tr>
              <th>5</th>
              <td>Yancy Tear</td>
              <td>Community Outreach Specialist</td>
              <td>Wyman-Ledner</td>
              <td>Brazil</td>
              <td>5/22/2020</td>
              <td>Indigo</td>
            </tr>
            <tr>
              <th>6</th>
              <td>Irma Vasilik</td>
              <td>Editor</td>
              <td>Wiza, Bins and Emard</td>
              <td>Venezuela</td>
              <td>12/8/2020</td>
              <td>Purple</td>
            </tr>
            <tr>
              <th>7</th>
              <td>Meghann Durtnal</td>
              <td>Staff Accountant IV</td>
              <td>Schuster-Schimmel</td>
              <td>Philippines</td>
              <td>2/17/2021</td>
              <td>Yellow</td>
            </tr>
            <tr>
              <th>8</th>
              <td>Sammy Seston</td>
              <td>Accountant I</td>
              <td>O'Hara, Welch and Keebler</td>
              <td>Indonesia</td>
              <td>5/23/2020</td>
              <td>Crimson</td>
            </tr>
            <tr>
              <th>9</th>
              <td>Lesya Tinham</td>
              <td>Safety Technician IV</td>
              <td>Turner-Kuhlman</td>
              <td>Philippines</td>
              <td>2/21/2021</td>
              <td>Maroon</td>
            </tr>
            <tr>
              <th>10</th>
              <td>Zaneta Tewkesbury</td>
              <td>VP Marketing</td>
              <td>Sauer LLC</td>
              <td>Chad</td>
              <td>6/23/2020</td>
              <td>Green</td>
            </tr>
            <tr>
              <th>11</th>
              <td>Andy Tipple</td>
              <td>Librarian</td>
              <td>Hilpert Group</td>
              <td>Poland</td>
              <td>7/9/2020</td>
              <td>Indigo</td>
            </tr>
            <tr>
              <th>12</th>
              <td>Sophi Biles</td>
              <td>Recruiting Manager</td>
              <td>Gutmann Inc</td>
              <td>Indonesia</td>
              <td>2/12/2021</td>
              <td>Maroon</td>
            </tr>
            <tr>
              <th>13</th>
              <td>Florida Garces</td>
              <td>Web Developer IV</td>
              <td>Gaylord, Pacocha and Baumbach</td>
              <td>Poland</td>
              <td>5/31/2020</td>
              <td>Purple</td>
            </tr>
            <tr>
              <th>14</th>
              <td>Maribeth Popping</td>
              <td>Analyst Programmer</td>
              <td>Deckow-Pouros</td>
              <td>Portugal</td>
              <td>4/27/2021</td>
              <td>Aquamarine</td>
            </tr>
            <tr>
              <th>15</th>
              <td>Moritz Dryburgh</td>
              <td>Dental Hygienist</td>
              <td>Schiller, Cole and Hackett</td>
              <td>Sri Lanka</td>
              <td>8/8/2020</td>
              <td>Crimson</td>
            </tr>
            <tr>
              <th>16</th>
              <td>Reid Semiras</td>
              <td>Teacher</td>
              <td>Sporer, Sipes and Rogahn</td>
              <td>Poland</td>
              <td>7/30/2020</td>
              <td>Green</td>
            </tr>
            <tr>
              <th>17</th>
              <td>Alec Lethby</td>
              <td>Teacher</td>
              <td>Reichel, Glover and Hamill</td>
              <td>China</td>
              <td>2/28/2021</td>
              <td>Khaki</td>
            </tr>
            <tr>
              <th>18</th>
              <td>Aland Wilber</td>
              <td>Quality Control Specialist</td>
              <td>Kshlerin, Rogahn and Swaniawski</td>
              <td>Czech Republic</td>
              <td>9/29/2020</td>
              <td>Purple</td>
            </tr>
            <tr>
              <th>19</th>
              <td>Teddie Duerden</td>
              <td>Staff Accountant III</td>
              <td>Pouros, Ullrich and Windler</td>
              <td>France</td>
              <td>10/27/2020</td>
              <td>Aquamarine</td>
            </tr>
            <tr>
              <th>20</th>
              <td>Lorelei Blackstone</td>
              <td>Data Coordiator</td>
              <td>Witting, Kutch and Greenfelder</td>
              <td>Kazakhstan</td>
              <td>6/3/2020</td>
              <td>Red</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
              <th></th>
              <th>Name</th>
              <th>Job</th>
              <th>company</th>
              <th>location</th>
              <th>Last Login</th>
              <th>Favorite Color</th>
            </tr>
            </tfoot>
          </table>
        </div>
        <div class="flex flex-row justify-center mt-6 w-full">
          <div class="join grid grid-cols-2">
            <button class="join-item btn btn-sm btn-outline">Previous page</button>
            <button class="join-item btn btn-sm btn-outline">Next</button>
          </div>
        </div>
      </div>








      <div class="modal-action">
        <form method="dialog">
          <!-- if there is a button, it will close the modal -->
          <button class="btn">Close</button>
        </form>
      </div>
    </div>
  </dialog>

</template>
<script setup>
import { onMounted, ref, watch } from 'vue'
import throttle from 'lodash/throttle'
import { Inertia } from '@inertiajs/inertia'
import { debounce } from 'lodash'

let props = defineProps({
  mistStreams: Object,
  mistStream: Object,
  filters: Object,
  shouldFetch: Boolean, // Example prop to control fetching
})

const modalVisible = ref(false);

const mistStreams = ref([]);
const searchQuery = ref('');
// let search = ref(props.filters.search);

// watch(searchQuery, (newValue) => {
//   fetchMistStreams(newValue);
// });

const fetchData = debounce(() => {
  if (!props.shouldFetch) return; // Condition to prevent immediate fetching

  Inertia.get('/admin/mist-stream/search', { search: searchQuery.value }, {
    preserveState: true,
    replace: true,
  });
}, 300);

onMounted(fetchData);

// const fetchMistStreams = throttle((value) => {
//   Inertia.get('/admin/mist-stream/search', { search: value }, {
//     preserveState: true,
//     replace: true,
//     only: ['mistStreams', 'filters']
//   });
// }, 300);

// onMounted(() => {
//   fetchMistStreams(searchQuery.value);
// });



// function fetchMistStreams() {
//   Inertia.get('/mistStream/adminSearch', { search: searchQuery.value }, {
//     preserveState: true,
//     only: ['mistStreams'],
//     onSuccess: (page) => {
//       mistStreams.value = page.props.mistStreams;
//     }
//   });
// }
//
// // Watch for search changes and fetch data with Inertia
// watch(searchQuery, throttle(function (value) {
//   Inertia.get('/mistStreams', { searchQuery: value }, {
//     preserveState: true,
//     replace: true,
//     only: ['mistStreams', 'filters']
//   });
// }, 300));

function toggleModal() {
  console.log('Toggling modal:', !modalVisible.value);
  modalVisible.value = !modalVisible.value;
}
</script>