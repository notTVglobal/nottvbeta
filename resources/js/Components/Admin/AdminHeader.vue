<template>
    <header>
        <div class="flex justify-between my-3">
            <div class="mb-4">
                <h1 class="text-3xl font-semibold"><slot /></h1>
            </div>
            <div>
                <div class="flex flex-wrap-reverse justify-end gap-2">
                    <Link :href="`/admin/settings`">
                        <button
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Admin Settings
                        </button>
                    </Link>
                </div>
            </div>
        </div>
    </header>

</template>

<script setup>

</script>
