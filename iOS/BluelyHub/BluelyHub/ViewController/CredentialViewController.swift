//
//  CredentialViewController.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 2/2/21.
//

import UIKit
import WebKit

class CredentialViewController: UIViewController, WKNavigationDelegate {

    public var credential: Credential?
    
    @IBOutlet weak var expireDate: UILabel!
    @IBOutlet weak var webView: WKWebView!

    override func viewDidLoad() {
        super.viewDidLoad()
  
        if let expire = credential?.expire_date {
            expireDate.text = "Expire Date: \(expire)"
        }
        
        if let fileName = credential?.file_name {
            let file_link = APIManager.imagePath + fileName
            webView.load(URLRequest(url: URL(string: file_link)!))
        }
    }
}
