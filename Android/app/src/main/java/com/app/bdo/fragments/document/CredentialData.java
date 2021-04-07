package com.app.bdo.fragments.document;

/**
 * Created by MobiDev on 28/03/21.
 */
public class CredentialData {

    private String id;
    private String title;
    private String cre_uid;
    private String file_name;
    private String expire_date;
    private String created_by;
    private String expired;
    private String path;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getCre_uid() {
        return cre_uid;
    }

    public void setCre_uid(String cre_uid) {
        this.cre_uid = cre_uid;
    }

    public String getFile_name() {
        return file_name;
    }

    public void setFile_name(String file_name) {
        this.file_name = file_name;
    }

    public String getExpire_date() {
        return expire_date;
    }

    public void setExpire_date(String expire_date) {
        this.expire_date = expire_date;
    }

    public String getCreated_by() {
        return created_by;
    }

    public void setCreated_by(String created_by) {
        this.created_by = created_by;
    }

    public String getExpired() {
        return expired;
    }

    public void setExpired(String expired) {
        this.expired = expired;
    }

    public String getPath() {
        return path;
    }

    public void setPath(String path) {
        this.path = path;
    }
}
