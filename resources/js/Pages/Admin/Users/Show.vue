<template>

    <Head :title="props.user.name" />
    <div class="flex m-auto mt-10 mb-10 w-1/2">
        <div class="bg-white rounded text-black p-5 mb-10">
            <div class="flex justify-between mb-6">
                <h1 class="text-2xl pb-3">{{props.user.name}}</h1>
                <Link href="/admin/users" class="text-blue-500 text-sm ml-2">Go back</Link>
            </div>
            <p>
                <img :src="props.user.profile_photo_url" />
            </p>

        </div>
    </div>

</template>

<script setup>
let props = defineProps({
    user: Object
});
</script>
