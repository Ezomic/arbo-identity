<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { index, update } from '@/routes/master/tenants';
import { store as storeUser, destroy as destroyUser } from '@/routes/master/tenants/users';
import { impersonate } from '@/routes/master';

type Tenant = {
    id: string;
    name: string;
    slug: string;
    status: string;
    require_2fa: boolean;
    created_at: string;
};

type UserType = {
    id: string;
    name: string;
    app_slug: string;
};

type TenantUser = {
    uuid: string;
    name: string;
    email: string;
    user_type: string | null;
    user_type_id: string | null;
    role: string | null;
    scope_id: string | null;
    last_login_at: string | null;
    last_login_ip: string | null;
    created_at: string;
};

const props = defineProps<{
    tenant: Tenant;
    users: TenantUser[];
    userTypes: UserType[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Tenants', href: index().url }],
    },
});

const settingsForm = useForm({
    name: props.tenant.name,
    status: props.tenant.status,
    require_2fa: props.tenant.require_2fa,
});

const userForm = useForm({
    name: '',
    email: '',
    user_type_id: props.userTypes[0]?.id ?? '',
    scope_id: '',
});

const showAddUser = ref(false);

function saveSettings() {
    settingsForm.patch(update(props.tenant.id).url);
}

function addUser() {
    userForm.post(storeUser(props.tenant.id).url, {
        onSuccess: () => {
            userForm.reset();
            showAddUser.value = false;
        },
    });
}

function deleteUser(uuid: string) {
    if (!confirm('Delete this user? This cannot be undone.')) return;
    useForm({}).delete(destroyUser({ tenant: props.tenant.id, uuid }).url);
}

function startImpersonate(uuid: string) {
    useForm({}).post(impersonate(uuid).url);
}

function formatDate(dateStr: string): string {
    return new Date(dateStr).toLocaleDateString('nl-NL', { day: 'numeric', month: 'short', year: 'numeric' });
}
</script>

<template>
    <Head :title="tenant.name" />

    <div class="flex flex-col gap-6 p-4">
        <Heading :title="tenant.name" :description="`/${tenant.slug}`" />

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Settings -->
            <div class="lg:col-span-1">
                <div class="rounded-lg border p-4">
                    <h2 class="mb-4 font-medium">Settings</h2>
                    <form class="space-y-4" @submit.prevent="saveSettings">
                        <div class="grid gap-2">
                            <Label for="tenant-name">Name</Label>
                            <Input id="tenant-name" v-model="settingsForm.name" required />
                            <InputError :message="settingsForm.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="tenant-status">Status</Label>
                            <Select v-model="settingsForm.status">
                                <SelectTrigger id="tenant-status">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="active">Active</SelectItem>
                                    <SelectItem value="suspended">Suspended</SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="settingsForm.errors.status" />
                        </div>

                        <div class="flex items-center gap-2">
                            <Checkbox id="require-2fa" v-model:checked="settingsForm.require_2fa" />
                            <Label for="require-2fa">Require 2FA</Label>
                        </div>

                        <Button type="submit" size="sm" :disabled="settingsForm.processing">Save</Button>
                    </form>
                </div>
            </div>

            <!-- Users -->
            <div class="lg:col-span-2">
                <div class="rounded-lg border p-4">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="font-medium">Users</h2>
                        <Button size="sm" variant="outline" @click="showAddUser = !showAddUser">
                            {{ showAddUser ? 'Cancel' : 'Add user' }}
                        </Button>
                    </div>

                    <!-- Add user form -->
                    <div v-if="showAddUser" class="mb-4 rounded-md border bg-muted/30 p-4">
                        <h3 class="mb-3 text-sm font-medium">New user</h3>
                        <form class="grid gap-3" @submit.prevent="addUser">
                            <div class="grid gap-1.5">
                                <Label>Name</Label>
                                <Input v-model="userForm.name" required placeholder="Full name" />
                                <InputError :message="userForm.errors.name" />
                            </div>
                            <div class="grid gap-1.5">
                                <Label>Email</Label>
                                <Input v-model="userForm.email" type="email" required placeholder="user@example.com" />
                                <InputError :message="userForm.errors.email" />
                            </div>
                            <div class="grid gap-1.5">
                                <Label>User type</Label>
                                <Select v-model="userForm.user_type_id">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="ut in userTypes" :key="ut.id" :value="ut.id">
                                            {{ ut.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="userForm.errors.user_type_id" />
                            </div>
                            <Button type="submit" size="sm" :disabled="userForm.processing">Create user</Button>
                        </form>
                    </div>

                    <div v-if="users.length === 0" class="text-sm text-muted-foreground">No users yet.</div>

                    <table v-else class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-2 text-left font-medium">Name</th>
                                <th class="pb-2 text-left font-medium">Email</th>
                                <th class="pb-2 text-left font-medium">Type</th>
                                <th class="pb-2 text-left font-medium">Role</th>
                                <th class="pb-2 text-left font-medium">Last login</th>
                                <th class="pb-2 text-left font-medium">Last IP</th>
                                <th class="pb-2 text-left font-medium">Created</th>
                                <th class="pb-2" />
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.uuid" class="border-b last:border-0">
                                <td class="py-2.5 pr-3 font-medium">{{ user.name }}</td>
                                <td class="py-2.5 pr-3 text-muted-foreground">{{ user.email }}</td>
                                <td class="py-2.5 pr-3">
                                    <Badge variant="outline" class="text-xs">{{ user.user_type ?? '—' }}</Badge>
                                </td>
                                <td class="py-2.5 pr-3 text-muted-foreground">{{ user.role ?? '—' }}</td>
                                <td class="py-2.5 pr-3 text-muted-foreground">{{ user.last_login_at ? formatDate(user.last_login_at) : '—' }}</td>
                                <td class="py-2.5 pr-3 font-mono text-xs text-muted-foreground">{{ user.last_login_ip ?? '—' }}</td>
                                <td class="py-2.5 pr-3 text-muted-foreground">{{ formatDate(user.created_at) }}</td>
                                <td class="py-2.5 text-right">
                                    <div class="flex justify-end gap-1">
                                        <Button
                                            v-if="user.user_type_id"
                                            size="sm"
                                            variant="outline"
                                            @click="startImpersonate(user.uuid)"
                                        >
                                            Impersonate
                                        </Button>
                                        <Button
                                            size="sm"
                                            variant="ghost"
                                            class="text-destructive"
                                            @click="deleteUser(user.uuid)"
                                        >
                                            Delete
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
