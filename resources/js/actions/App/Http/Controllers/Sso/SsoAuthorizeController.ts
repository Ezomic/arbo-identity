import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/dashboard'
*/
const SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})

SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.definition = {
    methods: ["get","head"],
    url: '/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/dashboard'
*/
SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.url = (options?: RouteQueryOptions) => {
    return SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/dashboard'
*/
SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/dashboard'
*/
SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/dashboard'
*/
const SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/dashboard'
*/
SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/dashboard'
*/
SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9.form = SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9Form
/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/sso/authorize'
*/
const SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.url(options),
    method: 'get',
})

SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.definition = {
    methods: ["get","head"],
    url: '/sso/authorize',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/sso/authorize'
*/
SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.url = (options?: RouteQueryOptions) => {
    return SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/sso/authorize'
*/
SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/sso/authorize'
*/
SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/sso/authorize'
*/
const SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcbForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/sso/authorize'
*/
SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcbForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:38
* @route '/sso/authorize'
*/
SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcbForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb.form = SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcbForm

/**
* Multiple routes resolve to \App\Http\Controllers\Sso\SsoAuthorizeController::SsoAuthorizeController, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `SsoAuthorizeController['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
const SsoAuthorizeController = {
    '/dashboard': SsoAuthorizeController42a740574ecbfbac32f8cc353fc32db9,
    '/sso/authorize': SsoAuthorizeControllerfc887ad3522e5e813aeb278674723dcb,
}

export default SsoAuthorizeController