//
//  APIManager.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 2/1/21.
//

import Foundation
import Alamofire
import SwiftyJSON
import PDFKit

typealias Licenses = [License]
typealias Credentials = [Credential]

enum EndPoint: String {
    case emailVerify = "emailverify"
    case validateCode = "validateCode"
    case getLicense = "getLicenses"
    case getvideolink = "getvideolink"

    case register = "register"
    case login = "login"
    
    case loginApple = "loginwithApple"
    case loginGoogle = "loginwithGoogle"
    case loginFacebook = "loginUserwithFacebook"
    
    case reset = "forgotpassword"
    case changePassword = ""
    
    case getCredential = "getcredentials"
    case deleteCredentialFiles = "deleteCredentialuser"

    case addCredential = "addCredential"
    case removeCredential = "deleteExtracredential"
    case updateProfile = "updateAccount"
    case updateSkillsAndHobbies = "skills"
    case uploadCredentialFile = "uploadCredentialFile"
    
    case deleteAccount = "deleteAccount"
}

class APIManager {
    static let shared = APIManager()
    
    let urlMain = "https://bluecarecoach.com/api/v1/"
    public static let imagePath = "https://bluecarecoach.com/uploads/"

    func verify(_ params: JSON, _ callback: @escaping (Bool, String?) -> Void) {
        let urlString = urlMain + EndPoint.emailVerify.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    callback(true, json["msg"].string)
                } else if (json["status"].string == "failed") {
                    callback(false, json["msg"].string)
                } else {
                    callback(false, "Unknown response")
                }
            case .failure(let error):
                callback(false, error.errorDescription)
            }
        }
    }
    
    func validate(_ params: JSON, _ callback: @escaping (Bool, String?) -> Void) {
        let urlString = urlMain + EndPoint.validateCode.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    callback(true, json["msg"].string)
                } else if (json["status"].string == "failed") {
                    callback(false, json["msg"].string)
                } else {
                    callback(false, "Unknown response")
                }
            case .failure(let error):
                callback(false, error.errorDescription)
            }
        }
    }
    
    func getLicense(_ callback: @escaping (Bool, Licenses?, String?) -> Void) {
        let urlString = urlMain + EndPoint.getLicense.rawValue
        AF.request(urlString, method: .get).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    do {
                        print(json["data"])
                        let licenses = try JSONDecoder().decode(Licenses.self, from: json["data"].rawData())
                        callback(true, licenses, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, json["msg"].string)
                } else {
                    callback(false, nil, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, error.errorDescription)
            }
        }
    }
    
    func register(_ params: JSON, _ profilePhoto: UIImage?, _ callback: @escaping (Bool, User?, String?) -> Void) {
        let urlString = urlMain + EndPoint.register.rawValue
        
        if profilePhoto == nil {
            AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
                switch response.result {
                case .success(let value):
                    let json = JSON(value)
                    print(json)
                    if (json["status"].string == "success") {
                        let decoder = JSONDecoder()
                        do {
                            print(json["data"])
                            let user = try decoder.decode(User.self, from: json["data"].rawData())
                            callback(true, user, json["msg"].string)
                        } catch {
                            print("Error \(error)")
                            callback(false, nil, json["msg"].string)
                        }
                    } else if (json["status"].string == "failed") {
                        callback(false, nil, json["msg"].string)
                    } else {
                        callback(false, nil, "Unknown response")
                    }
                case .failure(let error):
                    callback(false, nil, error.errorDescription)
                }
            }
        } else {
            let imageData = profilePhoto!.jpegData(compressionQuality: 0.8)
            AF.upload(multipartFormData: { multipartFormData in
                for (key, value) in params {
                    multipartFormData.append(value.stringValue.data(using: .utf8)!, withName: key)
                }
                
                if let data = imageData {
                    multipartFormData.append(data, withName: "profile_logo", fileName: "\(Date.init().timeIntervalSince1970).jpeg", mimeType: "image/jpeg")
                }
            },
            to: URL(string: urlString)!, method: .post , headers: nil)
            .responseJSON(completionHandler: { (response) in
                
                switch response.result {
                case .success(let value):
                    let json = JSON(value)
                    print(json)
                    if (json["status"].string == "success") {
                        let decoder = JSONDecoder()
                        do {
                            print(json["data"])
                            let user = try decoder.decode(User.self, from: json["data"].rawData())
                            callback(true, user, json["msg"].string)
                        } catch {
                            print("Error \(error)")
                            callback(false, nil, json["msg"].string)
                        }
                    } else if (json["status"].string == "failed") {
                        callback(false, nil, json["msg"].string)
                    } else {
                        callback(false, nil, "Unknown response")
                    }
                case .failure(let error):
                    callback(false, nil, error.errorDescription)
                }
            })
        }
    }
    
    func login(_ params: JSON, _ callback: @escaping (Bool, User?, String?) -> Void) {
        let urlString = urlMain + EndPoint.login.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    let decoder = JSONDecoder()
                    do {
                        print(json["data"])
                        let user = try decoder.decode(User.self, from: json["data"].rawData())
                        callback(true, user, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, json["msg"].string)
                } else {
                    callback(false, nil, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, error.errorDescription)
            }
        }
    }
    
    func loginWithApple(_ params: JSON, _ callback: @escaping (Bool, User?, Bool, String?) -> Void) {
        let urlString = urlMain + EndPoint.loginApple.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    let decoder = JSONDecoder()
                    do {
                        print(json["data"])
                        let user = try decoder.decode(User.self, from: json["data"].rawData())
                        callback(true, user, json["isNewUser"].boolValue, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, false, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, false, json["msg"].string)
                } else {
                    callback(false, nil, false, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, false, error.errorDescription)
            }
        }
    }
    
    func loginWithGoogle(_ params: JSON, _ callback: @escaping (Bool, User?, Bool, String?) -> Void) {
        let urlString = urlMain + EndPoint.loginGoogle.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    let decoder = JSONDecoder()
                    do {
                        print(json["data"])
                        let user = try decoder.decode(User.self, from: json["data"].rawData())
                        callback(true, user, json["isNewUser"].boolValue, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, false, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, false, json["msg"].string)
                } else {
                    callback(false, nil, false, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, false, error.errorDescription)
            }
        }
    }
    
    func loginWithFacebook(_ params: JSON, _ callback: @escaping (Bool, User?, Bool, String?) -> Void) {
        let urlString = urlMain + EndPoint.loginFacebook.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    let decoder = JSONDecoder()
                    do {
                        print(json["data"])
                        let user = try decoder.decode(User.self, from: json["data"].rawData())
                        callback(true, user, json["isNewUser"].boolValue, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, false, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, false, json["msg"].string)
                } else {
                    callback(false, nil, false, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, false, error.errorDescription)
            }
        }
    }
    
    func resetPassword(_ params: JSON, _ callback: @escaping (Bool, String?) -> Void) {
        let urlString = urlMain + EndPoint.reset.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                if (json["status"].string == "success") {
                    callback(true, json["msg"].string)
                } else if (json["status"].string == "failed") {
                    callback(false, json["msg"].string)
                } else {
                    callback(false, "Unknown response")
                }
            case .failure(let error):
                callback(false, error.errorDescription)
            }
        }
    }
    
    func getCredential(_ params: JSON, _ callback: @escaping (Bool, Credentials?, String?) -> Void) {
        let urlString = urlMain + EndPoint.getCredential.rawValue
        AF.request(urlString, method: .get, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    do {
                        print(json["data"])
                        let credentials = try JSONDecoder().decode(Credentials.self, from: json["data"].rawData())
                        callback(true, credentials, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, json["msg"].string)
                } else {
                    callback(false, nil, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, error.errorDescription)
            }
        }
    }
    
    func getVideo(_ params: JSON, _ callback: @escaping (Bool, Video?, String?) -> Void) {
        let urlString = urlMain + EndPoint.getvideolink.rawValue
                        
        AF.request(urlString, method: .get, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    do {
                        print(json["data"])
                        let video = try JSONDecoder().decode(Video.self, from: json["data"].rawData())
                        callback(true, video, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, json["msg"].string)
                } else {
                    callback(false, nil, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, error.errorDescription)
            }
        }
    }
    
    func addCredential(_ params: JSON, _ callback: @escaping (Bool, Credentials?, String?) -> Void) {
        let urlString = urlMain + EndPoint.addCredential.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    do {
                        print(json["data"])
                        let credentials = try JSONDecoder().decode(Credentials.self, from: json["data"].rawData())
                        callback(true, credentials, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, json["msg"].string)
                } else {
                    callback(false, nil, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, error.errorDescription)
            }
        }
    }
    
    func deleteCredentialFiles(_ params: JSON, _ callback: @escaping (Bool, Credentials?, String?) -> Void) {
        let urlString = urlMain + EndPoint.deleteCredentialFiles.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    do {
                        print(json["data"])
                        let credentials = try JSONDecoder().decode(Credentials.self, from: json["data"].rawData())
                        callback(true, credentials, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, json["msg"].string)
                } else {
                    callback(false, nil, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, error.errorDescription)
            }
        }
    }
    
    func removeCredential(_ params: JSON, _ callback: @escaping (Bool, Credentials?, String?) -> Void) {
        let urlString = urlMain + EndPoint.removeCredential.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    do {
                        print(json["data"])
                        let credentials = try JSONDecoder().decode(Credentials.self, from: json["data"].rawData())
                        callback(true, credentials, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, json["msg"].string)
                } else {
                    callback(false, nil, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, error.errorDescription)
            }
        }
    }
    
    func updateProfile(_ params: JSON, _ profilePhoto: UIImage?, _ callback: @escaping (Bool, User?, String?) -> Void) {
        let urlString = urlMain + EndPoint.updateProfile.rawValue
        
        if profilePhoto == nil {
            AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
                switch response.result {
                case .success(let value):
                    let json = JSON(value)
                    print(json)
                    if (json["status"].string == "success") {
                        let decoder = JSONDecoder()
                        do {
                            print(json["data"])
                            let user = try decoder.decode(User.self, from: json["data"].rawData())
                            callback(true, user, json["msg"].string)
                        } catch {
                            print("Error \(error)")
                            callback(false, nil, json["msg"].string)
                        }
                    } else if (json["status"].string == "failed") {
                        callback(false, nil, json["msg"].string)
                    } else {
                        callback(false, nil, "Unknown response")
                    }
                case .failure(let error):
                    callback(false, nil, error.errorDescription)
                }
            }
        } else {
            let imageData = profilePhoto!.jpegData(compressionQuality: 0.8)
            AF.upload(multipartFormData: { multipartFormData in
                for (key, value) in params {
                    multipartFormData.append(value.stringValue.data(using: .utf8)!, withName: key)
                }
                
                if let data = imageData {
                    multipartFormData.append(data, withName: "profile_logo", fileName: "\(Date.init().timeIntervalSince1970).jpeg", mimeType: "image/jpeg")
                }
            },
            to: URL(string: urlString)!, method: .post , headers: nil)
            .responseJSON(completionHandler: { (response) in
                
                switch response.result {
                case .success(let value):
                    let json = JSON(value)
                    print(json)
                    if (json["status"].string == "success") {
                        let decoder = JSONDecoder()
                        do {
                            print(json["data"])
                            let user = try decoder.decode(User.self, from: json["data"].rawData())
                            callback(true, user, json["msg"].string)
                        } catch {
                            print("Error \(error)")
                            callback(false, nil, json["msg"].string)
                        }
                    } else if (json["status"].string == "failed") {
                        callback(false, nil, json["msg"].string)
                    } else {
                        callback(false, nil, "Unknown response")
                    }
                case .failure(let error):
                    callback(false, nil, error.errorDescription)
                }
            })
        }
    }
    
    func updateSkillsAndHobbies(_ params: JSON, _ callback: @escaping (Bool, User?, String?) -> Void) {
        let urlString = urlMain + EndPoint.updateSkillsAndHobbies.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    let decoder = JSONDecoder()
                    do {
                        print(json["data"])
                        let user = try decoder.decode(User.self, from: json["data"].rawData())
                        callback(true, user, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, json["msg"].string)
                } else {
                    callback(false, nil, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, error.errorDescription)
            }
        }
    }
    
    func uploadCredentialFile(_ params: JSON, _ image:UIImage, pdf: PDFDocument, ocrString: String, type: UploadedType,_ callback: @escaping (Bool, Credentials?, String?) -> Void) {
        let urlString = urlMain + EndPoint.uploadCredentialFile.rawValue
        let url = URL(string: urlString)
        let imageData = image.jpegData(compressionQuality: 0.8)
        let pdfData = pdf.dataRepresentation()
        let stringData = ocrString.data(using: .utf8)
        
        AF.upload(multipartFormData: { multipartFormData in
            for (key, value) in params {
                multipartFormData.append(value.stringValue.data(using: .utf8)!, withName: key)
            }
            
            if type == .image{
                if let data = imageData {
                    multipartFormData.append(data, withName: "credentialfile", fileName: "\(Date.init().timeIntervalSince1970).jpeg", mimeType: "image/jpeg")
                }
            }
            else if type == .pdf{
                if let data = pdfData {
                    multipartFormData.append(data, withName: "credentialfile", fileName: "\(Date.init().timeIntervalSince1970).pdf", mimeType: "application/pdf")
                }
            } else if type == .text{
                if let data = stringData {
                    multipartFormData.append(data, withName: "credentialfile", fileName: "\(Date.init().timeIntervalSince1970).txt", mimeType: "text/plain")
                }
            }
            
        },
        to: url!, method: .post , headers: nil)
        .responseJSON(completionHandler: { (response) in
            
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    do {
                        print(json["data"])
                        let credentials = try JSONDecoder().decode(Credentials.self, from: json["data"].rawData())
                        callback(true, credentials, json["msg"].string)
                    } catch {
                        print("Error \(error)")
                        callback(false, nil, json["msg"].string)
                    }
                } else if (json["status"].string == "failed") {
                    callback(false, nil, json["msg"].string)
                } else {
                    callback(false, nil, "Unknown response")
                }
            case .failure(let error):
                callback(false, nil, error.errorDescription)
            }
        })
    }
    
    func deleteAccount(_ params: JSON, _ callback: @escaping (Bool, String?) -> Void) {
        let urlString = urlMain + EndPoint.deleteAccount.rawValue
        AF.request(urlString, method: .post, parameters: params).validate().responseJSON { response in
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                print(json)
                if (json["status"].string == "success") {
                    callback(true, json["msg"].string)
                } else if (json["status"].string == "failed") {
                    callback(false, json["msg"].string)
                } else {
                    callback(false, "Unknown response")
                }
            case .failure(let error):
                callback(false, error.errorDescription)
            }
        }
    }
}


