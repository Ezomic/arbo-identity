<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { store, index } from '@/routes/master/tenants';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Tenants', href: index().url }] },
});

const form = useForm({
    name: '',
    slug: '',
    status: 'active',
});

function onNameInput() {
    if (!form.slug || form.slug === slugify(form.name.slice(0, -1))) {
        form.slug = slugify(form.name);
    }
}

function slugify(value: string): string {
    return value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');
}

function submit() {
    form.post(store().url);
}
</script>

<template>
    <Head title="New Tenant" />

    <div class="flex flex-col gap-6 p-4">
        <Heading
            title="New Tenant"
            description="Create a new tenant on this platform."
        />

        <form class="max-w-md space-y-4" @submit.prevent="submit">
            <div class="grid gap-2">
                <Label for="name">Organization name</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    @input="onNameInput"
                    required
                    autofocus
                    placeholder="Acme Arbodienst"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="slug">Slug</Label>
                <Input
                    id="slug"
                    v-model="form.slug"
                    required
                    placeholder="acme-arbodienst"
                />
                <p class="text-xs text-muted-foreground">
                    Lowercase letters, numbers, hyphens only.
                </p>
                <InputError :message="form.errors.slug" />
            </div>

            <div class="grid gap-2">
                <Label for="status">Status</Label>
                <Select v-model="form.status">
                    <SelectTrigger id="status">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="active">Active</SelectItem>
                        <SelectItem value="suspended">Suspended</SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.status" />
            </div>

            <Button type="submit" :disabled="form.processing"
                >Create tenant</Button
            >
        </form>
    </div>
</template>
