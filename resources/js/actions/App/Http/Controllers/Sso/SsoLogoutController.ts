import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
const SsoLogoutController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SsoLogoutController.url(options),
    method: 'get',
})

SsoLogoutController.definition = {
    methods: ["get","head"],
    url: '/sso/logout',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
SsoLogoutController.url = (options?: RouteQueryOptions) => {
    return SsoLogoutController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
SsoLogoutController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SsoLogoutController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
SsoLogoutController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SsoLogoutController.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
const SsoLogoutControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoLogoutController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
SsoLogoutControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoLogoutController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
SsoLogoutControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoLogoutController.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

SsoLogoutController.form = SsoLogoutControllerForm

export default SsoLogoutController