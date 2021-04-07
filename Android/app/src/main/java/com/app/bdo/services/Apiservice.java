package com.app.bdo.services;

import android.text.TextUtils;

import com.app.bdo.fragments.document.CredentialData;
import com.app.bdo.helper.AppHelper;
import com.app.bdo.model.User;
import com.app.bdo.utils.Logger;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.IOException;
import java.util.Date;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

/**
 * Created by MobiDev on 26/03/21.
 */
public class Apiservice {

    private static final Apiservice ourInstance = new Apiservice();

    public static Apiservice getInstance() {
        return ourInstance;
    }

    public static final MediaType JSON
            = MediaType.get("application/json; charset=utf-8");

    private static final MediaType MEDIA_TYPE_PNG = MediaType.parse("image/png");


    private Apiservice() {
    }

    public static RequestBody createGoogleloginRequest(String accountId) {

        JSONObject json = new JSONObject();
        try {
            json.put("google_id", accountId);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        return RequestBody.Companion.create(json.toString(), Apiservice.JSON);
    }

    public static RequestBody createFBloginRequest(String accountId) {

        JSONObject json = new JSONObject();
        try {
            json.put("fb_id", accountId);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        return RequestBody.Companion.create(json.toString(), Apiservice.JSON);
    }

    public static RequestBody getVerificationRequest(String email) {

        JSONObject json = new JSONObject();
        try {
            json.put("email", email);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        return RequestBody.Companion.create(json.toString(), Apiservice.JSON);
    }

    public static RequestBody getValidationRequest(String email, String code) {

        JSONObject json = new JSONObject();
        try {
            json.put("email", email);
            json.put("code", code);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        return RequestBody.Companion.create(json.toString(), Apiservice.JSON);
    }

    public static RequestBody createRegisterRequest(User user, String password) {

        File file = new File(user.getProfile_logo());

        RequestBody requestBody = new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("firstname", user.getFirstname())
                .addFormDataPart("lastname", user.getLastname())
                .addFormDataPart("email", user.getEmail())
                .addFormDataPart("care_giving_license", user.getCare_giving_license())
                .addFormDataPart("zip_code", user.getZip_code())
                .addFormDataPart("over_18", user.getOver_18())
                .addFormDataPart("phone_number", user.getPhone_number())
                .addFormDataPart("password", password)
                .addFormDataPart("profile_logo", "logo-square.png",
                        RequestBody.create(MEDIA_TYPE_PNG, file))
                .build();


        return requestBody;
    }

    public static RequestBody createUserUpdateRequest(User user, Boolean isImageUpdated) {

//        File file = new File(user.getProfile_logo());

        if (!TextUtils.isEmpty(user.getProfile_logo()) && isImageUpdated) {
            File file = new File(user.getProfile_logo());
            RequestBody requestBody = new MultipartBody.Builder()
                    .setType(MultipartBody.FORM)
                    .addFormDataPart("profile_logo", "logo-square.png",
                            RequestBody.create(MEDIA_TYPE_PNG, file))
                    .addFormDataPart("userid", String.valueOf(user.getId()))
                    .addFormDataPart("firstname", user.getFirstname())
                    .addFormDataPart("lastname", user.getLastname())
                    .addFormDataPart("care_giving_license", user.getCare_giving_license())
                    .addFormDataPart("zip_code", user.getZip_code())
                    .addFormDataPart("over_18", user.getOver_18())
                    .addFormDataPart("skill1", user.getSkill1())
                    .addFormDataPart("skill2", user.getSkill2())
                    .addFormDataPart("skill3", user.getSkill3())
                    .addFormDataPart("skill4", user.getSkill4())
                    .addFormDataPart("skill5", user.getSkill5())
                    .addFormDataPart("looking_job", user.getLooking_job())
                    .addFormDataPart("looking_job_zipcode", user.getLooking_job_zipcode())
                    .addFormDataPart("preferred_shift", user.getPreferred_shift())
                    .addFormDataPart("desired_pay_from", user.getDesired_pay_from())
                    .addFormDataPart("desired_pay_to", user.getDesired_pay_to())
                    .addFormDataPart("care_giving_experience", user.getCare_giving_experience())
                    .addFormDataPart("phone_number", user.getPhone_number())
                    .build();

            return requestBody;
        }

        RequestBody requestBody = new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("userid", String.valueOf(user.getId()))
                .addFormDataPart("firstname", user.getFirstname())
                .addFormDataPart("lastname", user.getLastname())
                .addFormDataPart("care_giving_license", user.getCare_giving_license())
                .addFormDataPart("zip_code", user.getZip_code())
                .addFormDataPart("over_18", user.getOver_18())
                .addFormDataPart("skill1", user.getSkill1())
                .addFormDataPart("skill2", user.getSkill2())
                .addFormDataPart("skill3", user.getSkill3())
                .addFormDataPart("skill4", user.getSkill4())
                .addFormDataPart("skill5", user.getSkill5())
                .addFormDataPart("looking_job", user.getLooking_job())
                .addFormDataPart("looking_job_zipcode", user.getLooking_job_zipcode())
                .addFormDataPart("preferred_shift", user.getPreferred_shift())
                .addFormDataPart("desired_pay_from", user.getDesired_pay_from())
                .addFormDataPart("desired_pay_to", user.getDesired_pay_to())
                .addFormDataPart("care_giving_experience", user.getCare_giving_experience())
                .addFormDataPart("phone_number", user.getPhone_number())
                .build();

//
//         .addFormDataPart("profile_logo", "logo-square.png",
//                RequestBody.create(MEDIA_TYPE_PNG, file))

        return requestBody;
    }

    public static RequestBody createCredentialFileRequest(CredentialData data, String date, String selctedUri) {

        File file = new File(selctedUri);
        Logger.debug("Apiservice ", "createCredentialFileRequest " + file.getPath());

        RequestBody requestBody = new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("credentialid", data.getId())
                .addFormDataPart("userid", String.valueOf(AppHelper.getInstance().getUser().getId()))
                .addFormDataPart("expire_date", TextUtils.isEmpty(date) ? new Date().toString() : date)
                .addFormDataPart("credentialfile", "credential.png",
                        RequestBody.create(MEDIA_TYPE_PNG, file))
                .build();


        return requestBody;
    }


    public static RequestBody createCredentialTextFileRequest(CredentialData data, String date, File file) {

        Logger.debug("Apiservice ", "createCredentialFileRequest " + file.getPath());

        RequestBody requestBody = new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("credentialid", data.getId())
                .addFormDataPart("userid", String.valueOf(AppHelper.getInstance().getUser().getId()))
                .addFormDataPart("expire_date", TextUtils.isEmpty(date) ? new Date().toString() : date)
                .addFormDataPart("credentialfile", "credential.png",
                        RequestBody.create(MEDIA_TYPE_PNG, file))
                .build();


        return requestBody;
    }


    public RequestBody createVideoLinkRequest() {

        JSONObject json = new JSONObject();
        try {

            json.put("userid", AppHelper.getInstance().getUser().getId());

        } catch (JSONException e) {
            e.printStackTrace();
        }

        Logger.debug("ApiService ", "createVideoLinkRequest " + json.toString());

        return RequestBody.Companion.create(json.toString(), Apiservice.JSON);
    }


    public RequestBody createCredentialDeleteReq(Boolean isExtra, String id) {

        JSONObject json = new JSONObject();
        try {

            json.put("userid", AppHelper.getInstance().getUser().getId());
            if (isExtra) {
                json.put("credID", id);

            } else {
                json.put("cre_uid", id);

            }

        } catch (JSONException e) {
            e.printStackTrace();
        }

        Logger.debug("ApiService ", "createCredentialDeleteReq " + json.toString());

        return RequestBody.Companion.create(json.toString(), Apiservice.JSON);
    }

    public RequestBody createCustomCredential(String name) {

        JSONObject json = new JSONObject();
        try {

            json.put("userid", AppHelper.getInstance().getUser().getId());
            json.put("title", name);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        return RequestBody.Companion.create(json.toString(), Apiservice.JSON);
    }


    public String makePost(String url, RequestBody requestBody) throws IOException {

        Logger.debug("ApiService ", "Url => " + url);
        Logger.debug("ApiService ", "body => " + requestBody.toString());

        OkHttpClient client = new OkHttpClient();

        Request request = new Request.Builder()
                .url(url)
                .post(requestBody)
                .build();
        try (Response response = client.newCall(request).execute()) {
            return response.body().string();
        }

    }

    public String makeGet(String url) throws IOException {
        Logger.debug("ApiService ", "makeGet Url => " + url);

        OkHttpClient client = new OkHttpClient();
        Request request = new Request.Builder().url(url).build();
        try (Response response = client.newCall(request).execute()) {
            return response.body().string();
        }

    }

}
