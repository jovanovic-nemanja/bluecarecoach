//
//  VerifyViewController.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 1/30/21.
//

import UIKit
import SwiftyJSON

class VerifyViewController: UIViewController, UITextFieldDelegate {

    @IBOutlet weak var labelEmail: UILabel!
    @IBOutlet weak var labelCode: UILabel!
    @IBOutlet weak var tfEmail: UITextField!
    @IBOutlet weak var tfCode: UITextField!
    @IBOutlet weak var btnSend: UIButton!
    @IBOutlet weak var btnConfirm: UIButton!
    @IBOutlet weak var btnResend: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        labelEmail.isHidden = false
        labelCode.isHidden = true
        tfEmail.isHidden = false
        tfCode.isHidden = true
        btnSend.isHidden = false
        btnConfirm.isHidden = true
        btnResend.isHidden = true
    }

    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
        if (segue.identifier == "signup") {
            if let vcDest = segue.destination as? PersonalViewController {
                vcDest.signupParams = sender as? JSON
            }
        }
    }
    
    func sendCode() {
        guard let email = tfEmail.text, !email.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "Error", message: "Please input email address")
            return
        }
        
        if (!DataManager.isValidEmail(email)) {
            UIManager.shared.showAlert(vc: self, title: "Error", message: "Invalid e-mail address.")
            return
        }
        
        var params: JSON = JSON()
        params["email"].string = email

        UIManager.shared.showHUD(view: self.view)
        
        APIManager.shared.verify(params) { (success, message) in
            UIManager.shared.hideHUD()
            
            if success {
                self.labelEmail.isHidden = true
                self.labelCode.isHidden = false
                self.tfEmail.isHidden = true
                self.tfCode.isHidden = false
                self.btnSend.isHidden = true
                self.btnResend.isHidden = false
                self.btnConfirm.isHidden = false
            } else {
                UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
            }
        }
    }
    
    @IBAction func onSend(_ sender: Any) {
        sendCode()
    }
    
    @IBAction func onResend(_ sender: Any) {
        sendCode()
    }
    
    @IBAction func onConfirm(_ sender: Any) {
        let email = self.tfEmail.text
        let codeString =  self.tfCode.text
        if codeString?.count == 0 {
            return
        }
        
        let code = Int(codeString!)
        
        var params: JSON = JSON()
        params["email"].string = email
        params["code"].int = code

        UIManager.shared.showHUD(view: self.view)
        APIManager.shared.validate(params) { (success, msg) in
            UIManager.shared.hideHUD()

            if success {
                params = JSON()
                params["email"].string = email
                self.performSegue(withIdentifier: "signup", sender: params)
            } else {
                UIManager.shared.showAlert(vc: self, title: "Error", message: msg!)
            }
        }
    }
    
    func textField(_ textField: UITextField, shouldChangeCharactersIn range: NSRange, replacementString string: String) -> Bool {
        let currentCharacterCount = textField.text?.count ?? 0
        if range.length + range.location > currentCharacterCount {
            return false
        }
        
        let newLength = currentCharacterCount + string.count - range.length
        return newLength <= 6
    }
}
