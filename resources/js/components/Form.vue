<script setup lang="ts">
// defineProps<Props>();
// no props needed for now
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';

</script>

<template>
    <form action="#" method="POST">
        <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <Label
                    for="name"
                    class="block text-sm/6 font-semibold text-gray-900 dark:text-gray-100"
                >
                    Bedrijfsnaam
                </Label>
                <div class="mt-2.5">
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        placeholder="Voer een bedrijfsnaam in"
                    />
                </div>
            </div>
            <div class="sm:col-span-2">
                <Label
                    for="url"
                    class="block text-sm/6 font-semibold text-gray-900 dark:text-gray-100"
                >
                    URL
                </Label>
                <div class="mt-2.5">
                    <Input
                        type="url"
                        name="url"
                        id="url"
                        placeholder="https://every-day.nl"
                    />
                </div>
            </div>
        </div>
        <div class="mt-10 flex justify-end">
            <Button
                type="submit"
                :disabled="processing"
            >
                Quickscan
            </Button>
        </div>
    </form>
</template>
