//
//  HomeViewController.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 2/20/21.
//

import UIKit
import Photos
import SwiftyJSON
import DatePickerDialog
import MessageUI

import Vision
import VisionKit
import PDFKit

class UserextraViewController: UIViewController, UITableViewDelegate, UITableViewDataSource {
    
    @IBOutlet weak var mailBtnView: UIView!
    @IBOutlet weak var tableView: UITableView!
    @IBOutlet weak var mailBtnViewHeight: NSLayoutConstraint!
    @IBOutlet weak var btwAll: UIButton!
    @IBOutlet weak var btnUpload: UIButton!
    @IBOutlet weak var btnNotSet: UIButton!
    
    var credentials: Credentials = Credentials()
    var sel_credentials: Credentials = Credentials()
    var sel_status = "all"
    
    private var curCredential: Credential?
    var sel_cre_uid = "1"
    var creatingMail = false
    
    
    // variables for scaning images
    var ocrText = String()
    var scannedImages = [UIImage]()
    var scanImageView = UIImage()
    var oneLong = UIImage()
    private var ocrRequest = VNRecognizeTextRequest(completionHandler: nil)
    var pdfFile : PDFDocument?
    // vairables for scanning images ends here
    
    func showData() {
        self.credentials.removeAll()
        
        if self.sel_status == "all" {
            if self.sel_credentials.count != 0 {
                self.credentials = self.sel_credentials
            }
        }else if self.sel_status == "upload" {
            if self.sel_credentials.count != 0 {
                for i in 0 ... self.sel_credentials.count - 1 {
                    if self.sel_credentials[i].file_name != nil {
                        self.credentials.append(self.sel_credentials[i])
                    }
                }
            }
        }else{
            if self.sel_credentials.count != 0 {
                for i in 0 ... self.sel_credentials.count - 1 {
                    if self.sel_credentials[i].created_by != "1" {
                        self.credentials.append(self.sel_credentials[i])
                    }
                }
            }
        }
        
        self.tableView.reloadData()
    }
    
    func initData() {
        self.credentials = []
        self.sel_credentials = []
        if let userID = DataManager.currentUser?.id {
            var params: JSON = JSON()
            params["userid"].int = userID
            
            UIManager.shared.showHUD(view: self.view)
            
            APIManager.shared.getCredential(params) { (success, credentials, message) in
                UIManager.shared.hideHUD()
                
                if (success) {
                    self.sel_credentials = credentials!
                    self.showData()
                } else {
                    UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                    return
                }
            }
        }
    }
    
    @objc func handleLongPress(sender: UILongPressGestureRecognizer) {
        if self.sel_status == "all" || self.sel_status == "upload" {
            return
        }
        
        guard let userID = DataManager.currentUser?.id else {
            return
        }
        
        if sender.state == .began {
            let touchPoint = sender.location(in: tableView)
            if let indexPath = tableView.indexPathForRow(at: touchPoint) {
                let credential = credentials[indexPath.row]
                
                let alertController = UIAlertController(title: "Choose Action", message: nil, preferredStyle: .actionSheet)
             
//                alertController.addAction(UIAlertAction(title: "Edit Credential", style: .default, handler: { _ in
//                }))
                
                alertController.addAction(UIAlertAction(title: "Remove Credential", style: .destructive, handler: { _ in
                    var params: JSON = JSON()
                    params["userid"].int = userID
                    params["credID"].string = credential.id
                    
                    UIManager.shared.showHUD(view: self.view)
                    
                    APIManager.shared.removeCredential(params) { (success, credentials, message) in
                        UIManager.shared.hideHUD()
                        
                        if (success) {
                            self.sel_credentials = credentials!
                            self.showData()
                        } else {
                            UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                            return
                        }
                    }
                }))
                
                alertController.addAction(UIAlertAction(title: "Cancel", style: .cancel, handler: nil))
                
                self.present(alertController, animated: true)
            }
        }
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        hideMailBtn()
        // Do any additional setup after loading the view.
        tableView.register(UINib(nibName: "CredentialTableViewCell", bundle: nil), forCellReuseIdentifier: "CredentialCell")
        tableView.tableFooterView = UIView()
        
        let longPress = UILongPressGestureRecognizer(target: self, action: #selector(handleLongPress))
        tableView.addGestureRecognizer(longPress)
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(true)
        initData()
        hideMailBtn()
        configureOCR()
    }
    
    @IBAction func onAdd(_ sender: Any) {
        let controller = UIAlertController(title: "Enter credential type", message: nil, preferredStyle: .alert)
        controller.addTextField()
        
        let submitAction = UIAlertAction(title: "Submit", style: .default) { [unowned controller] _ in
            if let newType = controller.textFields![0].text {
                if newType.count > 0 {
                    var params: JSON = JSON()
                    params["userid"].int = DataManager.currentUser?.id
                    params["title"].string = newType
                    
                    UIManager.shared.showHUD(view: self.view, title: "Saving...")
                    
                    APIManager.shared.addCredential(params, { (success, credentials, message) in
                        UIManager.shared.hideHUD()
                        
                        if (success) {
                            if let credentials = credentials {
                                self.sel_credentials = credentials
                                self.showData()
                            }
                        } else {
                            UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                            return
                        }
                    })
                }
            }
        }
        
        let cancelAction = UIAlertAction(title: "Cancel", style: .cancel) { (action) in
            
        }
        
        controller.addAction(submitAction)
        controller.addAction(cancelAction)
        
        present(controller, animated: true)
    }
    
    func onDelete() {
        let controller = UIAlertController(title: "Delete uploaded credential", message: nil, preferredStyle: .alert)
        
        let submitAction = UIAlertAction(title: "Yes", style: .default) { [unowned controller] _ in
            var params: JSON = JSON()
            params["userid"].int = DataManager.currentUser?.id
            params["cre_uid"].string = self.sel_cre_uid
            UIManager.shared.showHUD(view: self.view, title: "Processing...")
            
            APIManager.shared.deleteCredentialFiles(params, { (success, credentials, message) in
                UIManager.shared.hideHUD()
                
                if (success) {
                    if let credentials = credentials {
                        self.credentials = credentials
                        self.tableView.reloadData()
                    }
                } else {
                    UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                    return
                }
            })
        }
        
        let cancelAction = UIAlertAction(title: "No", style: .cancel) { (action) in
            
        }
        
        controller.addAction(submitAction)
        controller.addAction(cancelAction)
        
        present(controller, animated: true)
    }
    
    @IBAction func onApply(_ sender: Any) {
        if MFMailComposeViewController.canSendMail() {
            showMailBtnView()
        } else {
            UIManager.shared.showAlert(vc: self, title: "BluelyHub", message: "Please set up mail account in order to send email")
        }
    }
/*
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        
        self.tabBarController?.navigationItem.title = "Credentials"
        
        self.tabBarController?.navigationItem.leftBarButtonItem = UIBarButtonItem(barButtonSystemItem: .add, target: self, action: #selector(onAdd))
        
        self.tabBarController?.navigationItem.rightBarButtonItem = UIBarButtonItem(title: "Email", style: .plain, target: self, action: #selector(onApply))
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
    }
*/
    // MARK: - Navigation
    
    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
        if segue.identifier == "detail" {
            if let vcDest = segue.destination as? CredentialViewController {
                vcDest.credential = sender as? Credential
            }
        }
    }
    
    @IBAction func onAllBtn(_ sender: Any) {
        btwAll.backgroundColor = #colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1)
        btwAll.setTitleColor(UIColor.white, for: .normal)
        btnUpload.backgroundColor = UIColor.white
        btnUpload.setTitleColor(#colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1), for: .normal)
        btnNotSet.backgroundColor = UIColor.white
        btnNotSet.setTitleColor(#colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1), for: .normal)
        
        sel_status = "all"
        initData()
    }
    
    @IBAction func onUploadBtn(_ sender: Any) {
        btwAll.backgroundColor = UIColor.white
        btwAll.setTitleColor(#colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1), for: .normal)
        btnUpload.backgroundColor = #colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1)
        btnUpload.setTitleColor(UIColor.white, for: .normal)
        btnNotSet.backgroundColor = UIColor.white
        btnNotSet.setTitleColor(#colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1), for: .normal)
        sel_status = "upload"
        initData()
    }
    
    @IBAction func onNotSetBtn(_ sender: Any) {
        btwAll.backgroundColor = UIColor.white
        btwAll.setTitleColor(#colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1), for: .normal)
        btnUpload.backgroundColor = UIColor.white
        btnUpload.setTitleColor(#colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1), for: .normal)
        btnNotSet.backgroundColor = #colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1)
        btnNotSet.setTitleColor(UIColor.white, for: .normal)
        sel_status = "noset"
        initData()
    }
    
    // MARK: -UITableViewDelegate, UITableViewDataSource
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return credentials.count
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "CredentialCell", for: indexPath) as! CredentialTableViewCell
        
        if creatingMail && credentials[indexPath.row].file_name != nil{
            cell.checkView.isHidden = false
        }
        else{
            cell.checkView.isHidden = true
        }
        
        // Configure the cell...
        cell.setCredential(credentials[indexPath.row])
        
        return cell
    }
    
    func tableView(_ tableView: UITableView, editActionsForRowAt indexPath: IndexPath) -> [UITableViewRowAction]? {
        
        // action one
        let editAction = UITableViewRowAction(style: .default, title: "Edit", handler: { (action, indexPath) in
            let credential = self.credentials[indexPath.row]
            
            self.showAlertController(credential)
        })
        editAction.backgroundColor = #colorLiteral(red: 0, green: 0.5032967925, blue: 1, alpha: 1)
        
        // action two
        let credential = credentials[indexPath.row]
        if (credential.file_name == nil && credential.expire_date == nil) {
            return [editAction]
        }else{
            let deleteAction = UITableViewRowAction(style: .default, title: "Delete", handler: { (action, indexPath) in
                self.sel_cre_uid = credential.cre_uid!
                self.onDelete()
            })
            deleteAction.backgroundColor = UIColor.red
            
            return [editAction, deleteAction]
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
            let alertController = UIAlertController(title: "BluelyHub", message: strMessage, preferredStyle: .alert)
            
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
            let alertController = UIAlertController(title: "BluelyHub", message: strMessage, preferredStyle: .alert)
            
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
    
    func showAlertController(_ credential:Credential) {
        guard VNDocumentCameraViewController.isSupported else {
            return
        }
        
        self.curCredential = credential
        let scanVC = VNDocumentCameraViewController()
        scanVC.delegate = self
        self.present(scanVC, animated: true)
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        if creatingMail{
            credentials[indexPath.row].isSelected = !(credentials[indexPath.row].isSelected ?? false)
            tableView.reloadData()
        }
        else{
            let credential = credentials[indexPath.row]
            
            if (credential.file_name == nil && credential.expire_date == nil) {
                showAlertController(credential)
            } else {
                self.performSegue(withIdentifier: "detail", sender: credential)
            }
            
        }
    }
}

extension UserextraViewController: UINavigationControllerDelegate, UIImagePickerControllerDelegate {
    func uploadCredential(_ date:Date?, type: UploadedType) {
        var params: JSON = JSON()
        params["userid"].int = DataManager.currentUser?.id
        params["credentialid"].string = self.curCredential?.id
        
        if let date = date {
            let dateformatter = DateFormatter()
            dateformatter.dateFormat = "yyyy-MM-dd"
            params["expire_date"].string = dateformatter.string(from: date)
        }
        
        UIManager.shared.showHUD(view: self.view, title: "Uploading...")
        
        APIManager.shared.uploadCredentialFile(params, oneLong, pdf: pdfFile!, ocrString: ocrText, type: type,{ (success, credentials, msg) in
            UIManager.shared.hideHUD()
            
            if success {
                self.initData()
            } else {
                UIManager.shared.showAlert(vc: self, title: "", message: msg!)
            }
        })
    }
    
    func imagePickerController(_ picker: UIImagePickerController, didFinishPickingMediaWithInfo info: [UIImagePickerController.InfoKey : Any]) {
        // Local variable inserted by Swift 4.2 migrator.
        let info = convertFromUIImagePickerControllerInfoKeyDictionary(info)
        
        var image: UIImage? = nil
        if let possibleImage = info["UIImagePickerControllerEditedImage"] as? UIImage {
            image = possibleImage
        } else if let possibleImage = info["UIImagePickerControllerOriginalImage"] as? UIImage {
            image = possibleImage
        }
        
        picker.dismiss(animated: true) {
            if image != nil {
                let myDatePicker: UIDatePicker = UIDatePicker()
                myDatePicker.frame = CGRect(x: 0, y: 15, width: 270, height: 200)
                myDatePicker.datePickerMode = .date
                let alertController = UIAlertController(title: "Choose Expire Date\n\n\n\n\n\n\n\n", message: nil, preferredStyle: .alert)
                alertController.view.addSubview(myDatePicker)
                let selectAction = UIAlertAction(title: "Ok", style: .default, handler: { _ in
                    //self.uploadCredential(myDatePicker.date, image!, type: .image)
                })
                let skipAction = UIAlertAction(title: "Skip", style: .default) { _ in
                }
                let cancelAction = UIAlertAction(title: "Cancel", style: .cancel, handler: nil)
                alertController.addAction(selectAction)
                alertController.addAction(skipAction)
                alertController.addAction(cancelAction)
                self.present(alertController, animated: true)
            }
        }
    }
    
    func imagePickerControllerDidCancel(_ picker: UIImagePickerController) {
        picker.dismiss(animated: true, completion: nil)
    }
    
    // Helper function inserted by Swift 4.2 migrator.
    fileprivate func convertFromUIImagePickerControllerInfoKeyDictionary(_ input: [UIImagePickerController.InfoKey: Any]) -> [String: Any] {
        return Dictionary(uniqueKeysWithValues: input.map {key, value in (key.rawValue, value)})
    }
}

extension UserextraViewController: MFMailComposeViewControllerDelegate {
    func mailComposeController(_ controller: MFMailComposeViewController, didFinishWith result: MFMailComposeResult, error: Error?) {
        controller.dismiss(animated: true)
    }
}

// for mail view btns
extension UserextraViewController {
    func hideMailBtn(){
        
        for i in 0..<credentials.count{
            credentials[i].isSelected = false
        }
        
        creatingMail = false
        mailBtnView.isHidden = true
        mailBtnViewHeight.constant = 0.0
        UIView.animate(withDuration: 0.5) {
            self.view.layoutIfNeeded()
        }
        tableView.reloadData()
    }
    func showMailBtnView(){
        creatingMail = true
        mailBtnViewHeight.constant = 50.0
        UIView.animate(withDuration: 0.5) {
            self.view.layoutIfNeeded()
        }
        perform(#selector(afterShowing), with: nil, afterDelay: 0.4)
        tableView.reloadData()
    }
    
    
    @objc func afterShowing(){
        mailBtnView.isHidden = false
    }
    
    @objc func afterHiding(){
        mailBtnView.isHidden = true
    }
    
    @IBAction func cancelBtnPressed(_ sender: Any) {
        hideMailBtn()
    }
    @IBAction func mailDoneBtnPressed(_ sender: Any) {
        let mail = MFMailComposeViewController()
        mail.mailComposeDelegate = self
        mail.setSubject("BluelyHub Credentials!")

        let selectedCredentials = credentials.filter({$0.isSelected == true})
        selectedCredentials.forEach({
            if let file_name = $0.file_name{
                let ext = URL(fileURLWithPath: file_name).pathExtension
                
                var mime = ""
                if ext == "jpeg" {
                    mime = "image/jpeg"
                } else if ext == "pdf" {
                    mime = "application/pdf"
                } else if ext == "txt" {
                    mime = "text/plain"
                }
                
                mail.setMessageBody("Credentials \n \(file_name)", isHTML: false)
                do {
                    mail.addAttachmentData(try Data(contentsOf: URL(string: APIManager.imagePath + file_name)!), mimeType: mime, fileName: file_name)
                } catch let error {
                }
            }
        })
        
        //        mail.setToRecipients(["you@yoursite.com"])
        present(mail, animated: true)
        
    }
    
    
}




// Scanning
extension UserextraViewController: VNDocumentCameraViewControllerDelegate {
    func documentCameraViewController(_ controller: VNDocumentCameraViewController, didFinishWith scan: VNDocumentCameraScan) {
        controller.dismiss(animated: true)

        for i in 0..<scan.pageCount{
            scannedImages.append(scan.imageOfPage(at: i))
        }
        
        scanImageView = scannedImages[0]
        processImage(scan.imageOfPage(at: 0))
    }
    
    private func processImage(_ image: UIImage) {
        guard let cgImage = image.cgImage else { return }
        
        let requestHandler = VNImageRequestHandler(cgImage: cgImage, options: [:])
        do {
            try requestHandler.perform([self.ocrRequest])
        } catch {
            print(error)
        }
    }
    
    
    
    func documentCameraViewController(_ controller: VNDocumentCameraViewController, didFailWithError error: Error) {
        //Handle properly error
        controller.dismiss(animated: true)
    }
    
    func documentCameraViewControllerDidCancel(_ controller: VNDocumentCameraViewController) {
        controller.dismiss(animated: true)
    }
    
    private func configureOCR() {
        ocrRequest = VNRecognizeTextRequest { (request, error) in
            guard let observations = request.results as? [VNRecognizedTextObservation] else { return }
            
            var ocrText = ""
            for observation in observations {
                guard let topCandidate = observation.topCandidates(1).first else { return }
                
                ocrText += topCandidate.string + "\n"
            }
            
            
            DispatchQueue.main.async {
                self.ocrText = self.ocrText + ocrText
                self.pdfFile = self.scannedImages.makePDF()
                self.oneLong = self.scannedImages.stitchImages(isVertical: true)
                
                
                let myDatePicker: UIDatePicker = UIDatePicker()
                myDatePicker.frame = CGRect(x: 0, y: 15, width: 270, height: 200)
                myDatePicker.datePickerMode = .date
                let alertController = UIAlertController(title: "Choose Expire Date\n\n\n\n\n\n\n\n", message: nil, preferredStyle: .alert)
                alertController.view.addSubview(myDatePicker)
                let selectAction = UIAlertAction(title: "Ok", style: .default, handler: { _ in
                    self.showTypeSeletionSheet(expDate: myDatePicker.date)
                    //                    self.uploadCredential(myDatePicker.date, image!, type: .image)
                })
                let skipAction = UIAlertAction(title: "Skip", style: .default) { _ in
                    self.showTypeSeletionSheet(expDate: nil)
                }
                let cancelAction = UIAlertAction(title: "Cancel", style: .cancel, handler: nil)
                alertController.addAction(selectAction)
                alertController.addAction(skipAction)
                alertController.addAction(cancelAction)
                self.present(alertController, animated: true)
                
                
            }
        }
        
        ocrRequest.recognitionLevel = .accurate
        ocrRequest.recognitionLanguages = ["en-US", "en-GB"]
        ocrRequest.usesLanguageCorrection = true
    }
    
    func showTypeSeletionSheet(expDate: Date?){
        
        let alertController = UIAlertController(title: nil, message: "Please select Type", preferredStyle: .actionSheet)
        
        let pdfAction = UIAlertAction(title: "PDF", style: .default, handler: { (alert: UIAlertAction!) -> Void in
            self.uploadCredential(expDate,  type: .pdf)
        })
        
        let textAction = UIAlertAction(title: "Text", style: .default, handler: { (alert: UIAlertAction!) -> Void in
            self.uploadCredential(expDate, type: .text)
        })
        
        
        let imageAction = UIAlertAction(title: "Image", style: .default, handler: { (alert: UIAlertAction!) -> Void in
            self.uploadCredential(expDate, type: .image)
        })
        
        let cancelAction = UIAlertAction(title: "Cancel", style: .cancel, handler: { (alert: UIAlertAction!) -> Void in
            
        })
        
        alertController.addAction(imageAction)
        alertController.addAction(pdfAction)
        alertController.addAction(textAction)
        alertController.addAction(cancelAction)
        
        if let popoverController = alertController.popoverPresentationController {
            popoverController.sourceView = self.view
            popoverController.sourceRect = CGRect(x: self.view.bounds.midX, y: self.view.bounds.midY, width: 0, height: 0)
            popoverController.permittedArrowDirections = []
        }
        
        self.present(alertController, animated: true, completion: nil)
    }
    
    
}



