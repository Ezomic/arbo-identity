import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::setup
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:16
* @route '/2fa/setup'
*/
export const setup = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: setup.url(options),
    method: 'get',
})

setup.definition = {
    methods: ["get","head"],
    url: '/2fa/setup',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::setup
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:16
* @route '/2fa/setup'
*/
setup.url = (options?: RouteQueryOptions) => {
    return setup.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::setup
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:16
* @route '/2fa/setup'
*/
setup.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: setup.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::setup
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:16
* @route '/2fa/setup'
*/
setup.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: setup.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::setup
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:16
* @route '/2fa/setup'
*/
const setupForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: setup.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::setup
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:16
* @route '/2fa/setup'
*/
setupForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: setup.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\TwoFactorSetupController::setup
* @see app/Http/Controllers/Auth/TwoFactorSetupController.php:16
* @route '/2fa/setup'
*/
setupForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: setup.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

setup.form = setupForm

const method2fa = {
    setup: Object.assign(setup, setup),
}

export default method2fa