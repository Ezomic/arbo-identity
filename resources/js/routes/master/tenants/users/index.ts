import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Master\TenantUserController::store
* @see app/Http/Controllers/Master/TenantUserController.php:17
* @route '/master/tenants/{tenant}/users'
*/
export const store = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/master/tenants/{tenant}/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Master\TenantUserController::store
* @see app/Http/Controllers/Master/TenantUserController.php:17
* @route '/master/tenants/{tenant}/users'
*/
store.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        tenant: args.tenant,
    }

    return store.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Master\TenantUserController::store
* @see app/Http/Controllers/Master/TenantUserController.php:17
* @route '/master/tenants/{tenant}/users'
*/
store.post = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Master\TenantUserController::store
* @see app/Http/Controllers/Master/TenantUserController.php:17
* @route '/master/tenants/{tenant}/users'
*/
const storeForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Master\TenantUserController::store
* @see app/Http/Controllers/Master/TenantUserController.php:17
* @route '/master/tenants/{tenant}/users'
*/
storeForm.post = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Master\TenantUserController::destroy
* @see app/Http/Controllers/Master/TenantUserController.php:47
* @route '/master/tenants/{tenant}/users/{uuid}'
*/
export const destroy = (args: { tenant: string | number, uuid: string | number } | [tenant: string | number, uuid: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/master/tenants/{tenant}/users/{uuid}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Master\TenantUserController::destroy
* @see app/Http/Controllers/Master/TenantUserController.php:47
* @route '/master/tenants/{tenant}/users/{uuid}'
*/
destroy.url = (args: { tenant: string | number, uuid: string | number } | [tenant: string | number, uuid: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            uuid: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        tenant: args.tenant,
        uuid: args.uuid,
    }

    return destroy.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace('{uuid}', parsedArgs.uuid.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Master\TenantUserController::destroy
* @see app/Http/Controllers/Master/TenantUserController.php:47
* @route '/master/tenants/{tenant}/users/{uuid}'
*/
destroy.delete = (args: { tenant: string | number, uuid: string | number } | [tenant: string | number, uuid: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Master\TenantUserController::destroy
* @see app/Http/Controllers/Master/TenantUserController.php:47
* @route '/master/tenants/{tenant}/users/{uuid}'
*/
const destroyForm = (args: { tenant: string | number, uuid: string | number } | [tenant: string | number, uuid: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Master\TenantUserController::destroy
* @see app/Http/Controllers/Master/TenantUserController.php:47
* @route '/master/tenants/{tenant}/users/{uuid}'
*/
destroyForm.delete = (args: { tenant: string | number, uuid: string | number } | [tenant: string | number, uuid: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const users = {
    store: Object.assign(store, store),
    destroy: Object.assign(destroy, destroy),
}

export default users