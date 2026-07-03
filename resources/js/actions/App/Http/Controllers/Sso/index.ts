import SsoAuthorizeController from './SsoAuthorizeController'
import SsoLogoutController from './SsoLogoutController'

const Sso = {
    SsoAuthorizeController: Object.assign(SsoAuthorizeController, SsoAuthorizeController),
    SsoLogoutController: Object.assign(SsoLogoutController, SsoLogoutController),
}

export default Sso