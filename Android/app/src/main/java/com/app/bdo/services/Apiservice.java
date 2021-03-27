package com.app.bdo.services;

import com.app.bdo.model.User;
import com.app.bdo.utils.Logger;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.IOException;

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

        OkHttpClient client = new OkHttpClient();
        Request request = new Request.Builder().url(url).build();
        try (Response response = client.newCall(request).execute()) {
            return response.body().string();
        }

    }

}
