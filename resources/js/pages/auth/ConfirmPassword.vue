<script setup lang="ts">
import { usePasskeyVerify } from '@laravel/passkeys/vue';
import { KeyRound } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import passkey from '@/routes/passkey';

defineOptions({
    layout: {
        title: 'Confirm your passkey',
        description:
            'This is a secure area of the application. Please re-verify your passkey before continuing.',
    },
});

const {
    verify,
    isLoading: passkeyLoading,
    error: passkeyError,
} = usePasskeyVerify({
    routes: {
        submit: passkey.confirm.url(),
    },
    onSuccess: (response) => {
        if (response.redirect) {
            window.location.href = response.redirect;
        }
    },
});
</script>

<template>
    <div class="space-y-6">
        <Button
            type="button"
            class="w-full"
            :disabled="passkeyLoading"
            data-test="confirm-passkey-button"
            @click="verify"
        >
            <Spinner v-if="passkeyLoading" />
            <KeyRound v-else class="size-4" />
            Confirm with your passkey
        </Button>
        <InputError :message="passkeyError ?? undefined" />
    </div>
</template>
