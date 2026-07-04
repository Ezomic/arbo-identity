<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { index, create, show } from '@/routes/master/tenants';

type Tenant = {
    id: string;
    name: string;
    slug: string;
    status: string;
    require_2fa: boolean;
    users_count: number;
    created_at: string;
};

defineProps<{ tenants: Tenant[] }>();

defineOptions({ layout: { breadcrumbs: [] } });

function formatDate(dateStr: string): string {
    return new Date(dateStr).toLocaleDateString('nl-NL', { day: 'numeric', month: 'short', year: 'numeric' });
}
</script>

<template>
    <Head title="Tenants" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex items-center justify-between">
            <Heading title="Tenants" description="All tenants on this platform." />
            <Button as-child>
                <Link :href="create().url">New tenant</Link>
            </Button>
        </div>

        <div class="rounded-lg border">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-muted/40">
                        <th class="px-4 py-3 text-left font-medium">Name</th>
                        <th class="px-4 py-3 text-left font-medium">Slug</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">2FA</th>
                        <th class="px-4 py-3 text-left font-medium">Users</th>
                        <th class="px-4 py-3 text-left font-medium">Created</th>
                        <th class="px-4 py-3" />
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="tenants.length === 0">
                        <td colspan="7" class="px-4 py-8 text-center text-muted-foreground">No tenants yet.</td>
                    </tr>
                    <tr v-for="tenant in tenants" :key="tenant.id" class="border-b last:border-0 hover:bg-muted/20">
                        <td class="px-4 py-3 font-medium">{{ tenant.name }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-muted-foreground">{{ tenant.slug }}</td>
                        <td class="px-4 py-3">
                            <Badge :variant="tenant.status === 'active' ? 'default' : 'destructive'">
                                {{ tenant.status }}
                            </Badge>
                        </td>
                        <td class="px-4 py-3">
                            <Badge v-if="tenant.require_2fa" variant="outline">Required</Badge>
                            <span v-else class="text-muted-foreground">—</span>
                        </td>
                        <td class="px-4 py-3">{{ tenant.users_count }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ formatDate(tenant.created_at) }}</td>
                        <td class="px-4 py-3 text-right">
                            <Button variant="ghost" size="sm" as-child>
                                <Link :href="show(tenant.id).url">Manage</Link>
                            </Button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
