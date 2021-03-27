package com.app.bdo.activity.auth;

import android.content.DialogInterface;
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
import com.app.bdo.databinding.ActivityForgotViewBinding;
import com.app.bdo.helper.Constants;
import com.app.bdo.services.Apiservice;
import com.app.bdo.utils.Loader;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import okhttp3.RequestBody;

public class ForgotView extends AppCompatActivity implements View.OnClickListener {

    private ActivityForgotViewBinding forgotViewBinding;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        forgotViewBinding = DataBindingUtil.setContentView(this, R.layout.activity_forgot_view);

        ActionBar actionBar = getSupportActionBar();
        actionBar.setTitle("");
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeAsUpIndicator(R.drawable.custom_back_btn_icon);

        addBtnListener();
    }


    private void addBtnListener() {

        forgotViewBinding.sendcode.setOnClickListener(this);

        forgotViewBinding.emailEdit.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void afterTextChanged(Editable editable) {
                forgotViewBinding.emailEdit.setError(null);
            }
        });
    }

    private boolean validate() {
        if (TextUtils.isEmpty(forgotViewBinding.emailEdit.getText().toString())) {
            forgotViewBinding.emailEdit.setError(getString(R.string.email_valid_erro));
            return false;
        }
        return true;
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
                sendCode();
                break;

        }
    }

    private void sendCode() {
        if (validate()) {

            Loader.showLoader(this);

            RequestBody requestBody = Apiservice.getVerificationRequest(forgotViewBinding.emailEdit.getText().toString());

            AsyncTask.execute(new Runnable() {
                @Override
                public void run() {
                    try {
                        String results = Apiservice.getInstance().makePost(Constants.FORGOT_PASWD, requestBody);
                        validateResults(results);
                    } catch (IOException e) {
                        e.printStackTrace();

                        runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                Loader.hide();
                                Loader.showAlert(ForgotView.this, "", e.getLocalizedMessage());
                            }
                        });
                    }
                }
            });
        }
    }

    private void validateResults(String results) {

        runOnUiThread(new Runnable() {
            @Override
            public void run() {

                Loader.hide();

                try {
                    JSONObject jsonObject = new JSONObject(results);
                    if (jsonObject.has("status")) {
                        String message = jsonObject.getString("msg");

                        if (jsonObject.getString("status").equals("success")) {
                            Loader.showAlert(ForgotView.this, "", message, new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialogInterface, int i) {
                                    onBackPressed();
                                }
                            });
                        } else {
                            Loader.showAlert(ForgotView.this, "", message);
                        }


                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }
        });
    }
}