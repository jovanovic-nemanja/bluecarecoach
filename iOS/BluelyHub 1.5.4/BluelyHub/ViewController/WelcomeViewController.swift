//
//  WelcomeViewController.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 1/30/21.
//

import UIKit
import SwiftyJSON
import GoogleSignIn
import FBSDKLoginKit
import AuthenticationServices

class WelcomeViewController: UIViewController {
    
    @IBOutlet weak var tfEmail: UITextField!
    @IBOutlet weak var tfPassword: UITextField!
    
    @IBOutlet weak var btnLoginFacebook: UIButton!
    @IBOutlet weak var btnLoginGoogle: UIButton!
    @IBOutlet weak var viewLoginApple: UIView!
    
    @objc func actionHandleAppleSignin() {
        let appleIDProvider = ASAuthorizationAppleIDProvider()
        let request = appleIDProvider.createRequest()
        request.requestedScopes = [.fullName, .email]
        
        let authorizationController = ASAuthorizationController(authorizationRequests: [request])
        authorizationController.delegate = self
        authorizationController.presentationContextProvider = self
        authorizationController.performRequests()
    }
    
    func setupAppleLoginButton() {
        if #available(iOS 13.0, *) {
            let appleLoginBtn = ASAuthorizationAppleIDButton(type: .signIn, style: .whiteOutline)
            appleLoginBtn.addTarget(self, action: #selector(actionHandleAppleSignin), for: .touchUpInside)
            self.viewLoginApple.addSubview(appleLoginBtn)
            
            // Setup Layout Constraints to be in the center of the screen
            appleLoginBtn.translatesAutoresizingMaskIntoConstraints = false
            NSLayoutConstraint.activate([
                appleLoginBtn.centerXAnchor.constraint(equalTo: self.viewLoginApple.centerXAnchor),
                appleLoginBtn.centerYAnchor.constraint(equalTo: self.viewLoginApple.centerYAnchor),
                appleLoginBtn.widthAnchor.constraint(equalToConstant: self.viewLoginApple.frame.width),
                appleLoginBtn.heightAnchor.constraint(equalToConstant: self.viewLoginApple.frame.height)
            ])
        }
    }
    
    func restoreMailSignin() {
        DispatchQueue.main.async {
            var params: JSON = JSON()
            params["email"].string = UserDefaults.standard.string(forKey: "email")
            params["password"].string = UserDefaults.standard.string(forKey: "password")

            UIManager.shared.showHUD(view: self.view)
            
            APIManager.shared.login(params) { (success, user, message) in
                UIManager.shared.hideHUD()
                
                if (success) {
                    DataManager.currentUser = user
                    self.performSegue(withIdentifier: "home", sender: nil)
                } else {
                    UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                    return
                }
            }
        }
    }
    
    func appleSignin(_ params: JSON) {
        DispatchQueue.main.async {
            UIManager.shared.showHUD(view: self.view)
            
            APIManager.shared.loginWithApple(params) { (success, user, newUser, message) in
                UIManager.shared.hideHUD()
                
                if (success) {
                    DataManager.currentUser = user
                    UserDefaults.standard.setValue(DataManager.LoginType.Apple.rawValue, forKey: "login_type")
                    UserDefaults.standard.setValue(user?.apple_id, forKey: "apple_id")

                    if (newUser) {
                        self.performSegue(withIdentifier: "personal", sender: params)
                    } else {
                        self.performSegue(withIdentifier: "home", sender: nil)
                    }
                } else {
                    UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                    return
                }
            }
        }
    }
    
    func restoreAppleSignIn(_ appleID: String?) {
        guard let appleID = appleID else {
            return
        }
        
        let appleIDProvider = ASAuthorizationAppleIDProvider()
        appleIDProvider.getCredentialState(forUserID: appleID) { (credentialState, error) in
            switch credentialState {
            case .authorized:
                var params: JSON = JSON()
                params["apple_id"].string = appleID
                self.appleSignin(params)
                return
            case .revoked:
                UIManager.shared.showAlert(vc: self, title: "Error", message: "The Apple Credential has been revoked.")
                return
            case .notFound:
                UIManager.shared.showAlert(vc: self, title: "Error", message: "Could not find the Apple Credential.")
                return
            default:
                UIManager.shared.showAlert(vc: self, title: "Error", message: "Unknown Error.")
            }
        }
    }

    func restoreFacebookSignIn(_ fbID: String?) {
        guard let fbID = fbID else {
            return
        }
        
        var params: JSON = JSON()
        params["fb_id"].string = fbID

        UIManager.shared.showHUD(view: self.view)
        
        APIManager.shared.loginWithFacebook(params) { (success, user, newUser, message) in
            UIManager.shared.hideHUD()
            
            if (success) {
                DataManager.currentUser = user
                UserDefaults.standard.setValue(DataManager.LoginType.Facebook.rawValue, forKey: "login_type")
                UserDefaults.standard.setValue(user?.fb_id, forKey: "facebook_id")
                
                if (newUser) {
                    self.performSegue(withIdentifier: "personal", sender: params)
                } else {
                    self.performSegue(withIdentifier: "home", sender: nil)
                }
            } else {
                UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                return
            }
        }
    }

    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        GIDSignIn.sharedInstance()?.presentingViewController = self;
//
        // setup Login With Facebook Button
        btnLoginFacebook.moveImageLeftTextCenter()
        
        // setup Login With Google Button
        btnLoginGoogle.moveImageLeftTextCenter()

        // setup Login With Apple Button
        setupAppleLoginButton()
        
        NotificationCenter.default.addObserver(self, selector: #selector(googleSignIn), name: NSNotification.Name("GoolgeSignInNotification"), object: nil)
        
        // Get License List
        UIManager.shared.showHUD(view: self.view, title: "Loading...")
        
        APIManager.shared.getLicense({ (success, licenses, message) in
            
            UIManager.shared.hideHUD()
            
            if (success) {
                DataManager.licenses = licenses
                
                // Automatically sign in the user.
                let loginType = UserDefaults.standard.integer(forKey: "login_type")
                if (loginType == DataManager.LoginType.Mail.rawValue) {
                    self.restoreMailSignin()
                } else if (loginType == DataManager.LoginType.Apple.rawValue) {
                    let appleID = UserDefaults.standard.string(forKey: "apple_id")
                    self.restoreAppleSignIn(appleID)
                } else if (loginType == DataManager.LoginType.Google.rawValue) {
                    GIDSignIn.sharedInstance()?.restorePreviousSignIn()
                } else if (loginType == DataManager.LoginType.Facebook.rawValue) {
                    let fbID = UserDefaults.standard.string(forKey: "facebook_id")
                    self.restoreFacebookSignIn(fbID)
                }
            } else {
                UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
            }
        })
    }

    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        
        navigationController?.setNavigationBarHidden(true, animated: animated)
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        navigationController?.setNavigationBarHidden(false, animated: animated)
    }

    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
        if (segue.identifier == "reset") {
            if let vcDest = segue.destination as? ForgotViewController {
                vcDest.userMail = sender as? String
            }
        } else if (segue.identifier == "personal") {
            if let vcDest = segue.destination as? PersonalViewController {
                vcDest.signupParams = sender as? JSON
            }
        }
    }

    @IBAction func onLogin(_ sender: Any) {
        guard let email = tfEmail.text, !email.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "Error", message: "Please input e-mail address.")
            return
        }
        
        if (!DataManager.isValidEmail(email)) {
            UIManager.shared.showAlert(vc: self, title: "Error", message: "Invalid e-mail address.")
            return
        }
        
        guard let password = tfPassword.text, !password.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "Error", message: "Please input your password.")
            return
        }
        
        var params: JSON = JSON()
        params["email"].string = email
        params["password"].string = password

        UIManager.shared.showHUD(view: self.view)
        
        APIManager.shared.login(params) { (success, user, message) in
            UIManager.shared.hideHUD()
            
            if (success) {
                UserDefaults.standard.setValue(DataManager.LoginType.Mail.rawValue, forKey: "login_type")
                UserDefaults.standard.setValue(email, forKey: "email")
                UserDefaults.standard.setValue(password, forKey: "password")
                DataManager.currentUser = user
                self.performSegue(withIdentifier: "home", sender: nil)
            } else {
                UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                return
            }
        }
    }
    
    @IBAction func onForgot(_ sender: Any) {
        self.performSegue(withIdentifier: "reset", sender: tfEmail.text)
    }
    
    @IBAction func onFacebook(_ sender: Any) {
        // 1
        let loginManager = LoginManager()
        
        if let _ = AccessToken.current {
            // Access token available -- user already logged in
            // Perform log out
            
            // 2
            loginManager.logOut()
            
        } else {
            // Access token not available -- user already logged out
            // Perform log in
            
            // 3
            loginManager.logIn(permissions: ["email"], from: self) { [weak self] (result, error) in
                
                // 4
                // Check for error
                guard error == nil else {
                    // Error occurred
                    print(error!.localizedDescription)
                    return
                }
                
                // 5
                // Check for cancel
                guard let result = result, !result.isCancelled else {
                    print("User cancelled login")
                    return
                }
                
                // 7
                Profile.loadCurrentProfile { (profile, error) in
                    var params: JSON = JSON()
                    params["fb_id"].string = profile?.userID
                    params["email"].string = profile?.email
                    
                    DispatchQueue.main.async {
                        UIManager.shared.showHUD(view: self!.view)
                        
                        APIManager.shared.loginWithFacebook(params) { (success, user, newUser, message) in
                            UIManager.shared.hideHUD()
                            
                            if (success) {
                                UserDefaults.standard.setValue(DataManager.LoginType.Facebook.rawValue, forKey: "login_type")
                                UserDefaults.standard.setValue(user?.fb_id, forKey: "facebook_id")
                                DataManager.currentUser = user
                                
                                if (newUser) {
                                    self!.performSegue(withIdentifier: "personal", sender: params)
                                } else {
                                    self!.performSegue(withIdentifier: "home", sender: nil)
                                }
                            } else {
                                UIManager.shared.showAlert(vc: self!, title: "Error", message: message!)
                                return
                            }
                        }
                    }
                }
            }
        }
    }
    
    @IBAction func onGoogle(_ sender: Any) {
        GIDSignIn.sharedInstance().signIn()
    }
    
    @objc func googleSignIn(notification: NSNotification)  {
        if let user = notification.userInfo?["userInfo"] as? GIDGoogleUser {
            var params: JSON = JSON()
            params["firstname"].string = user.profile.givenName
            params["lastname"].string = user.profile.familyName
            params["google_id"].string = user.userID
            params["email"].string = user.profile.email
            
            UIManager.shared.showHUD(view: self.view)

            APIManager.shared.loginWithGoogle(params) { (success, user, newUser, message) in
                UIManager.shared.hideHUD()

                if (success) {
                    UserDefaults.standard.setValue(DataManager.LoginType.Google.rawValue, forKey: "login_type")
                    UserDefaults.standard.setValue(user?.fb_id, forKey: "google_id")
                    DataManager.currentUser = user

                    if (newUser) {
                        self.performSegue(withIdentifier: "personal", sender: params)
                    } else {
                        self.performSegue(withIdentifier: "home", sender: nil)
                    }
                } else {
                    UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                    return
                }
            }
        }
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self)
    }
}

extension UIButton {
    func moveImageLeftTextCenter(imagePadding: CGFloat = 30.0){
        self.imageView?.contentMode = .scaleAspectFit
        guard let imageViewWidth = self.imageView?.frame.width else{return}
        guard let titleLabelWidth = self.titleLabel?.intrinsicContentSize.width else{return}
        self.contentHorizontalAlignment = .left
        imageEdgeInsets = UIEdgeInsets(top: 0.0, left: imagePadding - imageViewWidth / 2, bottom: 0.0, right: 0.0)
        titleEdgeInsets = UIEdgeInsets(top: 0.0, left: (bounds.width - titleLabelWidth) / 2 - imageViewWidth, bottom: 0.0, right: 0.0)
    }
}

extension WelcomeViewController: ASAuthorizationControllerDelegate {
    func authorizationController(controller: ASAuthorizationController, didCompleteWithAuthorization authorization: ASAuthorization) {
        switch authorization.credential {
        case let appleIDCredential as ASAuthorizationAppleIDCredential:
            
            // Create an account in your system.
            var params: JSON = JSON()
            params["apple_id"].string = appleIDCredential.user
            
            if let firstName = appleIDCredential.fullName?.givenName {
                params["firstname"].string = firstName
            }
            
            if let lastName = appleIDCredential.fullName?.familyName {
                params["lastname"].string = lastName
            }
            
            if let email = appleIDCredential.email {
                params["email"].string = email
            }
            
            appleSignin(params)
        default:
            break
        }
    }

    func authorizationController(controller: ASAuthorizationController, didCompleteWithError error: Error) {
//        UIManager.shared.showAlert(vc: self, title: "Error", message: error.localizedDescription)
    }
}

extension WelcomeViewController: ASAuthorizationControllerPresentationContextProviding {
    func presentationAnchor(for controller: ASAuthorizationController) -> ASPresentationAnchor {
        return self.view.window!
    }
}
