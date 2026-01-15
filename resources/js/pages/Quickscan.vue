<script setup lang="ts">
const props = defineProps<{
    analysis?: string;
}>();

import Form from '@/components/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { quickscan } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quickscan',
        href: quickscan().url,
    },
];

const form = useForm({
    company_name: '',
    company_url: '',
});

const handleSubmit = (data) => {
    form.company_name = data.company_name;
    form.company_url = data.company_url;

    form.post('/quickscan');

    console.log(data);
};
</script>

<template>
    <Head title="Quickscan" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-col gap-10 p-6 lg:flex-row">
            <div class="lg:flex-1">
                <div class="rounded-xl border bg-card p-6 shadow-sm">
                    <Form @submit="handleSubmit" />
                </div>
            </div>

            <div class="flex-1">
                <div
                    v-if="props.analysis"
                    class="flex-1 overflow-auto rounded-lg bg-muted/40 p-4 text-sm"
                >
                    <pre class="whitespace-pre-wrap">
        {{ props.analysis }}
    </pre
                    >
                </div>

                <div
                    v-else
                    class="flex flex-1 items-center justify-center rounded-lg bg-muted/40 text-muted-foreground"
                >
                    Start een quickscan om inzichten te bekijken
                </div>
            </div>
        </div>
    </AppLayout>
</template>
