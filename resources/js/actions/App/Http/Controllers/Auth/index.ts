import DevLoginController from './DevLoginController'
import TwoFactorSetupController from './TwoFactorSetupController'

const Auth = {
    DevLoginController: Object.assign(DevLoginController, DevLoginController),
    TwoFactorSetupController: Object.assign(TwoFactorSetupController, TwoFactorSetupController),
}

export default Auth