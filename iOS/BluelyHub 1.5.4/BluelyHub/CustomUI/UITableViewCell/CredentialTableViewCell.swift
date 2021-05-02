//
//  CredentialTableViewCell.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 2/2/21.
//

import UIKit

class CredentialTableViewCell: UITableViewCell {

    @IBOutlet weak var checkView: UIView!
    @IBOutlet weak var checkImage: UIImageView!
    //    @IBOutlet weak var statusView: UIView!
    @IBOutlet weak var imageCover: UIImageView!
    @IBOutlet weak var labelTitle: UILabel!

    //    @IBOutlet weak var labelCreator: UILabel!
//    @IBOutlet weak var labelExpireDate: UILabel!
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }

    public func setCredential(_ credential: Credential) {
        
        if credential.isSelected ??  false {
            checkImage.isHidden = false
        }
        else{
            checkImage.isHidden = true
        }
        
        if let title = credential.title {
            labelTitle.text = title
        }

        if credential.file_name == nil {
            imageCover.tintColor = UIColor.gray
        } else {
            if credential.expire_date != nil {
                if let expired = credential.expired, let isExpired = Int(expired) {
                    if isExpired < 0 {
                        imageCover.tintColor = UIColor.red
                    } else {
                        imageCover.tintColor = UIColor.green
                    }
                } else {
                    imageCover.tintColor = UIColor.red
                }
            } else {
                imageCover.tintColor = UIColor.green
            }
        }
    }
}
