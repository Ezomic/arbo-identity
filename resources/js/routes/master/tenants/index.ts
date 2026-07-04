import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import users from './users'
/**
* @see \App\Http\Controllers\Master\TenantController::index
* @see app/Http/Controllers/Master/TenantController.php:16
* @route '/master/tenants'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/master/tenants',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Master\TenantController::index
* @see app/Http/Controllers/Master/TenantController.php:16
* @route '/master/tenants'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Master\TenantController::index
* @see app/Http/Controllers/Master/TenantController.php:16
* @route '/master/tenants'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Master\TenantController::index
* @see app/Http/Controllers/Master/TenantController.php:16
* @route '/master/tenants'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Master\TenantController::index
* @see app/Http/Controllers/Master/TenantController.php:16
* @route '/master/tenants'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Master\TenantController::index
* @see app/Http/Controllers/Master/TenantController.php:16
* @route '/master/tenants'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Master\TenantController::index
* @see app/Http/Controllers/Master/TenantController.php:16
* @route '/master/tenants'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Master\TenantController::create
* @see app/Http/Controllers/Master/TenantController.php:26
* @route '/master/tenants/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/master/tenants/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Master\TenantController::create
* @see app/Http/Controllers/Master/TenantController.php:26
* @route '/master/tenants/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Master\TenantController::create
* @see app/Http/Controllers/Master/TenantController.php:26
* @route '/master/tenants/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Master\TenantController::create
* @see app/Http/Controllers/Master/TenantController.php:26
* @route '/master/tenants/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Master\TenantController::create
* @see app/Http/Controllers/Master/TenantController.php:26
* @route '/master/tenants/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Master\TenantController::create
* @see app/Http/Controllers/Master/TenantController.php:26
* @route '/master/tenants/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Master\TenantController::create
* @see app/Http/Controllers/Master/TenantController.php:26
* @route '/master/tenants/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Master\TenantController::store
* @see app/Http/Controllers/Master/TenantController.php:31
* @route '/master/tenants'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/master/tenants',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Master\TenantController::store
* @see app/Http/Controllers/Master/TenantController.php:31
* @route '/master/tenants'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Master\TenantController::store
* @see app/Http/Controllers/Master/TenantController.php:31
* @route '/master/tenants'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Master\TenantController::store
* @see app/Http/Controllers/Master/TenantController.php:31
* @route '/master/tenants'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Master\TenantController::store
* @see app/Http/Controllers/Master/TenantController.php:31
* @route '/master/tenants'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Master\TenantController::show
* @see app/Http/Controllers/Master/TenantController.php:45
* @route '/master/tenants/{tenant}'
*/
export const show = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/master/tenants/{tenant}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Master\TenantController::show
* @see app/Http/Controllers/Master/TenantController.php:45
* @route '/master/tenants/{tenant}'
*/
show.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Master\TenantController::show
* @see app/Http/Controllers/Master/TenantController.php:45
* @route '/master/tenants/{tenant}'
*/
show.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Master\TenantController::show
* @see app/Http/Controllers/Master/TenantController.php:45
* @route '/master/tenants/{tenant}'
*/
show.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Master\TenantController::show
* @see app/Http/Controllers/Master/TenantController.php:45
* @route '/master/tenants/{tenant}'
*/
const showForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Master\TenantController::show
* @see app/Http/Controllers/Master/TenantController.php:45
* @route '/master/tenants/{tenant}'
*/
showForm.get = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Master\TenantController::show
* @see app/Http/Controllers/Master/TenantController.php:45
* @route '/master/tenants/{tenant}'
*/
showForm.head = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Master\TenantController::update
* @see app/Http/Controllers/Master/TenantController.php:75
* @route '/master/tenants/{tenant}'
*/
export const update = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/master/tenants/{tenant}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Master\TenantController::update
* @see app/Http/Controllers/Master/TenantController.php:75
* @route '/master/tenants/{tenant}'
*/
update.url = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{tenant}', parsedArgs.tenant.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Master\TenantController::update
* @see app/Http/Controllers/Master/TenantController.php:75
* @route '/master/tenants/{tenant}'
*/
update.put = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Master\TenantController::update
* @see app/Http/Controllers/Master/TenantController.php:75
* @route '/master/tenants/{tenant}'
*/
update.patch = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Master\TenantController::update
* @see app/Http/Controllers/Master/TenantController.php:75
* @route '/master/tenants/{tenant}'
*/
const updateForm = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Master\TenantController::update
* @see app/Http/Controllers/Master/TenantController.php:75
* @route '/master/tenants/{tenant}'
*/
updateForm.put = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Master\TenantController::update
* @see app/Http/Controllers/Master/TenantController.php:75
* @route '/master/tenants/{tenant}'
*/
updateForm.patch = (args: { tenant: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

const tenants = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    update: Object.assign(update, update),
    users: Object.assign(users, users),
}

export default tenants