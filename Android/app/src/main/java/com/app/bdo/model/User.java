package com.app.bdo.model;

/**
 * Created by MobiDev on 26/03/21.
 */
public class User {

    private int id;
    private String firstname;
    private String lastname;
    private String email;
    private String gender;
    private String over_18;
    private String care_giving_license;
    private String zip_code;
    private String email_verified_at;
    private String fb_id;
    private String google_id;
    private String apple_id;
    private String phone_number;
    private String profile_logo;

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
}
