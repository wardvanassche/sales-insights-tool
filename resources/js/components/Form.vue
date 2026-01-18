<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { reactive } from 'vue';

const emit = defineEmits(['submit']);

const form = reactive({
    company_name: '',
    company_url: '',
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
        </div>
        <div class="mt-10 flex">
            <Button type="submit"> Quickscan </Button>
        </div>
    </form>
</template>
