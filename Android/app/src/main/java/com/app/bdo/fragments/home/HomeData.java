package com.app.bdo.fragments.home;

/**
 * Created by MobiDev on 28/03/21.
 */
public class HomeData {

    private String link;
    private int all_uploaded_credentials_count = 0;
    private int expired_credentials_count = 0;
    private int extra_credentials_count = 0;
    private String tagline;

    public String getLink() {
        return link;
    }

    public void setLink(String link) {
        this.link = link;
    }

    public int getAll_uploaded_credentials_count() {
        return all_uploaded_credentials_count;
    }

    public void setAll_uploaded_credentials_count(int all_uploaded_credentials_count) {
        this.all_uploaded_credentials_count = all_uploaded_credentials_count;
    }

    public int getExpired_credentials_count() {
        return expired_credentials_count;
    }

    public void setExpired_credentials_count(int expired_credentials_count) {
        this.expired_credentials_count = expired_credentials_count;
    }

    public int getExtra_credentials_count() {
        return extra_credentials_count;
    }

    public void setExtra_credentials_count(int extra_credentials_count) {
        this.extra_credentials_count = extra_credentials_count;
    }

    public String getTagline() {
        return tagline;
    }

    public void setTagline(String tagline) {
        this.tagline = tagline;
    }
}
