<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { usePasskeyVerify } from '@laravel/passkeys/vue';
import { KeyRound } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { store as devLoginStore } from '@/routes/dev-login';
import passkey from '@/routes/passkey';
import { create as createTenant } from '@/routes/tenants';

defineOptions({
    layout: {
        title: 'Log in to your account',
        description: 'Sign in with your passkey below',
    },
});

const props = defineProps<{
    status?: string;
    app?: string;
    redirectTo?: string;
    testAccounts: { username: string; label: string }[];
}>();

// Cross-origin hop after a successful verify (see SsoLoginResponse) — carry
// app/redirect_to along on the query string so PasskeyLoginResponse can
// build the same SSO handoff, then navigate with window.location.href, not
// router.visit, for the same cross-origin reason.
const {
    verify,
    isLoading: passkeyLoading,
    error: passkeyError,
    isSupported: passkeySupported,
} = usePasskeyVerify({
    routes: {
        submit: passkey.login.url({
            query: {
                ...(props.app ? { app: props.app } : {}),
                ...(props.redirectTo ? { redirect_to: props.redirectTo } : {}),
            },
        }),
    },
    onSuccess: (response) => {
        if (response.redirect) {
            window.location.href = response.redirect;
        }
    },
});
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <div v-if="passkeySupported" class="flex flex-col gap-3">
        <Button
            type="button"
            class="w-full"
            :disabled="passkeyLoading"
            data-test="login-button"
            @click="verify"
        >
            <Spinner v-if="passkeyLoading" />
            <KeyRound v-else class="size-4" />
            Sign in with a passkey
        </Button>
        <InputError :message="passkeyError ?? undefined" />
    </div>
    <div v-else class="text-sm text-muted-foreground">
        Passkeys are not supported in this browser. Try a different browser or
        device.
    </div>

    <div class="mt-4 text-center text-sm text-muted-foreground">
        New organization?
        <TextLink :href="createTenant()">Create one</TextLink>
    </div>

    <div v-if="testAccounts.length > 0" class="mt-6 flex flex-col gap-3">
        <div class="flex items-center gap-3 text-xs text-muted-foreground">
            <div class="h-px flex-1 bg-border" />
            Dev shortcut
            <div class="h-px flex-1 bg-border" />
        </div>

        <Form
            v-for="account in testAccounts"
            :key="account.username"
            v-bind="devLoginStore.form()"
            v-slot="{ errors }"
            class="contents"
        >
            <input type="hidden" name="username" :value="account.username" />
            <input type="hidden" name="app" :value="props.app" />
            <input type="hidden" name="redirect_to" :value="props.redirectTo" />
            <Button type="submit" variant="outline" class="w-full">
                Log in as {{ account.label }}
            </Button>
            <InputError :message="errors.username" />
        </Form>
    </div>
</template>
