<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { usePasskeyVerify } from '@laravel/passkeys/vue';
import { KeyRound } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store as devLoginStore } from '@/routes/dev-login';
import { store } from '@/routes/login';
import passkey from '@/routes/passkey';
import { request } from '@/routes/password';
import { create as createTenant } from '@/routes/tenants';

defineOptions({
    layout: {
        title: 'Log in to your account',
        description: 'Enter your username and password below to log in',
    },
});

const props = defineProps<{
    status?: string;
    canResetPassword: boolean;
    app?: string;
    redirectTo?: string;
    testAccounts: { username: string; label: string }[];
}>();

// The password login goes through Inertia's <Form>, whose Inertia::location()
// response can hop cross-origin to the target portal (see SsoLoginResponse).
// This fetch()-based passkey verify carries the same app/redirect_to along on
// the query string so PasskeyLoginResponse can build the same SSO handoff —
// then we navigate with window.location.href, not router.visit, for the same
// cross-origin reason.
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

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <input type="hidden" name="app" :value="props.app" />
        <input type="hidden" name="redirect_to" :value="props.redirectTo" />

        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="username">Username</Label>
                <Input
                    id="username"
                    type="text"
                    name="username"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="username"
                    placeholder="username"
                />
                <InputError :message="errors.username" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password">Password</Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-sm"
                        :tabindex="5"
                    >
                        Forgot your password?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Password"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span>Remember me</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-4 w-full"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                Log in
            </Button>
        </div>
    </Form>

    <div v-if="passkeySupported" class="mt-6 flex flex-col gap-3">
        <div class="flex items-center gap-3 text-xs text-muted-foreground">
            <div class="h-px flex-1 bg-border" />
            Or
            <div class="h-px flex-1 bg-border" />
        </div>

        <Button
            type="button"
            variant="outline"
            class="w-full"
            :disabled="passkeyLoading"
            @click="verify"
        >
            <Spinner v-if="passkeyLoading" />
            <KeyRound v-else class="size-4" />
            Sign in with a passkey
        </Button>
        <InputError :message="passkeyError ?? undefined" />
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
