import TenantController from './TenantController'
import TenantUserController from './TenantUserController'
import ImpersonateController from './ImpersonateController'

const Master = {
    TenantController: Object.assign(TenantController, TenantController),
    TenantUserController: Object.assign(TenantUserController, TenantUserController),
    ImpersonateController: Object.assign(ImpersonateController, ImpersonateController),
}

export default Master