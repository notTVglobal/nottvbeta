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
                    <JetAuthenticationCardLogo class="max-w-[8rem]"/>
                    <JetValidationErrors class="mb-4" />
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>
                </header>

                <div class="py-3 text-gray-600">
                    Please log in to watch notTV and chat.<br>
                    Need to <button @click="showRegister" class="text-blue-800 hover:text-blue-600">register</button> for an account?
                </div>
                <div class="py-3">
                        <form @submit.prevent="submit">
                            <div>
                                <JetLabel for="email" value="Email" />
                                <JetInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
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
                                    autocomplete="current-password"
                                />
                            </div>

                            <div class="block mt-4">
                                <label class="flex items-center">
                                    <JetCheckbox v-model:checked="form.remember" name="remember" />
                                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                                    Forgot your password?
                                </Link>

                                <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Log in
                                </JetButton>
                            </div>
                        </form>
                </div>

                <footer>
                    <div class="modal-footer">
                            <button
                                @click="clearForm"
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
import { useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

let welcomeStore = useWelcomeStore()

defineProps({
    canResetPassword: Boolean,
    status: String,
    show: Boolean
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

function clearForm() {
    form.reset();
    welcomeStore.showLogin = false;
}
function showRegister() {
    form.reset();
    welcomeStore.showLogin = false;
    welcomeStore.showRegister = true;
}

</script>

<style scoped>
.modal-mask {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: grid;
    place-items: center;
    z-index: 100;
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
