<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import { CheckCircle, KeyRound, ShieldCheck } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import PasskeyRegister from '@/components/PasskeyRegister.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: {
        title: 'Set up two-factor authentication',
        description: 'Your organisation requires 2FA. Set it up to continue.',
    },
});

defineProps<{
    hasPasskeys: boolean;
    hasTotpEnabled: boolean;
    qrCodeSvg: string | null;
    recoveryCodes: string[];
}>();

function refreshPasskeyStatus() {
    router.reload({ only: ['hasPasskeys'] });
}
</script>

<template>
    <Head title="Set up two-factor authentication" />

    <div class="space-y-6">
        <div
            v-if="hasTotpEnabled || hasPasskeys"
            class="flex items-center gap-3 rounded-lg border p-4"
        >
            <CheckCircle class="size-5 shrink-0 text-green-600" />
            <div>
                <div class="font-medium">
                    Two-factor authentication is active
                </div>
                <div class="text-sm text-muted-foreground">
                    <span v-if="hasTotpEnabled"
                        >TOTP authenticator app enabled.</span
                    >
                    <span v-if="hasPasskeys"> Passkey registered.</span>
                </div>
            </div>
        </div>

        <div v-if="!hasTotpEnabled" class="space-y-4">
            <div class="flex items-center gap-2">
                <ShieldCheck class="size-5 text-muted-foreground" />
                <h2 class="font-semibold">Authenticator app (TOTP)</h2>
            </div>

            <p class="text-sm text-muted-foreground">
                Scan this QR code with your authenticator app (Google
                Authenticator, 1Password, Authy, etc.) then enter the 6-digit
                code to confirm.
            </p>

            <div
                v-if="qrCodeSvg"
                class="rounded-lg border p-4"
                v-html="qrCodeSvg"
            />

            <Form
                action="/user/confirmed-two-factor-authentication"
                method="post"
                v-slot="{ errors, processing }"
                class="space-y-3"
            >
                <div class="grid gap-2">
                    <Label for="code">Confirmation code</Label>
                    <Input
                        id="code"
                        name="code"
                        type="text"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        placeholder="123456"
                    />
                    <InputError :message="errors.code" />
                </div>
                <Button type="submit" :disabled="processing"
                    >Confirm and enable</Button
                >
            </Form>
        </div>

        <div
            v-if="!hasTotpEnabled && !hasPasskeys"
            class="flex items-center gap-3"
        >
            <div class="h-px flex-1 bg-border" />
            or
            <div class="h-px flex-1 bg-border" />
        </div>

        <div v-if="!hasPasskeys" class="space-y-4">
            <div class="flex items-center gap-2">
                <KeyRound class="size-5 text-muted-foreground" />
                <h2 class="font-semibold">Passkey</h2>
            </div>

            <p class="text-sm text-muted-foreground">
                Use your device's fingerprint, face recognition, or a security
                key instead of an authenticator app.
            </p>

            <PasskeyRegister @success="refreshPasskeyStatus" />
        </div>

        <div v-if="recoveryCodes.length > 0" class="space-y-3">
            <h3 class="font-medium">Recovery codes</h3>
            <p class="text-sm text-muted-foreground">
                Save these recovery codes in a safe place. Each can be used once
                if you lose access to your authenticator.
            </p>
            <div class="rounded-lg bg-muted p-4 font-mono text-sm">
                <div v-for="code in recoveryCodes" :key="code">{{ code }}</div>
            </div>
        </div>
    </div>
</template>
