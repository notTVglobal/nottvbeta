<template>

    <JetFormSection @submitted="updateContactInformation">
        <template #title>
            Contact Information
        </template>

        <template #description>
            Update your account's contact information.
        </template>



        <template #form>
<!--            <form @submit.prevent="submit" class="mt-6">-->

<!--        <div class="mb-6">-->
<!--            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"-->
<!--                   for="phone"-->
<!--            >-->
<!--                Phone Number-->
<!--            </label>-->

<!--            <input v-model="form.phone"-->
<!--                   class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
<!--                   type="text"-->
<!--                   name="phone"-->
<!--                   id="phone"-->
<!--            >-->
<!--            <div v-if="form.errors.phone" v-text="form.errors.phone" class="text-xs text-red-600 mt-1"></div>-->
<!--        </div>-->


                <div class="col-span-6 sm:col-span-4">
                    <JetLabel for="phone" value="Phone Number" />
                    <JetInput
                        id="phone"
                        v-model="form.phone"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="phone"
                    />
                    <JetInputError :message="form.errors.phone" class="mt-2" />
                </div>


                <div class="col-span-6 sm:col-span-4">
                    <JetLabel for="address1" value="Address 1" />
                    <JetInput
                        id="address1"
                        v-model="form.address1"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="address1"
                    />
                    <JetInputError :message="form.errors.address1" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <JetLabel for="address2" value="Address 2" />
                    <JetInput
                        id="address2"
                        v-model="form.address2"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="address2"
                    />
                    <JetInputError :message="form.errors.address2" class="mt-2" />
                </div>
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="city" value="City" />
                <JetInput
                    id="city"
                    v-model="form.city"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="city"
                />
                <JetInputError :message="form.errors.city" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="province" value="Province/State" />
                <JetInput
                    id="province"
                    v-model="form.province"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="province"
                />
                <JetInputError :message="form.errors.province" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="country" value="Country" />
                <JetInput
                    id="country"
                    v-model="form.country"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="country"
                />
                <JetInputError :message="form.errors.country" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="postal_code" value="Postal Code/Zip" />
                <JetInput
                    id="postalCode"
                    v-model="form.postalCode"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="postalCode"
                />
                <JetInputError :message="form.errors.postalCode" class="mt-2" />
            </div>



<!--        <div class="mb-6">-->
<!--            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"-->
<!--                   for="address1"-->
<!--            >-->
<!--                Address 1-->
<!--            </label>-->

<!--            <input v-model="form.address1"-->
<!--                   class="border border-gray-400 p-2 mb-2 w-full rounded-lg text-black"-->
<!--                   type="text"-->
<!--                   name="address1"-->
<!--                   id="address1"-->
<!--            >-->
<!--        </div>-->

<!--        <div class="mb-6">-->
<!--            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"-->
<!--                   for="address2"-->
<!--            >-->
<!--                Address 2-->
<!--            </label>-->

<!--            <input v-model="form.address2"-->
<!--                   class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
<!--                   type="text"-->
<!--                   name="address2"-->
<!--                   id="address2"-->
<!--            >-->
<!--            <div v-if="form.errors.address1" v-text="form.errors.address1" class="text-xs text-red-600 mt-1"></div>-->
<!--            <div v-if="form.errors.address2" v-text="form.errors.address2" class="text-xs text-red-600 mt-1"></div>-->
<!--        </div>-->





<!--        <div class="mb-6">-->
<!--            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"-->
<!--                   for="city"-->
<!--            >-->
<!--                City-->
<!--            </label>-->

<!--            <input v-model="form.city"-->
<!--                   class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
<!--                   type="text"-->
<!--                   name="city"-->
<!--                   id="city"-->
<!--            >-->
<!--            <div v-if="form.errors.city" v-text="form.errors.city" class="text-xs text-red-600 mt-1"></div>-->
<!--        </div>-->
<!--        <div class="mb-6">-->
<!--            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"-->
<!--                   for="province"-->
<!--            >-->
<!--                Province-->
<!--            </label>-->

<!--            <input v-model="form.province"-->
<!--                   class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
<!--                   type="text"-->
<!--                   name="province"-->
<!--                   id="province"-->
<!--            >-->
<!--            <div v-if="form.errors.province" v-text="form.errors.province" class="text-xs text-red-600 mt-1"></div>-->
<!--        </div>-->
<!--        <div class="mb-6">-->
<!--            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"-->
<!--                   for="country"-->
<!--            >-->
<!--                Country-->
<!--            </label>-->

<!--            <input v-model="form.country"-->
<!--                   class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
<!--                   type="text"-->
<!--                   name="country"-->
<!--                   id="country"-->
<!--            >-->
<!--            <div v-if="form.errors.country" v-text="form.errors.country" class="text-xs text-red-600 mt-1"></div>-->
<!--        </div>-->

<!--        <div class="mb-6">-->
<!--            <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"-->
<!--                   for="postal_code"-->
<!--            >-->
<!--                Postal Code-->
<!--            </label>-->

<!--            <input v-model="form.postalCode"-->
<!--                   class="border border-gray-400 p-2 w-full rounded-lg text-black"-->
<!--                   type="text"-->
<!--                   name="postalCode"-->
<!--                   id="postalCode"-->
<!--            >-->
<!--            <div v-if="form.errors.postalCode" v-text="form.errors.postalCode" class="text-xs text-red-600 mt-1"></div>-->
<!--        </div>-->

<!--        <div class="flex justify-between mb-6">-->
<!--            <button-->
<!--                type="submit"-->
<!--                class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"-->
<!--                :disabled="form.processing"-->
<!--            >-->
<!--                Submit-->
<!--            </button>-->
<!--            <div class="text-sm text-blue-600 mt-1 mb-2">-->
<!--                {{ props.message }}-->
<!--            </div>-->
<!--        </div>-->
<!--    </form>-->
        </template>

        <template #actions>
            <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </JetActionMessage>

            <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </JetButton>
        </template>
    </JetFormSection>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';

let props = defineProps({
    user: Object,
    message: String
});

let form = useForm({
    id: props.user.id,
    address1: props.user.address1,
    address2: props.user.address2,
    city: props.user.city,
    province: props.user.province,
    country: props.user.country,
    postalCode: props.user.postalCode,
    phone: props.user.phone,
});

const updateContactInformation = () => {

    form.put(route('users.updateContact'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
    });
};
// let submit = () => {
//     form.put(route('users.updateContact'));
// };
// function reset() {
//     form.reset();
// };
</script>
