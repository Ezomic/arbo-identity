<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { CheckCircle, KeyRound, ShieldAlert } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import PasskeyItem from '@/components/PasskeyItem.vue';
import PasskeyRegister from '@/components/PasskeyRegister.vue';
import { Button } from '@/components/ui/button';
import setup2fa from '@/routes/2fa';
import { destroy as destroyPasskey } from '@/routes/passkey';
import { edit } from '@/routes/security';
import type { Passkey } from '@/types/auth';

defineProps<{
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
        <div v-if="canManageTwoFactor" class="space-y-4">
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
