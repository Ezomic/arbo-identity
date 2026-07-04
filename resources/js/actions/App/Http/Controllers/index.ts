import Api from './Api'
import Auth from './Auth'
import Sso from './Sso'
import Tenants from './Tenants'
import Master from './Master'
import Settings from './Settings'

const Controllers = {
    Api: Object.assign(Api, Api),
    Auth: Object.assign(Auth, Auth),
    Sso: Object.assign(Sso, Sso),
    Tenants: Object.assign(Tenants, Tenants),
    Master: Object.assign(Master, Master),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers