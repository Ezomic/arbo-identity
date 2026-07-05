import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Master\ImpersonateController::store
* @see app/Http/Controllers/Master/ImpersonateController.php:22
* @route '/master/impersonate/{uuid}'
*/
export const store = (args: { uuid: string | number } | [uuid: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/master/impersonate/{uuid}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Master\ImpersonateController::store
* @see app/Http/Controllers/Master/ImpersonateController.php:22
* @route '/master/impersonate/{uuid}'
*/
store.url = (args: { uuid: string | number } | [uuid: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { uuid: args }
    }

    if (Array.isArray(args)) {
        args = {
            uuid: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        uuid: args.uuid,
    }

    return store.definition.url
            .replace('{uuid}', parsedArgs.uuid.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Master\ImpersonateController::store
* @see app/Http/Controllers/Master/ImpersonateController.php:22
* @route '/master/impersonate/{uuid}'
*/
store.post = (args: { uuid: string | number } | [uuid: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Master\ImpersonateController::store
* @see app/Http/Controllers/Master/ImpersonateController.php:22
* @route '/master/impersonate/{uuid}'
*/
const storeForm = (args: { uuid: string | number } | [uuid: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Master\ImpersonateController::store
* @see app/Http/Controllers/Master/ImpersonateController.php:22
* @route '/master/impersonate/{uuid}'
*/
storeForm.post = (args: { uuid: string | number } | [uuid: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

const ImpersonateController = { store }

export default ImpersonateController