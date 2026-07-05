import UserApiController from './UserApiController'
import TenantApiController from './TenantApiController'

const Api = {
    UserApiController: Object.assign(UserApiController, UserApiController),
    TenantApiController: Object.assign(TenantApiController, TenantApiController),
}

export default Api