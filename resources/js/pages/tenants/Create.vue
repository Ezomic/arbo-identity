<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/tenants';

defineOptions({
    layout: {
        title: 'Create your organization',
        description:
            'Set up your case management company and your own admin account',
    },
});
</script>

<template>
    <Head title="Create your organization" />

    <Form
        v-bind="store.form()"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="tenant_name">Organization name</Label>
                <Input
                    id="tenant_name"
                    name="tenant_name"
                    required
                    autofocus
                    placeholder="Acme Arbodienst"
                />
                <InputError :message="errors.tenant_name" />
            </div>

            <div class="grid gap-2">
                <Label for="name">Your name</Label>
                <Input id="name" name="name" required placeholder="Full name" />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="username">Username</Label>
                <Input
                    id="username"
                    name="username"
                    required
                    autocomplete="username"
                    placeholder="What you'll log in with"
                />
                <InputError :message="errors.username" />
            </div>

            <div class="grid gap-2">
                <Label for="email">Your email address</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                />
                <InputError :message="errors.email" />
            </div>

            <Button type="submit" class="mt-4 w-full" :disabled="processing">
                <Spinner v-if="processing" />
                Create organization
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink :href="login()">Log in</TextLink>
        </div>
    </Form>
</template>
