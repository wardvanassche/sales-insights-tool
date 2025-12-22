<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
defineProps({
    user: Object,
})
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col items-center justify-center h-full text-center">
            <div class="mb-6 flex h-28 items-center justify-center rounded-full">
                <img src="/logo.png" alt="Logo" class="h-full">
            </div>

            <h1 class="text-3xl font-bold text-gray-900">
                Welkom, {{ user.name }}
            </h1>

            <p class="mt-2 text-gray-600 max-w-md">
                Je hebt nog geen quickscans. Start je eerste en ontdek inzichten in minuten.
            </p>

            <a href="quickscan">
                <button
                    class="mt-6 rounded-lg bg-[#0A3D62] px-6 py-3 text-sm font-medium text-white hover:bg-[#08334E]"
                >
                    Maak eerste quickscan
                </button>
            </a>
        </div>
    </AppLayout>
</template>
