<template>

    <Head :title="`Invite Codes`"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white dark:bg-gray-800 rounded text-black dark:text-gray-50 p-5">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <AdminHeader>Invite Codes</AdminHeader>

            <div>
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                               for="code"
                        >
                            NEW CODE
                        </label>

                        <input v-model="form.code"
                               class="border border-gray-400 p-2 w-1/4 rounded-lg text-black"
                               type="text"
                               name="code"
                               id="code"
                        >
                        <div v-if="form.errors.code" v-text="form.errors.code"
                             class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="flex justify-start my-6 mr-6">
                        <button
                            @click.prevent="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"
                            :disabled="form.processing"
                        >
                            Add Code
                        </button>

                        <button
                            @click.prevent="exportCodes"
                            class="bg-blue-500 hover:bg-blue-600 text-white ml-2 px-4 py-2 h-fit rounded disabled:bg-gray-400"
                        >
                            Export Codes
                        </button>


<!--                        <JetValidationErrors class="ml-4" :key="props.messageKey"/>-->
                    </div>

                    <div class="text-gray-600"> The form is a bit buggy.. if you enter a code that is already claimed and then enter a new code sometimes the error message disappears and sometimes it does not.</div>

                </form>


                <div class="flex justify-between mt-4">

                    <div class="flex flex-row justify-end gap-x-4 mb-6">
                        <input v-model="search" type="search" placeholder="Search..." class="text-black border px-2 rounded-lg" />
                    </div>
                </div>

                <table
                    class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                >
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                    >
                    <tr>
                        <th scope="col" class="px-6 py-3 w-1/4">
                            Code
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Created by
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date created
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Claimed by
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date claimed
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Notes
                        </th>
                        <th scope="col" class="px-6 py-3">
<!--                            buttons for "reset the code", "claim the code"-->
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="code in invite_codes.data"
                        :key="code.id"
                        class="w-1/4 bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                    >
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                            {{ code.code }}
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                            {{ code.created_by }}
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                            {{ code.created_at }}
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                            {{ code.claimed_by }}
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                            {{ code.claimed_at }}
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                            {{ code.notes }}
                        </th>
                        <td class="px-6 py-4 space-x-2">
                            <Link :href="`#`"><button
                                disabled
                                class="px-4 py-2 mb-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                            >Claim (if unclaimed)</button>
                            </Link>
                        </td>
                    </tr>
                    </tbody>
                </table>

<!--                Paginator-->
                <Pagination :data="invite_codes" class="mb-6"/>


                <div>
                    TO DO's:
                </div>
                <div>
                    * Update "Add Code" with an Invite Code creation form... create batches of invite codes for admin use, or assign them to creators.
                </div>
                <div>
                    * Assign a user_role to the Invite codes, so they will automatically create that user type. Requires a pivot table invite_codes_roles
                </div>
                <div>
                    * Plus any notes about the code (this would be human readable to describe the promotional event which created the codes
                </div>
                <div>
                    *
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref, watch } from "vue"
import { Inertia } from "@inertiajs/inertia";
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import Pagination from "@/Components/Pagination"
import throttle from "lodash/throttle"
import AdminHeader from "@/Components/Admin/AdminHeader.vue";



let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'admin'

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(async () => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {
    //
    //     userStore.scrollToTopCounter ++;
    // }
    props.message = '';
});

let props = defineProps({
    invite_codes: Object,
    filters: String,
    message: String,
    messageKey: 1,
})

let form = useForm({
    code: '',
})

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/admin/invite_codes', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

let submit = () => {
    // Inertia.reload({ only: ['error'] })
    // Inertia.reload({ only: ['message'] })
    form.post(route('admin.inviteCodes'));
    // props.message = '';
    // props.messageKey ++;
};

let exportCodes = () => {
    Inertia.visit('/admin/export_invite_codes');
}

let showMessage = ref(true);

</script>
