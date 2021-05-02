//
//  DataModel.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 2/1/21.
//

import Foundation

struct User: Hashable, Codable, Identifiable {
    // ID
    var id: Int
    
    // Name
    var firstname: String?
    var middlename: String?
    var lastname: String?
    
    // Personal
    var email: String?
    var over_18: String?
    var phone_number: String?
    var zip_code: String?
    var care_giving_license: String?
    var care_giving_experience: String?
    var profile_logo: String?

    // Social
    var fb_id: String?
    var google_id: String?
    var apple_id: String?

    // Skills
    var skill1: String?
    var skill2: String?
    var skill3: String?
    var skill4: String?
    var skill5: String?
    
    // Hobbies
    var hobby1: String?
    var hobby2: String?
    var hobby3: String?
    var hobby4: String?
    var hobby5: String?
    
    // Job related
    var looking_job: String?
    var looking_job_zipcode: String?
    var preferred_shift: String?
    var desired_pay_from: String?
    var desired_pay_to: String?
    
    // Tagline
    var profiletagline: String?
}

// Credential
struct License: Hashable, Codable, Identifiable {
    // ID
    var id: Int
    
    // Name
    var name: String?
}

// Credential
struct Credential: Hashable, Codable, Identifiable {
    // ID
    var id: String?
    
    // Name
    var title: String?
    var created_by: String?
    var file_name: String?
    var expire_date: String?
    var expired: String?
    var cre_uid: String?
    var isSelected : Bool?
}

// Video
struct Video: Hashable, Codable {
    // ID
    var link: String?
    
    // Name
    var all_uploaded_credentials_count: Int
    var expired_credentials_count: Int
    var extra_credentials_count: Int
    
    // Tagline
    var tagline: String?
}
