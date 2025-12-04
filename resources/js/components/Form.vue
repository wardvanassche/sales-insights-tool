<script setup lang="ts">

// defineProps<Props>();
// no props needed for now
</script>

<template>
    <form class="space-y-4 p-4">
        <div class="flex flex-col">
            <label class="mb-1 font-semibold">Company Name</label>
            <input
                type="text"
                placeholder="Enter company name"
                class="border rounded px-3 py-2"
            />
        </div>

        <div class="flex flex-col">
            <label class="mb-1 font-semibold">Company URL</label
            ><input
            type="text"
            placeholder="https://example.com"
            class="border rounded px-3 py-2"
        />
        </div>

        <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
            Submit
        </button>
    </form>
</template>
