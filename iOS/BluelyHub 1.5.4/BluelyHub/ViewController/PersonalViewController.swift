//
//  PersonalViewController.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 1/30/21.
//

import UIKit
import Photos
import SwiftyJSON
import SwiftPhoneNumberFormatter

class PersonalViewController: UIViewController, UITextFieldDelegate, UIPickerViewDelegate, UIPickerViewDataSource {

    private var isSocial: Bool = false
    private var isPhotoSelected: Bool = false
    public var signupParams: JSON?

    @IBOutlet weak var ivProfilePhoto: UIImageView!
    @IBOutlet weak var tfFirstName: UITextField!
    @IBOutlet weak var tfLastName: UITextField!
    @IBOutlet weak var switchOver18: UISwitch!
    @IBOutlet weak var tfPhoneNumber: PhoneFormattedTextField!
    @IBOutlet weak var tfZIPCode: UITextField!
    @IBOutlet weak var tfLicense: UITextField!
    @IBOutlet weak var labelPassword: UILabel!
    @IBOutlet weak var tfPassword: UITextField!
    @IBOutlet weak var labelConfirm: UILabel!
    @IBOutlet weak var tfConfirm: UITextField!
    
    @objc func licenseTapDone() {
        if let picker = self.tfLicense.inputView as? UIPickerView {
            let selected = picker.selectedRow(inComponent: 0)
            if let licenses = DataManager.licenses {
                self.tfLicense.text = licenses[selected].name
            }
        }
        
        self.tfLicense.resignFirstResponder()
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        self.navigationItem.setHidesBackButton(true, animated: false)
        
        ivProfilePhoto.layer.masksToBounds = true
        ivProfilePhoto.layer.cornerRadius = ivProfilePhoto.frame.width / 2
        
        let tapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(imageTapped(tapGestureRecognizer:)))
        ivProfilePhoto.addGestureRecognizer(tapGestureRecognizer)
        
        tfLicense.setLicensePicker(target: self, selector: #selector(licenseTapDone))
        if let picker = tfLicense.inputView as? UIPickerView {
            picker.tag = 1
            picker.delegate = self
            picker.dataSource = self
        }

        tfPhoneNumber.config.defaultConfiguration = PhoneFormat(defaultPhoneFormat: "(###) ###-####")
        tfPhoneNumber.prefix = "+1 "
        
        if let params = signupParams {
            if let firstName = params["firstname"].string {
                tfFirstName.text = firstName
            }
            
            if let lastName = params["lastname"].string {
                tfLastName.text = lastName
            }
            
            // No need to show Password items for Social users
            if params["apple_id"].string != nil ||
                params["google_id"].string != nil ||
                params["fb_id"].string != nil {
                isSocial = true
            }
            
            labelPassword.isHidden = isSocial
            tfPassword.isHidden = isSocial
            labelConfirm.isHidden = isSocial
            tfConfirm.isHidden = isSocial
        }
    }
    
    func openImagePicker(sourceType: UIImagePickerController.SourceType) {
        switch sourceType {
        case UIImagePickerController.SourceType.camera:
            isCameraPermissionAuthorized(objViewController: self) { (isAuthorized) in
                if isAuthorized {
                    DispatchQueue.main.async {
                        if (UIImagePickerController.isSourceTypeAvailable(.camera)) {
                            let objImagePicker = UIImagePickerController()
                            UINavigationBar.appearance().tintColor = UIColor.white
                            objImagePicker.allowsEditing = true
                            objImagePicker.delegate = self
                            objImagePicker.sourceType =  sourceType//.photoLibrary
                            objImagePicker.mediaTypes = ["public.image"] //,String(kUTTypeVideo),String(kUTTypeMPEG4)
                            objImagePicker.videoQuality = .typeIFrame960x540//.typeIFrame1280x720 //iFrame960x540
                            self.navigationController?.present(objImagePicker, animated: true, completion: nil)
                        }
                    }
                }
            }
            break
            
        case UIImagePickerController.SourceType.photoLibrary:
            isPhotoPermissionAuthorized(objViewController: self) { (isAuthorized) in
                if isAuthorized {
                    DispatchQueue.main.async {
                        if (UIImagePickerController.isSourceTypeAvailable(.photoLibrary)) {
                            let objImagePicker = UIImagePickerController()
                            UINavigationBar.appearance().tintColor = UIColor.white
                            objImagePicker.allowsEditing = true
                            objImagePicker.delegate = self
                            objImagePicker.sourceType =  sourceType//.photoLibrary
                            objImagePicker.mediaTypes = ["public.image"] //,String(kUTTypeVideo),String(kUTTypeMPEG4)
                            objImagePicker.videoQuality = .typeIFrame960x540//.typeIFrame1280x720 //iFrame960x540
                            self.navigationController?.present(objImagePicker, animated: true, completion: nil)
                        }
                    }
                }
            }
            break

        default:
            break
        }
    }
    
    func isCameraPermissionAuthorized(objViewController: UIViewController, completion:@escaping ((Bool) -> Void)) {

        let status = AVCaptureDevice.authorizationStatus(for: .video)

        switch status {
        case .authorized:
            completion(true)
            break

        case .notDetermined:
            AVCaptureDevice.requestAccess(for: .video, completionHandler: { (granted) in
                if granted {
                    completion(true)
                } else {
                    completion(false)
                }
            })
            break

        case .denied, .restricted:
            let strMessage: String = "Please allow access to your photos."
            let alertController = UIAlertController(title: "Bluely Credentials", message: strMessage, preferredStyle: .alert)

            let cancelAction = UIAlertAction(title: "Ok", style: .default) { action in
                self.dismiss(animated: true, completion: nil)
            }
            alertController.addAction(cancelAction)
            
            self.present(alertController, animated: true)
            break

        default:
            completion(false)
            break
        }
    }

    func isPhotoPermissionAuthorized(objViewController: UIViewController, completion:@escaping ((Bool) -> Void)) {

        let status = PHPhotoLibrary.authorizationStatus()

        switch status {
        case .authorized:
            completion(true)
            break

        case .notDetermined:
            PHPhotoLibrary.requestAuthorization({ (newStatus) in
                if newStatus == PHAuthorizationStatus.authorized {
                    completion(true)
                } else {
                    completion(false)
                }
            })
            break

        case .denied, .restricted:
            let strMessage: String = "Please allow access to your photos."
            let alertController = UIAlertController(title: "Bluely Credentials", message: strMessage, preferredStyle: .alert)

            let cancelAction = UIAlertAction(title: "Ok", style: .default) { action in
                self.dismiss(animated: true, completion: nil)
            }
            alertController.addAction(cancelAction)
            
            self.present(alertController, animated: true)
            break

        default:
            completion(false)
            break
        }
    }

    @objc func imageTapped(tapGestureRecognizer: UITapGestureRecognizer) {
        let alertController = UIAlertController(title: "Choose Image", message: nil, preferredStyle: .actionSheet)
     
        alertController.addAction(UIAlertAction(title: "Camera", style: .default, handler: { _ in
            self.openImagePicker(sourceType: UIImagePickerController.SourceType.camera)
        }))
        
        alertController.addAction(UIAlertAction(title: "Photo Gallery", style: .default, handler: { _ in
            self.openImagePicker(sourceType: UIImagePickerController.SourceType.photoLibrary)
        }))
        
        alertController.addAction(UIAlertAction(title: "Cancel", style: .cancel, handler: nil))
        
        self.present(alertController, animated: true)
    }

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

    @IBAction func onSwitchChanged(_ sender: Any) {
    }
    
    @IBAction func onSave(_ sender: Any) {
        if !isPhotoSelected {
            UIManager.shared.showAlert(vc: self, title: "Bluely Credentials", message: "Please select profile picture.")
            return
        }

        guard let firstName = tfFirstName.text, !firstName.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "", message: "Please first name.")
            return
        }
        
        guard let lastName = tfLastName.text, !lastName.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "", message: "Please last name.")
            return
        }

        let isOver18 = switchOver18.isOn
        
        guard let phoneNumber = tfPhoneNumber.text, !phoneNumber.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "", message: "Please input phone number.")
            return
        }
        
        guard let zipCode = tfZIPCode.text, !zipCode.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "", message: "Please input ZIP code.")
            return
        }
        
        guard let license = tfLicense.text, !license.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "", message: "Please input your care giving license.")
            return
        }
        
        if (!isSocial) {
            guard let password = tfPassword.text, !password.isEmpty else {
                UIManager.shared.showAlert(vc: self, title: "", message: "Please input password.")
                return
            }
            
            guard let confirm = tfConfirm.text, !confirm.isEmpty else {
                UIManager.shared.showAlert(vc: self, title: "", message: "Please input password")
                return
            }
            
            if (password != confirm) {
                UIManager.shared.showAlert(vc: self, title: "", message: "The password doesn't match.")
                return
            }
            
            if password.count < 6 {
                UIManager.shared.showAlert(vc: self, title: "", message: "Password must be at least 6 characters.")
                return
            }
        }
        
        var params: JSON = JSON()
        if let signupParams = self.signupParams {
            params["email"].string = signupParams["email"].string
        }
        
        params["firstname"].string = firstName
        params["lastname"].string = lastName
        if isOver18 {
            params["over_18"] = "1"
        } else {
            params["over_18"] = "0"
        }
        
        params["phone_number"].string = phoneNumber
        params["zip_code"].string = zipCode
        
        if let picker = self.tfLicense.inputView as? UIPickerView {
            let selected = picker.selectedRow(inComponent: 0)
            params["care_giving_license"].int = DataManager.licenses![selected].id
        }

        if !isSocial {
            params["password"].string = tfPassword.text
        }

        UIManager.shared.showHUD(view: self.view)

        if (isSocial) {
            params["userid"].int = DataManager.currentUser?.id
            
            APIManager.shared.updateProfile(params, isPhotoSelected ? ivProfilePhoto.image : nil) { (success, user, msg) in
                
                UIManager.shared.hideHUD()
                
                if success {
                    DataManager.currentUser = user
                    
//                    self.performSegue(withIdentifier: "skill", sender: nil)
                    self.performSegue(withIdentifier: "home", sender: nil)
                } else {
                    UIManager.shared.showAlert(vc: self, title: "", message: msg!)
                }
            }
        } else {
            APIManager.shared.register(params, isPhotoSelected ? ivProfilePhoto.image : nil) { (success, user, msg) in
                
                UIManager.shared.hideHUD()
                
                if success {
                    UserDefaults.standard.setValue(DataManager.LoginType.Mail.rawValue, forKey: "login_type")
                    UserDefaults.standard.setValue(user?.email, forKey: "email")
                    UserDefaults.standard.setValue(self.tfPassword.text, forKey: "password")
                    DataManager.currentUser = user
                    
//                    self.performSegue(withIdentifier: "skill", sender: nil)
                    self.performSegue(withIdentifier: "home", sender: nil)
                } else {
                    UIManager.shared.showAlert(vc: self, title: "", message: msg!)
                }
            }
        }
    }
    
    func textField(_ textField: UITextField, shouldChangeCharactersIn range: NSRange, replacementString string: String) -> Bool {
        let currentCharacterCount = textField.text?.count ?? 0
        if range.length + range.location > currentCharacterCount {
            return false
        }
        
        let newLength = currentCharacterCount + string.count - range.length
        return newLength <= 5
    }
    
    // MARK: - UIPickerViewDataSource
    func numberOfComponents(in pickerView: UIPickerView) -> Int {
        return 1
    }
    
    func pickerView(_ pickerView: UIPickerView, numberOfRowsInComponent component: Int) -> Int {
        if (pickerView.tag == 1) {
            if let licenses = DataManager.licenses {
                return licenses.count
            }
            
            return 0
        } else if (pickerView.tag == 2) {
            return 50
        }
        
        return 0
    }
    
    func pickerView(_ pickerView: UIPickerView, titleForRow row: Int, forComponent component: Int) -> String? {
        if pickerView.tag == 1 {
            if let licenses = DataManager.licenses {
                return licenses[row].name
            }
        } else {
            if row == 0 {
                return "\(row + 1) Year"
            } else {
                return "\(row + 1) Years"
            }
        }
        
        return ""
    }
}

extension UITextField {
    func setDatePicker(target: Any, selector: Selector) {
        // Create a UIDatePicker object and assign to inputView
        let screenWidth = UIScreen.main.bounds.width
        let datePicker = UIDatePicker(frame: CGRect(x: 0, y: 0, width: screenWidth, height: 216))
        datePicker.datePickerMode = .date

        let dateformatter = DateFormatter()
        dateformatter.dateFormat = "dd-MM-yyyy"
        if let date = dateformatter.date(from: self.text!) {
            datePicker.date = date
        }

        if #available(iOS 14, *) {// Added condition for iOS 14
            datePicker.preferredDatePickerStyle = .wheels
            datePicker.sizeToFit()
        }
        self.inputView = datePicker
        
        // Create a toolbar and assign it to inputAccessoryView
        let toolBar = UIToolbar(frame: CGRect(x: 0.0, y: 0.0, width: screenWidth, height: 44.0))
        let flexible = UIBarButtonItem(barButtonSystemItem: .flexibleSpace, target: nil, action: nil)
        let cancel = UIBarButtonItem(title: "Cancel", style: .plain, target: nil, action: #selector(tapCancel))
        let barButton = UIBarButtonItem(title: "Done", style: .plain, target: target, action: selector)
        toolBar.setItems([cancel, flexible, barButton], animated: false)
        self.inputAccessoryView = toolBar
    }
    
    func setLicensePicker(target: Any, selector: Selector) {
        // Create a UIDatePicker object and assign to inputView
        let screenWidth = UIScreen.main.bounds.width
        let picker = UIPickerView(frame: CGRect(x: 0, y: 0, width: screenWidth, height: 216))
        if #available(iOS 14, *) {// Added condition for iOS 14
            picker.sizeToFit()
        }
        self.inputView = picker
        
        // Create a toolbar and assign it to inputAccessoryView
        let toolBar = UIToolbar(frame: CGRect(x: 0.0, y: 0.0, width: screenWidth, height: 44.0))
        let flexible = UIBarButtonItem(barButtonSystemItem: .flexibleSpace, target: nil, action: nil)
        let cancel = UIBarButtonItem(title: "Cancel", style: .plain, target: nil, action: #selector(tapCancel))
        let barButton = UIBarButtonItem(title: "Done", style: .plain, target: target, action: selector)
        toolBar.setItems([cancel, flexible, barButton], animated: false)
        self.inputAccessoryView = toolBar
    }
    
    func setExperiencePicker(target: Any, selector: Selector) {
        // Create a UIDatePicker object and assign to inputView
        let screenWidth = UIScreen.main.bounds.width
        let picker = UIPickerView(frame: CGRect(x: 0, y: 0, width: screenWidth, height: 216))
        if #available(iOS 14, *) {// Added condition for iOS 14
            picker.sizeToFit()
        }
        self.inputView = picker
        
        // Create a toolbar and assign it to inputAccessoryView
        let toolBar = UIToolbar(frame: CGRect(x: 0.0, y: 0.0, width: screenWidth, height: 44.0))
        let flexible = UIBarButtonItem(barButtonSystemItem: .flexibleSpace, target: nil, action: nil)
        let cancel = UIBarButtonItem(title: "Cancel", style: .plain, target: nil, action: #selector(tapCancel))
        let barButton = UIBarButtonItem(title: "Done", style: .plain, target: target, action: selector)
        toolBar.setItems([cancel, flexible, barButton], animated: false)
        self.inputAccessoryView = toolBar
    }
    
    @objc func tapCancel() {
        self.resignFirstResponder()
    }
}

extension PersonalViewController: UINavigationControllerDelegate, UIImagePickerControllerDelegate {
    func imagePickerController(_ picker: UIImagePickerController, didFinishPickingMediaWithInfo info: [UIImagePickerController.InfoKey : Any]) {
            // Local variable inserted by Swift 4.2 migrator.
        let info = convertFromUIImagePickerControllerInfoKeyDictionary(info)

        if let possibleImage = info["UIImagePickerControllerEditedImage"] as? UIImage {
            self.ivProfilePhoto.image = possibleImage.resize(targetSize: CGSize(width: 128, height: 128))
            isPhotoSelected = true
        } else if let possibleImage = info["UIImagePickerControllerOriginalImage"] as? UIImage {
            self.ivProfilePhoto.image = possibleImage.resize(targetSize: CGSize(width: 128, height: 128))
            isPhotoSelected = true
        } else {
            isPhotoSelected = false
            return
        }

        picker.dismiss(animated: true)
    }

    func imagePickerControllerDidCancel(_ picker: UIImagePickerController) {
        isPhotoSelected = false
        picker.dismiss(animated: true, completion: nil)
    }

    // Helper function inserted by Swift 4.2 migrator.
    fileprivate func convertFromUIImagePickerControllerInfoKeyDictionary(_ input: [UIImagePickerController.InfoKey: Any]) -> [String: Any] {
        return Dictionary(uniqueKeysWithValues: input.map {key, value in (key.rawValue, value)})
    }
}

extension UIImage {
    func resize(targetSize: CGSize) -> UIImage {
        return UIGraphicsImageRenderer(size:targetSize).image { _ in
            self.draw(in: CGRect(origin: .zero, size: targetSize))
        }
    }
}
