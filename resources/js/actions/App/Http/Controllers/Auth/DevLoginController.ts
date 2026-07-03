import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\DevLoginController::store
* @see app/Http/Controllers/Auth/DevLoginController.php:20
* @route '/dev-login'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/dev-login',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\DevLoginController::store
* @see app/Http/Controllers/Auth/DevLoginController.php:20
* @route '/dev-login'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\DevLoginController::store
* @see app/Http/Controllers/Auth/DevLoginController.php:20
* @route '/dev-login'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\DevLoginController::store
* @see app/Http/Controllers/Auth/DevLoginController.php:20
* @route '/dev-login'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\DevLoginController::store
* @see app/Http/Controllers/Auth/DevLoginController.php:20
* @route '/dev-login'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const DevLoginController = { store }

export default DevLoginController