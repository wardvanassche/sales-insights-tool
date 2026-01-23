<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { reactive } from 'vue';

const emit = defineEmits(['submit']);

const form = reactive({
    company_name: '',
    company_url: '',
    llm_model: 'openai',
});

const submit = () => {
    if (!form.company_name && !form.company_url) {
        alert('Vul minimaal een bedrijfsnaam of een URL in.');
        return;
    }

    emit('submit', { ...form });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <Label for="name" class="block text-sm/6"> Bedrijfsnaam </Label>
                <div class="mt-2.5">
                    <Input
                        v-model="form.company_name"
                        type="text"
                        placeholder="Voer een bedrijfsnaam in"
                    />
                </div>
                <p
                    v-if="$page.props.errors.company_name"
                    class="text-sm text-red-500"
                >
                    {{ $page.props.errors.company_name }}
                </p>
            </div>
            <div class="sm:col-span-2">
                <Label for="url" class="block text-sm/6"> URL </Label>
                <div class="mt-2.5">
                    <Input
                        v-model="form.company_url"
                        type="url"
                        placeholder="Voer een url in"
                    />
                </div>
                <p
                    v-if="$page.props.errors.company_url"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ $page.props.errors.company_url }}
                </p>
            </div>
            <div class="sm:col-span-2">
                <Label for="llm_model" class="block text-sm/6"> Model </Label>
                <div class="mt-2.5">
                    <select
                        v-model="form.llm_model"
                        id="llm_model"
                        class="block w-full rounded-md border px-3 py-2 shadow-sm h-9 dark:bg-input/30
                        focus-visible:ring-[1px] focus-visible:ring-[#0A3D62]
                        aria-invalid:border-destructive aria-invalid:ring-destructive/20 sm:text-sm dark:aria-invalid:ring-destructive/40"
                    >
                        <option value="openai">OpenAI (GPT-4 Mini)</option>
                        <option value="ollama">Ollama (Lokaal)</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mt-10 flex">
            <Button type="submit"> Quickscan </Button>
        </div>
    </form>
</template>
