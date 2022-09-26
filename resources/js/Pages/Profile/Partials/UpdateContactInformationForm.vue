<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    address_1: props.user.address_1,
    address_2: props.user.address_2,
    city: props.user.city,
    province: props.user.province,
    country: props.user.country,
    postal_code: props.user.postal_code,
    phone: props.user.phone,
});

const updateContactInformation = () => {
    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
    });
};

</script>

<template>
    <JetFormSection @submitted="updateContactInformation">
        <template #title>
            Contact Information
        </template>

        <template #description>
            Update your account's contact information.
        </template>

        <template #form>

            <!-- Address -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="address_1" value="Address 1" />
                <JetInput
                    id="address_1"
                    v-model="form.address_1"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="address_1"
                />
                <JetInputError :message="form.errors.address_1" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="address_2" value="Address 2" />
                <JetInput
                    id="address_2"
                    v-model="form.address_2"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="address_2"
                />
                <JetInputError :message="form.errors.address_2" class="mt-2" />
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
                    id="postal_code"
                    v-model="form.postal_code"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="postal_code"
                />
                <JetInputError :message="form.errors.postal_code" class="mt-2" />
            </div>
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
