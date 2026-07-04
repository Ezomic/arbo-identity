import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:39
* @route '/sso/authorize'
*/
export const authorize = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: authorize.url(options),
    method: 'get',
})

authorize.definition = {
    methods: ["get","head"],
    url: '/sso/authorize',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:39
* @route '/sso/authorize'
*/
authorize.url = (options?: RouteQueryOptions) => {
    return authorize.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:39
* @route '/sso/authorize'
*/
authorize.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: authorize.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:39
* @route '/sso/authorize'
*/
authorize.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: authorize.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:39
* @route '/sso/authorize'
*/
const authorizeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: authorize.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:39
* @route '/sso/authorize'
*/
authorizeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: authorize.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoAuthorizeController::__invoke
* @see app/Http/Controllers/Sso/SsoAuthorizeController.php:39
* @route '/sso/authorize'
*/
authorizeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: authorize.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

authorize.form = authorizeForm

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
export const logout = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: logout.url(options),
    method: 'get',
})

logout.definition = {
    methods: ["get","head"],
    url: '/sso/logout',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
logout.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: logout.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
logout.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: logout.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
const logoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: logout.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
logoutForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: logout.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Sso\SsoLogoutController::__invoke
* @see app/Http/Controllers/Sso/SsoLogoutController.php:22
* @route '/sso/logout'
*/
logoutForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: logout.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

logout.form = logoutForm

/**
* @see routes/web.php:25
* @route '/.well-known/identity-public-key'
*/
export const publicKey = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: publicKey.url(options),
    method: 'get',
})

publicKey.definition = {
    methods: ["get","head"],
    url: '/.well-known/identity-public-key',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:25
* @route '/.well-known/identity-public-key'
*/
publicKey.url = (options?: RouteQueryOptions) => {
    return publicKey.definition.url + queryParams(options)
}

/**
* @see routes/web.php:25
* @route '/.well-known/identity-public-key'
*/
publicKey.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: publicKey.url(options),
    method: 'get',
})

/**
* @see routes/web.php:25
* @route '/.well-known/identity-public-key'
*/
publicKey.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: publicKey.url(options),
    method: 'head',
})

/**
* @see routes/web.php:25
* @route '/.well-known/identity-public-key'
*/
const publicKeyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: publicKey.url(options),
    method: 'get',
})

/**
* @see routes/web.php:25
* @route '/.well-known/identity-public-key'
*/
publicKeyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: publicKey.url(options),
    method: 'get',
})

/**
* @see routes/web.php:25
* @route '/.well-known/identity-public-key'
*/
publicKeyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: publicKey.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

publicKey.form = publicKeyForm

const sso = {
    authorize: Object.assign(authorize, authorize),
    logout: Object.assign(logout, logout),
    publicKey: Object.assign(publicKey, publicKey),
}

export default sso