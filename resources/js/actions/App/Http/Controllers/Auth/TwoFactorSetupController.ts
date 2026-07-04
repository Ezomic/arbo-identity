import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::show
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:13
* @route '/2fa/setup'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/2fa/setup',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::show
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:13
* @route '/2fa/setup'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::show
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:13
* @route '/2fa/setup'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::show
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:13
* @route '/2fa/setup'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::show
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:13
* @route '/2fa/setup'
*/
const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::show
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:13
* @route '/2fa/setup'
*/
showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::show
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:13
* @route '/2fa/setup'
*/
showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const TwoFactorSetupController = { show }

export default TwoFactorSetupController