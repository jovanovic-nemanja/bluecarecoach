package com.app.bdo.activity.auth;

import android.content.DialogInterface;
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
import com.app.bdo.databinding.ActivitySignupBinding;
import com.app.bdo.helper.Constants;
import com.app.bdo.services.Apiservice;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.Logger;
import com.app.bdo.utils.ToastUtils;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import okhttp3.RequestBody;

public class Signup extends AppCompatActivity implements View.OnClickListener {

    private String TAG = Signup.class.getName();

    private ActivitySignupBinding signupBinding;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        signupBinding = DataBindingUtil.setContentView(this, R.layout.activity_signup);

        ActionBar actionBar = getSupportActionBar();
        actionBar.setTitle("");
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeAsUpIndicator(R.drawable.custom_back_btn_icon);

        addBtnListener();
    }

    private void addBtnListener() {

        signupBinding.sendcode.setOnClickListener(this);

        signupBinding.emailEdit.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void afterTextChanged(Editable editable) {
                signupBinding.emailEdit.setError(null);
            }
        });
    }

    private boolean validate() {
        if (TextUtils.isEmpty(signupBinding.emailEdit.getText().toString())) {
            signupBinding.emailEdit.setError(getString(R.string.email_valid_erro));
            return false;
        }
        return true;
    }

    private void sendVerificationCode() {

        Logger.debug(TAG, "sendVerificationCode");

        Loader.showLoader(this);

        RequestBody body = Apiservice.getVerificationRequest(signupBinding.emailEdit.getText().toString());

        Logger.debug(TAG, "response body");

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {
                    String response = Apiservice.getInstance().makePost(Constants.EMAIL_VERIFICATION, body);
                    Logger.debug(TAG, "response");
                    validateResults(response);
                } catch (IOException e) {
                    e.printStackTrace();
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            Loader.hide();
                            ToastUtils.show(Signup.this, e.getLocalizedMessage());
                        }
                    });

                }
            }
        });

    }

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
                        if (results.getString("status").equals("success")) {
                            Loader.showAlert(Signup.this, "", message, new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialogInterface, int i) {
                                    goToVerification();

                                }
                            });
                        } else {
                            Loader.showAlert(Signup.this, "", message);
                        }

                    }
                } catch (JSONException e) {
                    Logger.debug(TAG, "validateResults " + e.getLocalizedMessage());
                    e.printStackTrace();
                }
            }
        });

    }

    private void goToVerification() {

        Intent verifictionIntent = new Intent(this, VerificationActivity.class);
        verifictionIntent.putExtra("email", signupBinding.emailEdit.getText().toString());
        startActivity(verifictionIntent);
    }


    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                break;
        }
        return true;
    }

    @Override
    public void onClick(View view) {

        switch (view.getId()) {
            case R.id.sendcode:
                Logger.debug(TAG, "sendcode clicked");
                sendCode();
                break;

        }
    }

    private void sendCode() {
        if (validate()) {
            sendVerificationCode();
        }
    }
}