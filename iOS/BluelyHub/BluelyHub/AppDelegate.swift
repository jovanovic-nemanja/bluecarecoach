//
//  AppDelegate.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 1/30/21.
//

import UIKit
import GoogleSignIn
import FBSDKCoreKit
import IQKeyboardManagerSwift

@main
class AppDelegate: UIResponder, UIApplicationDelegate, GIDSignInDelegate {

    var window: UIWindow?

    func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?) -> Bool {
        // Override point for customization after application launch.
        
        // Enable IQKeyboardManager
        IQKeyboardManager.shared.enable = true

        // Initialize Google sign-in as Guide
        GIDSignIn.sharedInstance().clientID = "860126371655-3l16pg0r2dfv7u93398j768b214735ms.apps.googleusercontent.com"
        GIDSignIn.sharedInstance().delegate = self
        
        // Initialize Facebook Login
        ApplicationDelegate.shared.application(application, didFinishLaunchingWithOptions: launchOptions)

        UINavigationBar.appearance().barStyle = .blackOpaque
//        UINavigationBar.appearance().barTintColor = UIColor.init(red: 231.0/255.0, green: 105.0/255.0, blue: 41.0/255.0, alpha: 1.0)
        UINavigationBar.appearance().barTintColor = #colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1)
        UINavigationBar.appearance().tintColor = UIColor.white
        UINavigationBar.appearance().titleTextAttributes = [NSAttributedString.Key.foregroundColor: UIColor.white]
        
        UITabBar.appearance().unselectedItemTintColor = UIColor.white
        UITabBar.appearance().selectedImageTintColor = UIColor.white
        
        let attributes = [NSAttributedString.Key.foregroundColor : UIColor.white]
        UIBarButtonItem.appearance(whenContainedInInstancesOf: [UISearchBar.self]).setTitleTextAttributes(attributes, for: .normal)
        
        return true
    }

    // MARK: UISceneSession Lifecycle

    func application(_ application: UIApplication, configurationForConnecting connectingSceneSession: UISceneSession, options: UIScene.ConnectionOptions) -> UISceneConfiguration {
        // Called when a new scene session is being created.
        // Use this method to select a configuration to create the new scene with.
        return UISceneConfiguration(name: "Default Configuration", sessionRole: connectingSceneSession.role)
    }

    func application(_ application: UIApplication, didDiscardSceneSessions sceneSessions: Set<UISceneSession>) {
        // Called when the user discards a scene session.
        // If any sessions were discarded while the application was not running, this will be called shortly after application:didFinishLaunchingWithOptions.
        // Use this method to release any resources that were specific to the discarded scenes, as they will not return.
    }

    func application(_ app: UIApplication, open url: URL, options: [UIApplication.OpenURLOptionsKey : Any] = [:]) -> Bool {
        let urlString = url.absoluteString
        if urlString.contains("fb") {
            return ApplicationDelegate.shared.application(app, open: url, options: options)
        }
        
        return GIDSignIn.sharedInstance().handle(url)
    }
    
    // MARK: GIDSignInDelegate
    func sign(_ signIn: GIDSignIn!, didSignInFor user: GIDGoogleUser!, withError error: Error!) {
        if let error = error {
            if (error as NSError).code == GIDSignInErrorCode.hasNoAuthInKeychain.rawValue {
                print("The user has not signed in before or they have since signed out.")
            } else {
                print("\(error.localizedDescription)")
            }
            
            return
        }
        
        // Perform any operations on signed in user here.
        let userInfo:[String:GIDGoogleUser] = ["userInfo":user]
        NotificationCenter.default.post(name: Notification.Name("GoolgeSignInNotification"), object: nil, userInfo: userInfo)
    }
    
    func sign(_ signIn: GIDSignIn!, didDisconnectWith user: GIDGoogleUser!, withError error: Error!) {
        // Perform any operations when the user disconnects from app here.
        // ...
    }
}
