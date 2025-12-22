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
        <div class="flex flex-row gap-10 p-4">
            <div class="flex-1">
                <Form />
            </div>

            <div class="flex-1">
                <h1>Analyse</h1>
            </div>
        </div>
    </AppLayout>
</template>
