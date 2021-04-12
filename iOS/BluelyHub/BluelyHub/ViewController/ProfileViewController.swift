//
//  ProfileViewController.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 2/1/21.
//

import UIKit
import Photos
import SDWebImage
import GoogleSignIn
import FBSDKLoginKit
import SwiftyJSON
import PickerViewCell
import SwiftPhoneNumberFormatter

class ProfileViewController: UIViewController, UITextFieldDelegate, UIPickerViewDelegate, UIPickerViewDataSource {

    private var isSocial: Bool = false
    private var isPhotoChanged: Bool = false
    
    private var isLooking: Bool = false
    private var lookingZipCode: Int = 0
    private var experiencedYears: Int = 0
    private var skills:[String] = [String]()
    private var preferredShift: Int = 0
    private var payMin: Int = 0, payMax: Int = 0

    @IBOutlet weak var segmentControl: UISegmentedControl!
    @IBOutlet weak var viewPersonal: UIScrollView!
    @IBOutlet weak var labelTagline: UILabel!
    @IBOutlet weak var ivProfilePhoto: UIImageView!
    @IBOutlet weak var tfFirstName: UITextField!
    @IBOutlet weak var tfLastName: UITextField!
    @IBOutlet weak var switchOver18: UISwitch!
    @IBOutlet weak var tfPhoneNumber: PhoneFormattedTextField!
    @IBOutlet weak var tfZIPCode: UITextField!
    @IBOutlet weak var tfLicense: UITextField!
    @IBOutlet weak var btnChangePassword: UIButton!

    @IBOutlet weak var viewLookingFor: UIView!
    @IBOutlet weak var tableView: UITableView!
    
    @objc func licenseTapDone() {
        if let picker = self.tfLicense.inputView as? UIPickerView {
            let selected = picker.selectedRow(inComponent: 0)
            if let licenses = DataManager.licenses {
                self.tfLicense.text = licenses[selected].name
            }
        }
        
        self.tfLicense.resignFirstResponder()
    }
    
    func initData() {
        // Personal Tab
        if let tagLine = DataManager.currentUser?.profiletagline {
            labelTagline.text = tagLine
        }
        
        if let photoUrl = DataManager.currentUser?.profile_logo {
            ivProfilePhoto.sd_setImage(with: URL(string: APIManager.imagePath + photoUrl), completed: nil)
        }
        
        if let firstName = DataManager.currentUser?.firstname {
            tfFirstName.text = firstName
        }

        if let lastName = DataManager.currentUser?.lastname {
            tfLastName.text = lastName
        }

        if let over_i8 = DataManager.currentUser?.over_18 {
            switchOver18.isOn = (Int(over_i8) == 1)
        }

        if let phoneNumber = DataManager.currentUser?.phone_number {
            tfPhoneNumber.text = phoneNumber
        }
        
        if let zipCode = DataManager.currentUser?.zip_code {
            tfZIPCode.text = zipCode
        }
        
        if let license = DataManager.currentUser?.care_giving_license {
            if let licenses = DataManager.licenses {
                for item in licenses {
                    if item.id == Int(license) {
                        tfLicense.text = item.name
                    }
                }
            }
        }
        
        // LookingFor Tab
        skills.removeAll()
        if let lookingJob = DataManager.currentUser?.looking_job {
            isLooking = (Int(lookingJob) == 1)
            
            if isLooking {
                if let zipCode = DataManager.currentUser?.looking_job_zipcode {
                    lookingZipCode = Int(zipCode)!
                }
                
                if let experience = DataManager.currentUser?.care_giving_experience {
                    experiencedYears = Int(experience)!
                }
                
                if let skill1 = DataManager.currentUser?.skill1 {
                    skills.append(skill1)
                }
                
                if let skill2 = DataManager.currentUser?.skill2 {
                    skills.append(skill2)
                }
                
                if let skill3 = DataManager.currentUser?.skill3 {
                    skills.append(skill3)
                }
                
                if let skill4 = DataManager.currentUser?.skill4 {
                    skills.append(skill4)
                }
                
                if let skill5 = DataManager.currentUser?.skill5 {
                    skills.append(skill5)
                }
                
                if let shift = DataManager.currentUser?.preferred_shift {
                    preferredShift = Int(shift)!
                }
                
                if let min = DataManager.currentUser?.desired_pay_from {
                    payMin = Int(min)!
                }
                
                if let max = DataManager.currentUser?.desired_pay_to {
                    payMax = Int(max)!
                }
            }
            
            tableView.reloadData()
        }
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        viewLookingFor.isHidden = true
        
        ivProfilePhoto.layer.masksToBounds = true
        ivProfilePhoto.layer.cornerRadius = ivProfilePhoto.frame.size.width / 2
        
        let tapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(imageTapped(tapGestureRecognizer:)))
        ivProfilePhoto.addGestureRecognizer(tapGestureRecognizer)

        tfLicense.setLicensePicker(target: self, selector: #selector(licenseTapDone))
        if let picker = tfLicense.inputView as? UIPickerView {
            picker.delegate = self
            picker.dataSource = self
        }
        
        tfPhoneNumber.config.defaultConfiguration = PhoneFormat(defaultPhoneFormat: "(###) ###-##-##")
        tfPhoneNumber.prefix = "+1 "
        
        initData()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        
        ivProfilePhoto.layer.masksToBounds = true
        ivProfilePhoto.layer.cornerRadius = ivProfilePhoto.frame.size.width / 2
    }

    @IBAction func onSegmentChanged(_ sender: Any) {
        if segmentControl.selectedSegmentIndex == 0 {
            viewPersonal.isHidden = false
            viewLookingFor.isHidden = true
        } else if segmentControl.selectedSegmentIndex == 1 {
            viewPersonal.isHidden = true
            viewLookingFor.isHidden = false
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
            let alertController = UIAlertController(title: "ThatDubaiGirl", message: strMessage, preferredStyle: .alert)

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
            let alertController = UIAlertController(title: "ThatDubaiGirl", message: strMessage, preferredStyle: .alert)

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
    
    @IBAction func onSave(_ sender: Any) {
        guard let firstName = tfFirstName.text, !firstName.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "", message: "Please input first name.")
            return
        }
        
        guard let lastName = tfLastName.text, !lastName.isEmpty else {
            UIManager.shared.showAlert(vc: self, title: "", message: "Please input last name.")
            return
        }
        
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
        
        var params: JSON = JSON()
        params["userid"].int = DataManager.currentUser?.id
        params["firstname"].string = firstName
        params["lastname"].string = lastName
        
        if switchOver18.isOn {
            params["over_18"].string = "1"
        } else {
            params["over_18"].string = "0"
        }
        
        params["phone_number"].string = phoneNumber
        params["zip_code"].string = zipCode
        
        if let picker = self.tfLicense.inputView as? UIPickerView {
            let selected = picker.selectedRow(inComponent: 0)
            params["care_giving_license"].int = DataManager.licenses![selected].id
        }
        
        params["looking_job"].int = isLooking ? 1 : 0
        if lookingZipCode > 0 {
            params["looking_job_zipcode"].int = lookingZipCode
        }
        
        if experiencedYears > 0 {
            params["care_giving_experience"].int = experiencedYears
        }
        
        if preferredShift > 0 {
            params["preferred_shift"].int = preferredShift
        }
        
        if payMin > 0 && payMax > 0 && payMax >= payMin {
            params["desired_pay_from"].int = payMin
            params["desired_pay_to"].int = payMax
        }
        
        if !skills.isEmpty {
            for index in 0...skills.count - 1 {
                params["skill\(index + 1)"].string = skills[index]
            }
        }

        UIManager.shared.showHUD(view: self.view)

        APIManager.shared.updateProfile(params, isPhotoChanged ? ivProfilePhoto.image : nil) { (success, user, msg) in
            
            UIManager.shared.hideHUD()
            
            if success {
                DataManager.currentUser = user
                self.initData()
            } else {
                UIManager.shared.showAlert(vc: self, title: "", message: msg!)
            }
        }
    }
    
    func logOut() {
        let loginType = UserDefaults.standard.integer(forKey: "login_type")
        // If this user has logged in with Google
        if (loginType == DataManager.LoginType.Google.rawValue) {
            // Google Log Out
            GIDSignIn.sharedInstance().signOut()
        } else if (loginType == DataManager.LoginType.Facebook.rawValue) {
            let loginManager = LoginManager()
            if let _ = AccessToken.current {
                loginManager.logOut()
            }
        }
        
        UserDefaults.standard.setValue(DataManager.LoginType.None.rawValue, forKey: "login_type")
        DataManager.currentUser = nil
        
        if let parent = self.navigationController {
            if let root = parent.navigationController {
                root.popToRootViewController(animated: true)
            }
        }
    }

    @IBAction func onLogout(_ sender: Any) {
        logOut()
    }

    @IBAction func onChangePassword(_ sender: Any) {
    }
    
    @IBAction func onDeleteAccount(_ sender: Any) {
        let strMessage: String = "Your account will be removed. Are you sure?"
        let alertController = UIAlertController(title: nil, message: strMessage, preferredStyle: .alert)
        
        let confirmAction = UIAlertAction(title: "Yes", style: .destructive) { action in
            UIManager.shared.showHUD(view: self.view)
            
            var params: JSON = JSON()
            params["userid"].int = DataManager.currentUser?.id
            
            APIManager.shared.deleteAccount(params) { (success, msg) in
                
                UIManager.shared.hideHUD()
                
                if success {
                    self.logOut()
                } else {
                    UIManager.shared.showAlert(vc: self, title: "", message: msg!)
                }
            }
        }
        alertController.addAction(confirmAction)

        let cancelAction = UIAlertAction(title: "No", style: .cancel) { action in
        }
        alertController.addAction(cancelAction)
        
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
        if let licenses = DataManager.licenses {
            return licenses.count
        }
        
        return 0
    }
    
    func pickerView(_ pickerView: UIPickerView, titleForRow row: Int, forComponent component: Int) -> String? {
        if let licenses = DataManager.licenses {
            return licenses[row].name
        }
        
        return ""
    }
}

extension ProfileViewController: UINavigationControllerDelegate, UIImagePickerControllerDelegate {
    func imagePickerController(_ picker: UIImagePickerController, didFinishPickingMediaWithInfo info: [UIImagePickerController.InfoKey : Any]) {
            // Local variable inserted by Swift 4.2 migrator.
        let info = convertFromUIImagePickerControllerInfoKeyDictionary(info)

        if let possibleImage = info["UIImagePickerControllerEditedImage"] as? UIImage {
            self.ivProfilePhoto.image = possibleImage.resize(targetSize: CGSize(width: 128, height: 128))
            isPhotoChanged = true
        } else if let possibleImage = info["UIImagePickerControllerOriginalImage"] as? UIImage {
            self.ivProfilePhoto.image = possibleImage.resize(targetSize: CGSize(width: 128, height: 128))
            isPhotoChanged = true
        } else {
            isPhotoChanged = false
            return
        }

        picker.dismiss(animated: true)
    }

    func imagePickerControllerDidCancel(_ picker: UIImagePickerController) {
        isPhotoChanged = false
        picker.dismiss(animated: true, completion: nil)
    }

    // Helper function inserted by Swift 4.2 migrator.
    fileprivate func convertFromUIImagePickerControllerInfoKeyDictionary(_ input: [UIImagePickerController.InfoKey: Any]) -> [String: Any] {
        return Dictionary(uniqueKeysWithValues: input.map {key, value in (key.rawValue, value)})
    }
}

extension ProfileViewController: PickerTableCellDelegate {
    func pickerView(_ pickerView: UIPickerView, titleForRow row: Int, forComponent component: Int, forCell cell: PickerTableViewCell) -> String? {
        if row == 0 {
            return "Day"
        } else if row == 1 {
            return "Night"
        } else if row == 2 {
            return "Live-in"
        }
        
        return ""
    }
    
    func pickerView(_ pickerView: UIPickerView, didSelectRow row: Int, inComponent component: Int, forCell cell: PickerTableViewCell) {
        self.preferredShift = row + 1
        pickerView.resignFirstResponder()
        self.tableView.reloadData()
    }
    
    func onPickerOpen(_ cell: PickerTableViewCell) {
    }
    
    func onPickerClose(_ cell: PickerTableViewCell) {
    }
}

extension ProfileViewController: PickerTableCellDataSource {
    func numberOfComponents(in pickerView: UIPickerView, forCell cell: PickerTableViewCell) -> Int {
        return 1
    }
    
    func pickerView(_ pickerView: UIPickerView, numberOfRowsInComponent component: Int, forCell cell: PickerTableViewCell) -> Int {
        return 3
    }
    
    
}

extension ProfileViewController: UITableViewDelegate, UITableViewDataSource {
    
    func numberOfSections(in tableView: UITableView) -> Int {
        return 2
    }
    
    func tableView(_ tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
        if section == 0 {
            return "CAREGIVING JOBS"
        } else if section == 1 {
            return "CAREGIVING TRAININGS"
        }
        
        return ""
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if section == 0 {
            if isLooking {
                if skills.count < 5 {
                    return 6 + skills.count
                } else {
                    return 5 + skills.count
                }
            } else {
                return 1
            }
        } else if section == 1 {
            return 1
        }
        
        return 0
    }
    
    @objc func switchChanged(_ sender : UISwitch){
        isLooking = sender.isOn
        tableView.reloadData()
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {

        var cell: UITableViewCell?
        
        if indexPath.section == 0 && indexPath.row == 3 {
            cell = tableView.dequeueReusableCell(withIdentifier: "PickerCell") as? PickerTableViewCell
            if cell == nil {
                cell = PickerTableViewCell(style: .value1, reuseIdentifier: "PickerCell")
            }
            
            let pickerCell: PickerTableViewCell = cell as! PickerTableViewCell
            pickerCell.delegate = self
            pickerCell.dataSource = self
        } else {
            cell = tableView.dequeueReusableCell(withIdentifier: "Cell")
            if cell == nil {
                cell = UITableViewCell(style: .value1, reuseIdentifier: "Cell")
            }
        }
        
        if indexPath.section == 0 {
            if indexPath.row == 0 {
                cell?.textLabel?.textColor = .label
                cell?.textLabel?.text = "Looking for Job"
                let switchView = UISwitch(frame: .zero)
                switchView.setOn(isLooking, animated: true)
                switchView.addTarget(self, action: #selector(self.switchChanged(_:)), for: .valueChanged)
                cell?.accessoryView = switchView
                cell?.accessoryType = .none
            } else if indexPath.row == 1 {
                cell?.textLabel?.textColor = .label
                cell?.textLabel?.text = "Job search in 5miles of ZIP:"
                if lookingZipCode > 0 {
                    cell?.detailTextLabel?.text = String(lookingZipCode)
                } else {
                    cell?.detailTextLabel?.text = "Not set"
                }
                cell?.accessoryView = nil
                cell?.accessoryType = .disclosureIndicator
            } else if indexPath.row == 2 {
                cell?.textLabel?.textColor = .label
                cell?.textLabel?.text = "Years of Experience"
                if experiencedYears > 0 {
                    cell?.detailTextLabel?.text = String(experiencedYears)
                } else {
                    cell?.detailTextLabel?.text = "Not set"
                }
                cell?.accessoryView = nil
                cell?.accessoryType = .disclosureIndicator
            } else if indexPath.row == 3 {
                cell?.textLabel?.textColor = .label
                cell?.textLabel?.text = "Preferred Shift"
                if preferredShift == 0 {
                    cell?.detailTextLabel?.text = "Not set"
                } else if preferredShift == 1 {
                    cell?.detailTextLabel?.text = "Day"
                } else if preferredShift == 2 {
                    cell?.detailTextLabel?.text = "Night"
                } else if preferredShift == 3 {
                    cell?.detailTextLabel?.text = "Live-in"
                }
                cell?.accessoryView = nil
                cell?.accessoryType = .disclosureIndicator
            } else if indexPath.row == 4 {
                cell?.textLabel?.textColor = .label
                cell?.textLabel?.text = "Desired Pay"
                if payMin > 0 && payMax > 0 {
                    cell?.detailTextLabel?.text = "\(payMin) - \(payMax)"
                } else {
                    cell?.detailTextLabel?.text = "Not set"
                }
                cell?.accessoryView = nil
                cell?.accessoryType = .disclosureIndicator
            } else if indexPath.row > 4 && indexPath.row < 5 + skills.count {
                cell?.textLabel?.textColor = .label
                cell?.textLabel?.text = "Skills\(indexPath.row - 4)"
                cell?.detailTextLabel?.text = skills[indexPath.row - 5]
                cell?.accessoryView = nil
                cell?.accessoryType = .disclosureIndicator
            } else if indexPath.row >= 5 + skills.count {
                cell?.textLabel?.textColor = .link
                cell?.textLabel?.text = "Add Caregiving Skill"
                cell?.detailTextLabel?.text = ""
                cell?.accessoryView = nil
                cell?.accessoryType = .disclosureIndicator
            }
        } else if indexPath.section == 1 {
            if indexPath.row == 0 {
                cell?.textLabel?.textColor = .link
                cell?.textLabel?.text = "My Trainings"//For care givers in WA state"
                cell?.accessoryView = nil
                cell?.accessoryType = .disclosureIndicator
            }
        }
        
        return cell!
    }
    
    func addSkill(_ index: Int) {
        let controller = UIAlertController(title: "Enter your Care giving skill", message: nil, preferredStyle: .alert)
        controller.addTextField()
        
        if index >= 0 {
            if skills[index].count > 0 {
                controller.textFields![0].text = skills[index]
            }
        }
        
        let submitAction = UIAlertAction(title: "Submit", style: .default) { [unowned controller] _ in
            if let skill = controller.textFields![0].text {
                if (skill.count > 0) {
                    if index >= 0 {
                        self.skills[index] = skill
                    } else {
                        self.skills.append(skill)
                    }
                    self.tableView.reloadData()
                }
            }
        }
        
        let cancelAction = UIAlertAction(title: "Cancel", style: .cancel) { (action) in
            
        }

        controller.addAction(submitAction)
        controller.addAction(cancelAction)
        
        present(controller, animated: true)
    }
    
    func modifyField(_ fieldIndex: Int) {
        var title = ""
        if fieldIndex == 1 {
            title = "Enter ZIP code"
        } else if fieldIndex == 2 {
            title = "Enter your Years of Experience"
        } else if fieldIndex == 4 {
            title = "Enter your Desired Pay"
        }
        
        let controller = UIAlertController(title: title, message: nil, preferredStyle: .alert)
        controller.addTextField()
        
        if fieldIndex == 4 {
            controller.addTextField()
        }

        if fieldIndex == 1 {
            controller.textFields![0].keyboardType = .numberPad
            if lookingZipCode > 0 {
                controller.textFields![0].text = String(lookingZipCode)
            }
        } else if fieldIndex == 2 {
            controller.textFields![0].keyboardType = .numberPad
            if experiencedYears > 0 {
                controller.textFields![0].text = String(experiencedYears)
            }
        } else if fieldIndex == 4 {
            controller.textFields![0].keyboardType = .decimalPad
            controller.textFields![0].placeholder = "Minimum"//"$(Min)"
            if payMin > 0 {
                controller.textFields![0].text = String(payMin)
            }
            controller.textFields![1].keyboardType = .decimalPad
            controller.textFields![1].placeholder = "Maximum"//"$(Max)"
            if payMax > 0 {
                controller.textFields![1].text = String(payMax)
            }
        }
        
        let submitAction = UIAlertAction(title: "Submit", style: .default) { [unowned controller] _ in
            if fieldIndex == 1 {
                self.lookingZipCode = Int(controller.textFields![0].text!)!
                self.tableView.reloadData()
            } else if fieldIndex == 2 {
                self.experiencedYears = Int(controller.textFields![0].text!)!
                self.tableView.reloadData()
            } else if fieldIndex == 4 {
                self.payMin = Int(controller.textFields![0].text!)!
                self.payMax = Int(controller.textFields![1].text!)!
                self.tableView.reloadData()
            }
        }
        
        let cancelAction = UIAlertAction(title: "Cancel", style: .cancel) { (action) in
            
        }

        controller.addAction(submitAction)
        controller.addAction(cancelAction)
        
        present(controller, animated: true)
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        tableView.deselectRow(at: indexPath, animated: true)
        
        if indexPath.section == 0 {
            if indexPath.row == 1 { // ZIP code
                modifyField(indexPath.row)
            } else if indexPath.row == 2 { // Years
                modifyField(indexPath.row)
            } else if indexPath.row == 3 { // Shift
                if let cell = tableView.cellForRow(at: indexPath) as? PickerTableViewCell {
                    cell.delegate = self
                    cell.dataSource = self

                    if !cell.isFirstResponder {
                        _ = cell.becomeFirstResponder()
                    }
                }
            } else if indexPath.row == 4 { // Payment
                modifyField(indexPath.row)
            } else if indexPath.row == 5 + skills.count {
                addSkill(-1)
            } else {
                addSkill(indexPath.row - 5)
            }
        } else if indexPath.section == 1 {
            if indexPath.row == 0 {
                if let url = URL(string: "https://bluecarecoach.com") {
                    UIApplication.shared.open(url)
                }
            }
        }
    }
}
