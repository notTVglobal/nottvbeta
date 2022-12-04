<template>
    <Transition
        enter-from-class="opacity-0 scale-125"
        enter-to-class="opacity-100 scale-100"
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-125"
    >
        <div v-if="show" class="modal-mask">
            <div class="modal-container">
                <header class="flex uppercase text-sm font-semibold mb-2 text-center">
                    <JetAuthenticationCardLogo />
                    <JetValidationErrors class="mb-4" />
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>
                </header>

                <div class="py-3 text-gray-600">
                    Please register for an account to watch notTV.
                </div>
                <div class="py-3">
                <form @submit.prevent="submit">
                    <div>
                        <JetLabel for="name" value="Name" />
                        <JetInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autofocus
                            autocomplete="name"
                        />
                    </div>

                    <div class="mt-4">
                        <JetLabel for="email" value="Email" />
                        <JetInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                        />
                    </div>

                    <div class="mt-4">
                        <JetLabel for="password" value="Password" />
                        <JetInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            required
                            autocomplete="new-password"
                        />
                    </div>

                    <div class="mt-4">
                        <JetLabel for="password_confirmation" value="Confirm Password" />
                        <JetInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            required
                            autocomplete="new-password"
                        />
                    </div>

                    <!--            Jetstream/Fortify Multi-Auth: Roles, Permissions and Guards-->
                    <!--            https://www.youtube.com/watch?v=NiQSNjWKLfU-->

                    <!--            <div class="mt-4">-->
                    <!--                <JetLabel  for="role_id" value="Register as" />-->
                    <!--                <select name="role_id" v-model="form.role_id" class="mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">-->
                    <!--                    <option value="2" selected>Viewer</option>-->
                    <!--                    <option value="3">Creator</option>-->
                    <!--                </select>-->
                    <!--            </div>-->

                    <!--            <div class="mt-4" v-if="form.role_id == 3">-->
                    <!--                <JetLabel for="user_phone" value="Phone Number" />-->
                    <!--                <JetInput-->
                    <!--                    id="user_phone"-->
                    <!--                    v-model="form.user_phone"-->
                    <!--                    type="phone"-->
                    <!--                    class="mt-1 block w-full"-->
                    <!--                    required-->
                    <!--                    autocomplete="new-password"-->
                    <!--                />-->
                    <!--            </div>-->


                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                        <JetLabel for="terms">
                            <div class="flex items-center">
                                <JetCheckbox id="terms" v-model="form.terms" name="terms" required />

                                <div class="ml-2">
                                    I agree to the <a :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a> and <a :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                                </div>
                            </div>
                        </JetLabel>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button @click="showLogin" class="underline text-sm text-gray-600 hover:text-gray-900">
                            Already registered?
                        </button>

                        <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Register
                        </JetButton>
                    </div>
                </form>
                </div>

            <footer>
                <div class="modal-footer">
                    <button
                        @click="$emit('close')"
                        class="bg-gray-300 p-2 rounded-md hover:bg-gray-400 hover:text-gray-800"
                    >Cancel</button>
                </div>
            </footer>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { useWelcomeStore } from "@/Stores/WelcomeStore";
import { Link, useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

let welcomeStore = useWelcomeStore()

defineProps({
    status: String,
    show: Boolean
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: true,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

function showLogin() {
    welcomeStore.showLogin = true;
    welcomeStore.showRegister = false;
}

</script>

<style scoped>
.modal-mask {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: grid;
    place-items: center;
    z-index: 200;
}
.modal-container {
    background: white;
    color: black;
    padding: 1rem;
    width: 80vw;
    max-width: 500px;
    border-radius: 8px;
}
.modal-footer {
    border-top: 1px solid #ddd;
    margin-top: 1rem;
    padding-top: 0.5rem;
    font-size: .8rem;
}
</style>
