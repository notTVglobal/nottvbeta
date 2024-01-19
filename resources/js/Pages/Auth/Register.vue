<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import { useAppSettingStore } from "@/Stores/AppSettingStore"

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: true,
    invite_code: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

// function submit() {
//     form.post(route('contact-us.store'), {
//         preserveScroll: true,
//         onSuccess: () => console.log('success'),
//     })
// }
</script>
<!--<script>-->
<!--import NoLayout from '../../Layouts/NoLayout';-->
<!--export default {-->
<!--    layout: NoLayout,-->
<!--}-->

<!--</script>-->

<template>
    <Head title="Register" />
<div>
    <JetAuthenticationCard>
        <template #logo>
            <JetAuthenticationCardLogo />
        </template>

        <JetValidationErrors class="mb-4" />

        <div class="my-4">
            Please register for an account to watch notTV.
        </div>

        <form @submit.prevent="submit" class="pb-10">

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

            <div class="mt-4">
                <JetLabel for="invite_code" value="Invite Code" />
                <JetInput
                    id="invite_code"
                    v-model="form.invite_code"
                    type="password"
                    class="mt-1 block w-full"
                    required
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

            <!-- Google Recaptcha -->
<!--            <div class="g-recaptcha mt-4" data-sitekey={config('services.recaptcha.key')}></div>-->

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Already registered?
                </Link>

                <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </JetButton>
            </div>

          <div class="modal-footer">
            <div class="mt-4 pt-4 ml-4 border-t border-t-gray-200 text-center font-semibold">
              For a chance to get an invite code<br> <a
                href="https://not.tv/subscribe" target="_blank" class="text-blue-600 hover:text-blue-400 hover:cursor-pointer">subscribe
              to our newsletter</a>
            </div>
          </div>
        </form>
    </JetAuthenticationCard>
</div>
</template>
