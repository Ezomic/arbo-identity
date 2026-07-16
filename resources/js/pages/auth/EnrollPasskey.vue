<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CheckCircle, KeyRound } from '@lucide/vue';
import { ref } from 'vue';
import PasskeyRegister from '@/components/PasskeyRegister.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        title: 'Set up your passkey',
        description: 'Register a passkey to sign in — no password needed.',
    },
});

const passkeyCount = ref(0);
</script>

<template>
    <div class="space-y-6">
        <div
            v-if="passkeyCount === 0"
            class="flex items-center gap-3 rounded-lg border border-dashed p-4 text-sm text-muted-foreground"
        >
            <KeyRound class="size-5 shrink-0" />
            No passkeys registered yet. Add one to continue.
        </div>

        <div v-else class="flex items-center gap-3 rounded-lg border p-4">
            <CheckCircle class="size-5 shrink-0 text-green-600" />
            <div>
                <div class="font-medium">Passkey registered</div>
                <div class="text-sm text-muted-foreground">
                    You can now sign in with it on this device.
                </div>
            </div>
        </div>

        <div v-if="passkeyCount === 1" class="space-y-2 border-t pt-6">
            <p class="text-sm font-medium">Add a backup passkey</p>
            <p class="text-sm text-muted-foreground">
                Register a second passkey from another device or a security key,
                so you're not locked out if you lose this one.
            </p>
        </div>

        <PasskeyRegister @success="passkeyCount++" />

        <div class="flex items-center gap-4 border-t pt-6">
            <Button as-child :disabled="passkeyCount === 0">
                <Link :href="dashboard()">Continue</Link>
            </Button>
        </div>
    </div>
</template>
