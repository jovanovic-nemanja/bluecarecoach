package com.app.bdo.activity.auth;

import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.view.MenuItem;
import android.view.View;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;
import androidx.databinding.DataBindingUtil;

import com.app.bdo.R;
import com.app.bdo.databinding.ActivityVerificationBinding;
import com.app.bdo.helper.Constants;
import com.app.bdo.services.Apiservice;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.Logger;
import com.app.bdo.utils.ToastUtils;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import okhttp3.RequestBody;

public class VerificationActivity extends AppCompatActivity implements View.OnClickListener {

    private String TAG = VerificationActivity.class.getName();

    private String email;

    private ActivityVerificationBinding verificationBinding;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        verificationBinding = DataBindingUtil.setContentView(this, R.layout.activity_verification);

        /* Configure Naviagtion bar */
        ActionBar actionBar = getSupportActionBar();
        actionBar.setTitle("");
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeAsUpIndicator(R.drawable.custom_back_btn_icon);

        /* Button Listener */
        addBtnListener();

        /* parse email */
        email = getIntent().getStringExtra("email");
    }

    /* button actions init */
    private void addBtnListener() {

        verificationBinding.validateCode.setOnClickListener(this);

        verificationBinding.reSendCode.setOnClickListener(this);

        verificationBinding.codeEdit.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void afterTextChanged(Editable editable) {
                verificationBinding.codeEdit.setError(null);
            }
        });
    }

    /* Validation */
    private boolean validate() {

        if (TextUtils.isEmpty(verificationBinding.codeEdit.getText().toString())) {

            verificationBinding.codeEdit.setError(getString(R.string.verificaion_code_error));
            return false;
        }
        return true;
    }

    /* Verification method */
    private void sendCode() {

        if (validate()) {
            verifyCode();
        }
    }

    /* Recreate Code */
    private void resendCode() {

        Loader.showLoader(this);

        Logger.debug(TAG, "response body");

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {

                    RequestBody body = Apiservice.getVerificationRequest(email);

                    String response = Apiservice.getInstance().makePost(Constants.EMAIL_VERIFICATION, body);
                    Logger.debug(TAG, response);

                    validateResults(response);

                } catch (IOException e) {
                    e.printStackTrace();

                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {

                            Loader.hide();
                            ToastUtils.show(VerificationActivity.this, e.getLocalizedMessage());
                        }
                    });

                }
            }
        });

    }

    /* Verification Method */

    private void verifyCode() {

        Logger.debug(TAG, "verifyCode");

        Loader.showLoader(this);

        RequestBody body = Apiservice.getValidationRequest(email, verificationBinding.codeEdit.getText().toString());

        Logger.debug(TAG, "response body");

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {

                    String response = Apiservice.getInstance().makePost(Constants.EMAIL_VALIDATE, body);
                    Logger.debug(TAG, response);

                    verifyResults(response);
                } catch (IOException e) {
                    e.printStackTrace();
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {

                            Loader.hide();
                            ToastUtils.show(VerificationActivity.this, e.getLocalizedMessage());
                        }
                    });

                }
            }
        });

    }

    /* verify */

    private void verifyResults(String response) {

        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Loader.hide();
                try {
                    JSONObject jsonObject = new JSONObject(response);

                    Logger.debug(TAG, jsonObject.toString());

                    if (jsonObject.has("status")) {

                        String message = jsonObject.getString("msg");

                        if (jsonObject.getString("status").equals("success")) {

                            goToNext();

                        } else {

                            Loader.showAlert(VerificationActivity.this, "", message);
                        }
                    }
                } catch (Exception e) {

                    Loader.showAlert(VerificationActivity.this, "", e.getLocalizedMessage());

                }
            }
        });

    }

    /* Redirect to user registration screen */

    private void goToNext() {

        Intent register = new Intent(this, Register.class);

        register.putExtra("email", email);

        startActivity(register);

        finish();
    }

    /* Validation api response */

    private void validateResults(String response) {

        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Loader.hide();
                try {

                    JSONObject results = new JSONObject(response);
                    Logger.debug(TAG, "validateResults " + results);

                    if (results.has("status")) {

                        String message = results.getString("msg");

                        Loader.showAlert(VerificationActivity.this, "", message);
                    }
                } catch (JSONException e) {

                    Logger.debug(TAG, "validateResults " + e.getLocalizedMessage());
                    e.printStackTrace();
                }
            }
        });

    }

    /* Menu item onclick events */

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                break;
        }
        return true;
    }

    /* OnClick events */

    @Override
    public void onClick(View view) {

        switch (view.getId()) {
            case R.id.validateCode:

                sendCode();
                break;

            case R.id.reSendCode:

                resendCode();
                break;

        }
    }

}