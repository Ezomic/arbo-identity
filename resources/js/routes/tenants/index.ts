import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::create
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:14
* @route '/tenants/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/tenants/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::create
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:14
* @route '/tenants/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::create
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:14
* @route '/tenants/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::create
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:14
* @route '/tenants/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::create
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:14
* @route '/tenants/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::create
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:14
* @route '/tenants/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::create
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:14
* @route '/tenants/create'
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
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::store
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:19
* @route '/tenants'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/tenants',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::store
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:19
* @route '/tenants'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::store
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:19
* @route '/tenants'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::store
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:19
* @route '/tenants'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tenants\TenantRegistrationController::store
* @see app/Http/Controllers/Tenants/TenantRegistrationController.php:19
* @route '/tenants'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const tenants = {
    create: Object.assign(create, create),
    store: Object.assign(store, store),
}

export default tenants