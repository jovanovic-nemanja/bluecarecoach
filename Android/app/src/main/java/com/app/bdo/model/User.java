package com.app.bdo.model;

import android.text.TextUtils;

import com.app.bdo.helper.Constants;

/**
 * Created by MobiDev on 26/03/21.
 */
public class User {

    private int id;
    private String firstname = "";
    private String lastname = "";
    private String email = "";
    private String gender = "";
    private String over_18=  "";
    private String care_giving_license = "";
    private String zip_code = "";
    private String email_verified_at = "";
    private String fb_id = "";
    private String google_id = "";
    private String apple_id = "";
    private String phone_number = "";
    private String profile_logo = "";
    private String skill1 = "";
    private String skill2= "";
    private String skill3= "";
    private String skill4= "";
    private String skill5= "";
    private String looking_job = "";
    private String looking_job_zipcode = "";
    private String preferred_shift = "";
    private String desired_pay_from = "";
    private String desired_pay_to = "";
    private String care_giving_experience = "";
    private String profiletagline = "";


    public User() {

    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getFirstname() {
        return firstname;
    }

    public void setFirstname(String firstname) {
        this.firstname = firstname;
    }

    public String getLastname() {
        return lastname;
    }

    public void setLastname(String lastname) {
        this.lastname = lastname;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getGender() {
        return gender;
    }

    public void setGender(String gender) {
        this.gender = gender;
    }

    public String getOver_18() {
        return over_18;
    }

    public void setOver_18(String over_18) {
        this.over_18 = over_18;
    }

    public String getCare_giving_license() {
        return care_giving_license;
    }

    public void setCare_giving_license(String care_giving_license) {
        this.care_giving_license = care_giving_license;
    }

    public String getZip_code() {
        return zip_code;
    }

    public void setZip_code(String zip_code) {
        this.zip_code = zip_code;
    }

    public String getEmail_verified_at() {
        return email_verified_at;
    }

    public void setEmail_verified_at(String email_verified_at) {
        this.email_verified_at = email_verified_at;
    }

    public String getFb_id() {
        return fb_id;
    }

    public void setFb_id(String fb_id) {
        this.fb_id = fb_id;
    }

    public String getGoogle_id() {
        return google_id;
    }

    public void setGoogle_id(String google_id) {
        this.google_id = google_id;
    }

    public String getApple_id() {
        return apple_id;
    }

    public void setApple_id(String apple_id) {
        this.apple_id = apple_id;
    }

    public String getPhone_number() {
        return phone_number;
    }

    public void setPhone_number(String phone_number) {
        this.phone_number = phone_number;
    }

    public String getProfile_logo() {
        return profile_logo;
    }

    public void setProfile_logo(String profile_logo) {
        this.profile_logo = profile_logo;
    }

    public String getSkill1() {
        return skill1;
    }

    public void setSkill1(String skill1) {
        this.skill1 = skill1;
    }

    public String getSkill2() {
        return skill2;
    }

    public void setSkill2(String skill2) {
        this.skill2 = skill2;
    }

    public String getSkill3() {
        return skill3;
    }

    public void setSkill3(String skill3) {
        this.skill3 = skill3;
    }

    public String getSkill4() {
        return skill4;
    }

    public void setSkill4(String skill4) {
        this.skill4 = skill4;
    }

    public String getSkill5() {
        return skill5;
    }

    public void setSkill5(String skill5) {
        this.skill5 = skill5;
    }

    public String getLooking_job() {
        return looking_job;
    }

    public void setLooking_job(String looking_job) {
        this.looking_job = looking_job;
    }

    public String getLooking_job_zipcode() {
        if(TextUtils.isEmpty(looking_job_zipcode)){
            return Constants.NOT_SET;
        }
        return looking_job_zipcode;
    }

    public void setLooking_job_zipcode(String looking_job_zipcode) {
        this.looking_job_zipcode = looking_job_zipcode;
    }

    public String getPreferred_shift() {
        if(TextUtils.isEmpty(preferred_shift)){
            return Constants.NOT_SET;
        }
        return preferred_shift;
    }

    public void setPreferred_shift(String preferred_shift) {
        this.preferred_shift = preferred_shift;
    }

    public String getDesired_pay_from() {
        return desired_pay_from;
    }

    public void setDesired_pay_from(String desired_pay_from) {
        this.desired_pay_from = desired_pay_from;
    }

    public String getDesired_pay_to() {
        return desired_pay_to;
    }

    public void setDesired_pay_to(String desired_pay_to) {
        this.desired_pay_to = desired_pay_to;
    }

    public String getCare_giving_experience() {
        if(TextUtils.isEmpty(care_giving_experience)){
            return Constants.NOT_SET;
        }
        return care_giving_experience;
    }

    public void setCare_giving_experience(String care_giving_experience) {
        this.care_giving_experience = care_giving_experience;
    }

    public String jobPayVal(){
        if(TextUtils.isEmpty(this.desired_pay_from) && TextUtils.isEmpty(this.desired_pay_to)){
            return Constants.NOT_SET;
        }
        return this.desired_pay_from.concat("-").concat(desired_pay_to);
    }

    public String getProfiletagline() {
        return profiletagline;
    }

    public void setProfiletagline(String profiletagline) {
        this.profiletagline = profiletagline;
    }
}
