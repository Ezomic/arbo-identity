<script setup lang="ts">
import { Form, Head, Link, router } from '@inertiajs/vue3';
import { CheckCircle, KeyRound, ShieldAlert } from '@lucide/vue';
import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasskeyItem from '@/components/PasskeyItem.vue';
import PasskeyRegister from '@/components/PasskeyRegister.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import setup2fa from '@/routes/2fa';
import { destroy as destroyPasskey } from '@/routes/passkey';
import { edit } from '@/routes/security';
import type { Passkey } from '@/types/auth';

defineProps<{
    passwordRules: string;
    canManageTwoFactor: boolean;
    canManagePasskeys: boolean;
    passkeys: Passkey[];
    twoFactorEnabled?: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Security settings',
                href: edit(),
            },
        ],
    },
});

function refreshPasskeys() {
    router.reload({ only: ['passkeys'] });
}

function removePasskey(id: number, onError: () => void) {
    router.delete(destroyPasskey(id).url, {
        preserveScroll: true,
        onError,
    });
}
</script>

<template>
    <Head title="Security settings" />

    <h1 class="sr-only">Security settings</h1>

    <div class="space-y-8">
        <div class="space-y-6">
            <Heading
                variant="small"
                title="Update password"
                description="Ensure your account is using a long, random password to stay secure"
            />

            <Form
                v-bind="SecurityController.update.form()"
                :options="{
                    preserveScroll: true,
                }"
                reset-on-success
                :reset-on-error="[
                    'password',
                    'password_confirmation',
                    'current_password',
                ]"
                class="space-y-6"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="current_password">Current password</Label>
                    <PasswordInput
                        id="current_password"
                        name="current_password"
                        class="mt-1 block w-full"
                        autocomplete="current-password"
                        placeholder="Current password"
                    />
                    <InputError :message="errors.current_password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">New password</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                        placeholder="New password"
                        :passwordrules="passwordRules"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                        placeholder="Confirm password"
                        :passwordrules="passwordRules"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <div class="flex items-center gap-4">
                    <Button
                        :disabled="processing"
                        data-test="update-password-button"
                    >
                        Save
                    </Button>
                </div>
            </Form>
        </div>

        <div v-if="canManageTwoFactor" class="space-y-4 border-t pt-8">
            <Heading
                variant="small"
                title="Two-factor authentication"
                description="Your organisation requires 2FA for all accounts"
            />

            <div
                v-if="twoFactorEnabled"
                class="flex items-center gap-3 rounded-lg border p-4"
            >
                <CheckCircle class="size-5 shrink-0 text-green-600" />
                <div>
                    <div class="font-medium">Authenticator app enabled</div>
                    <div class="text-sm text-muted-foreground">
                        You'll be asked for a code from your authenticator app
                        when you sign in.
                    </div>
                </div>
                <Button
                    variant="outline"
                    size="sm"
                    class="ml-auto shrink-0"
                    as-child
                >
                    <Link :href="setup2fa.setup()">Manage</Link>
                </Button>
            </div>

            <div v-else class="flex items-center gap-3 rounded-lg border p-4">
                <ShieldAlert class="size-5 shrink-0 text-amber-600" />
                <div>
                    <div class="font-medium">
                        Two-factor authentication not set up
                    </div>
                    <div class="text-sm text-muted-foreground">
                        Required by your organisation before you can access any
                        portal.
                    </div>
                </div>
                <Button size="sm" class="ml-auto shrink-0" as-child>
                    <Link :href="setup2fa.setup()">Set up now</Link>
                </Button>
            </div>
        </div>

        <div v-if="canManagePasskeys" class="space-y-4 border-t pt-8">
            <Heading
                variant="small"
                title="Passkeys"
                description="Sign in with your device's fingerprint, face recognition, or security key instead of a password"
            />

            <div v-if="passkeys.length > 0" class="rounded-lg border">
                <PasskeyItem
                    v-for="passkey in passkeys"
                    :key="passkey.id"
                    :passkey="passkey"
                    @remove="removePasskey"
                />
            </div>
            <div
                v-else
                class="flex items-center gap-3 rounded-lg border border-dashed p-4 text-sm text-muted-foreground"
            >
                <KeyRound class="size-5 shrink-0" />
                No passkeys registered yet.
            </div>

            <PasskeyRegister @success="refreshPasskeys" />
        </div>
    </div>
</template>
