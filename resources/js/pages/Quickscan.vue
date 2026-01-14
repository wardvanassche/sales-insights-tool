<script setup lang="ts">
import Form from '@/components/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { quickscan } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quickscan',
        href: quickscan().url,
    },
];
</script>

<template>
    <Head title="Quickscan" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col h-full gap-10 p-6 lg:flex-row">
            <div class="lg:flex-1">
                <div class="rounded-xl border bg-card p-6 shadow-sm">
                    <Form />
                </div>
            </div>

            <div class="flex-1">
                <div
                    class="flex h-full flex-col rounded-xl border bg-card p-6 shadow-sm"
                >
                    <h1 class="mb-4 font-semibold text-foreground">
                        Analyse
                    </h1>

                    <div
                        class="flex flex-1 items-center justify-center rounded-lg
                       bg-muted/40 text-muted-foreground"
                    >
                        Start een quickscan om inzichten te bekijken
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
