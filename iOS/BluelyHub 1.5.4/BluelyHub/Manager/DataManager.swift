//
//  DataManager.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 2/1/21.
//

import Foundation

class DataManager {
    
    enum LoginType: Int {
        case None = 0
        case Mail = 1
        case Apple = 2
        case Google = 3
        case Facebook = 4
    }
    
    public static var currentUser: User?
    public static var licenses:Licenses?
    public static var credential:Credential?

    public static func isValidEmail(_ email: String) -> Bool {
        let emailRegEx = "[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,64}"

        let emailPred = NSPredicate(format:"SELF MATCHES %@", emailRegEx)
        return emailPred.evaluate(with: email)
    }
    
    public static func isValidPhone(_ phone: String) -> Bool {
        let range = NSRange(location: 0, length: phone.count)
        let regex = try! NSRegularExpression(pattern: "(\\([0-9]{3}\\) |[0-9]{3}-)[0-9]{3}-[0-9]{4}")
        if regex.firstMatch(in: phone, options: [], range: range) != nil{
            return true
        }
        
        return false
    }
    
    public static func isOlderThan(_ birthday: String, age: Int) ->Bool {
        let now = Date()

        let dateformatter = DateFormatter()
        dateformatter.dateFormat = "dd-MM-yyyy"
        if let from = dateformatter.date(from: birthday) {
            let calendar = Calendar.current
            
            let ageComponent = calendar.dateComponents([.year], from: from, to: now)
            if (ageComponent.year! < age) {
                return false
            }
            
            return true
        }
        
        return false
    }
}
