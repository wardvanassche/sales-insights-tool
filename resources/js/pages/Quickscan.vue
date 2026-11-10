<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    analysis?: string;
    error?: string;
}>();

import Form from '@/components/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { quickscan } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import MarkdownIt from 'markdown-it';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quickscan',
        href: quickscan().url,
    },
];

const form = useForm({
    company_name: '',
    company_url: '',
    llm_model: 'openai',
});

const handleSubmit = (data: {
    company_name: string;
    company_url: string;
    llm_model: string;
}) => {
    form.company_name = data.company_name;
    form.company_url = data.company_url;
    form.llm_model = data.llm_model;

    form.post('/quickscan');
};

// No raw HTML: the analysis is LLM output based on external data
const md = new MarkdownIt({
    html: false,
    linkify: true,
    breaks: true,
    typographer: true,
});

// Enable list and other plugins
md.enable(['table', 'strikethrough']);

// Custom rendering for better spacing
md.renderer.rules.bullet_list_open = () => '<ul class="list-disc">';
md.renderer.rules.ordered_list_open = () => '<ol class="list-decimal">';
md.renderer.rules.heading_open = (tokens, idx) => {
    const level = tokens[idx].tag;
    const classes =
        level === 'h1'
            ? 'text-lg font-semibold mt-6 mb-3'
            : level === 'h2'
              ? 'text-base font-semibold mt-5 mb-3 border-b pb-2'
              : 'text-sm font-semibold mt-4 mb-2';
    return `<${level} class="${classes}">`;
};

const renderedAnalysis = computed(() => md.render(props.analysis ?? ''));
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
                    v-if="error"
                    class="flex flex-1 items-center justify-center rounded-lg bg-destructive/10 p-6 text-sm text-destructive"
                >
                    {{ error }}
                </div>

                <div
                    v-else-if="analysis"
                    class="flex-1 overflow-auto rounded-lg bg-muted/40 p-6 text-sm"
                >
                    <div
                        v-html="renderedAnalysis"
                        class="prose prose-sm dark:prose-invert max-w-none"
                    ></div>
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
