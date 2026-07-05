<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: {
        title: 'Two-factor authentication',
        description: 'Enter the code from your authenticator app or a recovery code',
    },
});

const useRecoveryCode = ref(false);
</script>

<template>
    <Head title="Two-factor authentication" />

    <Form
        action="/two-factor-challenge"
        method="post"
        v-slot="{ errors, processing }"
        class="space-y-4"
    >
        <div v-if="!useRecoveryCode" class="grid gap-2">
            <Label for="code">Authentication code</Label>
            <Input
                id="code"
                name="code"
                type="text"
                inputmode="numeric"
                autocomplete="one-time-code"
                autofocus
                placeholder="123456"
            />
            <InputError :message="errors.code" />
        </div>

        <div v-else class="grid gap-2">
            <Label for="recovery_code">Recovery code</Label>
            <Input
                id="recovery_code"
                name="recovery_code"
                type="text"
                autocomplete="one-time-code"
                autofocus
                placeholder="xxxxx-xxxxx"
            />
            <InputError :message="errors.recovery_code" />
        </div>

        <Button type="submit" class="w-full" :disabled="processing">
            Verify
        </Button>

        <button
            type="button"
            class="text-muted-foreground w-full text-center text-sm underline-offset-4 hover:underline"
            @click="useRecoveryCode = !useRecoveryCode"
        >
            {{ useRecoveryCode ? 'Use authentication code' : 'Use a recovery code' }}
        </button>
    </Form>
</template>
