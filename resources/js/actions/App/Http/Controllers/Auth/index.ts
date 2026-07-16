import DevLoginController from './DevLoginController'
import PasskeyEnrollmentController from './PasskeyEnrollmentController'
import TwoFactorSetupController from './TwoFactorSetupController'

const Auth = {
    DevLoginController: Object.assign(DevLoginController, DevLoginController),
    PasskeyEnrollmentController: Object.assign(PasskeyEnrollmentController, PasskeyEnrollmentController),
    TwoFactorSetupController: Object.assign(TwoFactorSetupController, TwoFactorSetupController),
}

export default Auth