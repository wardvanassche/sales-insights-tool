<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
defineProps({
    user: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-col items-center justify-center text-center mx-6"
        >
            <div
                class="mb-6 flex h-28 w-28 items-center justify-center rounded-lg bg-primary/10 ring-2 ring-primary/20"
            >
                <svg
                    viewBox="0 0 2000 2000"
                    class="h-28 w-28 text-primary dark:text-white"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        transform="translate(1283,591)"
                        d="m0 0h145l6 3 7 7 2 4v802l-4 8-6 5-7 3h-9l-58-14-40-9-40-8-6-3-7-8-2-8v-765l4-8 5-5 6-3z"
                        fill="currentColor"
                    />
                    <path
                        transform="translate(1033,796)"
                        d="m0 0h115l32 1 6 4 7 8 2 5v542l-3 7-6 8-8 4h-12l-63-7-55-3-18-1-5-3-9-11-1-2-1-11v-448l1-75 4-8 8-8z"
                        fill="currentColor"
                    />
                    <path
                        transform="translate(783,937)"
                        d="m0 0h147l7 3 7 7 3 6v394l-3 7-9 8-6 2-57 3-40 3-42 5h-8l-9-7-4-5-2-6v-401l3-9 8-8z"
                        fill="currentColor"
                    />
                    <path
                        transform="translate(537,1077)"
                        d="m0 0h139l10 2 5 4 5 8 1 4v282l-4 8-7 6-24 5-51 10-64 16-5 1h-10l-8-6-5-6-2-8v-308l4-8 7-7z"
                        fill="currentColor"
                    />
                    <path
                        transform="translate(934,1409)"
                        d="m0 0h103l66 4 58 5 45 6 44 7 64 12 62 14 41 11 55 16 35 11 29 10 47 17 20 8 34 14 1 2-9-1-36-10-50-10-36-7-63-11-82-12-70-8-40-4-64-5-66-4-47-2-39-1h-111l-95 3-103 7-41 4-96 11-87 13-61 11-75 15-40 10-4-1 25-10 14-5 28-11 28-10 20-7 36-12 28-9 38-11 35-9 22-6 44-10 73-14 76-11 44-4 55-4z"
                        fill="currentColor"
                    />
                    <path
                        transform="translate(1461,337)"
                        d="m0 0h3l-3 10-6 22-9 36-10 39-6 22-9 35-2 6-4-2-13-12-8-7-12-10h-5l-11 9-12 11-11 9-13 10-14 11-44 33-11 8-23 16-11 7-21 14-19 12-13 8-40 24-25 14-21 12-22 12-16 8-41 21-43 21-28 13-27 12-41 18-26 11-34 14-50 20-26 10-84 30-30 10-64 22-43 14-6 1 3-3 20-8 19-7 19-8 25-10 24-10 29-13 30-13 25-11 33-15 26-12 27-13 23-11 26-13 19-10 33-17 22-12 24-13 21-12 22-12 22-13 17-10 34-21 19-12 63-42 18-13 32-24 18-14 25-20 11-9 10-9 11-9 13-12 8-7 12-11 15-14 13-13 8-7 2-3-15-14-11-10-5-5-4-5 36-12 25-8 36-11 51-17z"
                        fill="currentColor"
                    />
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-foreground">
                Welkom, {{ user.name }}
            </h1>

            <p class="mt-3 max-w-xl text-muted-foreground">
                Maak in enkele minuten een
                <strong>AI-gedreven sales quickscan</strong>
                en bereid je optimaal voor op je volgende gesprek.
            </p>

            <div
                class="mx-auto mt-8 w-full max-w-md rounded-xl border bg-card p-6 text-center shadow-sm"
            >
                <h2 class="mb-3 text-lg font-medium text-foreground">
                    Wat levert een quickscan je op?
                </h2>

                <ul class="space-y-2 text-sm text-left text-muted-foreground">
                    <li>• Helder bedrijfsprofiel met kerngegevens</li>
                    <li>• Inzicht in commerciële kansen</li>
                    <li>• Mogelijke pijnpunten en risico’s</li>
                    <li>• Concrete input voor je eerste salesgesprek</li>
                </ul>
            </div>

            <a href="quickscan">
                <Button class="mt-6"> Start een quickscan </Button>
            </a>
        </div>
    </AppLayout>
</template>
