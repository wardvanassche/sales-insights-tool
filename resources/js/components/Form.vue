<script setup lang="ts">
// defineProps<Props>();
// no props needed for now
</script>

<template>
    <form action="#" method="POST">
        <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label
                    for="name"
                    class="block text-sm/6 font-semibold text-gray-900 dark:text-gray-100"
                >
                    Company Name
                </label>
                <div class="mt-2.5">
                    <input
                        id="name"
                        name="name"
                        type="text"
                        placeholder="Enter company name"
                        class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 dark:bg-gray-800 dark:text-gray-100 dark:outline-gray-700 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                    />
                </div>
            </div>
            <div class="sm:col-span-2">
                <label
                    for="url"
                    class="block text-sm/6 font-semibold text-gray-900 dark:text-gray-100"
                >
                    Company URL
                </label>
                <div class="mt-2.5">
                    <input
                        type="url"
                        name="url"
                        id="url"
                        placeholder="https://example.com"
                        class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 dark:bg-gray-800 dark:text-gray-100 dark:outline-gray-700 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                    />
                </div>
            </div>
        </div>
        <div class="mt-10 flex justify-end">
            <button
                type="submit"
                class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-400"
            >
                Quickscan
            </button>
        </div>
    </form>
</template>
