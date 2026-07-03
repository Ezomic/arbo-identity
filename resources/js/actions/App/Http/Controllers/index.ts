import Auth from './Auth'
import Sso from './Sso'
import Tenants from './Tenants'
import Settings from './Settings'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    Sso: Object.assign(Sso, Sso),
    Tenants: Object.assign(Tenants, Tenants),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers