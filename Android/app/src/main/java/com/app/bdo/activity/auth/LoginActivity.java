package com.app.bdo.activity.auth;

import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;

import androidx.appcompat.app.AppCompatActivity;
import androidx.databinding.DataBindingUtil;

import com.app.bdo.R;
import com.app.bdo.activity.MainActivity;
import com.app.bdo.databinding.ActivityLoginBinding;
import com.app.bdo.helper.Constants;
import com.app.bdo.model.User;
import com.app.bdo.services.Apiservice;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.Logger;
import com.app.bdo.utils.SharePrefUtils;
import com.app.bdo.utils.ToastUtils;
import com.facebook.CallbackManager;
import com.facebook.FacebookCallback;
import com.facebook.FacebookException;
import com.facebook.login.LoginManager;
import com.facebook.login.LoginResult;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInAccount;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.android.gms.common.api.ApiException;
import com.google.android.gms.tasks.Task;
import com.google.gson.Gson;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.Arrays;
import okhttp3.RequestBody;


import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.content.pm.Signature;
import android.util.Base64;

public class LoginActivity extends AppCompatActivity implements View.OnClickListener {

    private String TAG = LoginActivity.class.getName();
    private final int RC_SIGN_IN = 201;

    private ActivityLoginBinding loginBinding;
    private CallbackManager callbackManager;
    private GoogleSignInClient mGoogleSignInClient;

    private static final String EMAIL = "email";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        loginBinding = DataBindingUtil.setContentView(this, R.layout.activity_login);

        addBtnListener();

        initFb();

        initGoogle();


//       try {
//            PackageInfo info = getPackageManager().getPackageInfo(
//                    getPackageName(),
//                    PackageManager.GET_SIGNATURES);
//            for (Signature signature : info.signatures) {
//                MessageDigest md = MessageDigest.getInstance("SHA");
//                md.update(signature.toByteArray());
//                Log.d("KeyHash:", Base64.encodeToString(md.digest(), Base64.DEFAULT));
//            }
//        }
//        catch (PackageManager.NameNotFoundException e) {
//
//        }
//        catch (NoSuchAlgorithmException e) {
//
//        }

    }

    private void initGoogle() {
        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestEmail()
                .build();
        mGoogleSignInClient = GoogleSignIn.getClient(this, gso);

    }

    private void initFb() {

        callbackManager = CallbackManager.Factory.create();

        LoginManager.getInstance().registerCallback(callbackManager,
                new FacebookCallback<LoginResult>() {
                    @Override
                    public void onSuccess(LoginResult loginResult) {
                        // App code
                        Logger.debug("FB Login", String.valueOf(loginResult.getAccessToken().getUserId()));

                        processFBLogin(loginResult.getAccessToken().getUserId());
                    }

                    @Override
                    public void onCancel() {
                        // App code
                        Loader.hide();
                        ToastUtils.show(LoginActivity.this, getString(R.string.cance_fb));
                    }

                    @Override
                    public void onError(FacebookException exception) {
                        // App code
                        Loader.hide();
                        ToastUtils.show(LoginActivity.this, exception.getLocalizedMessage());

                    }
                });

    }

    private void addBtnListener() {

        loginBinding.signBtn.setOnClickListener(this);

        loginBinding.forgotPaswdBtn.setOnClickListener(this);

        loginBinding.signupBtn.setOnClickListener(this);

        loginBinding.fbLoginButton.setOnClickListener(this);

        loginBinding.googleLoginButton.setOnClickListener(this);


        loginBinding.emailEdit.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void afterTextChanged(Editable editable) {
                loginBinding.emailEdit.setError(null);
                loginBinding.passwordEdit.setError(null);
            }
        });
    }

    @Override
    public void onBackPressed() {

        Loader.showExitAlert(this, getString(R.string.ask_to_close_App));
    }

    @Override
    public void onClick(View view) {

        switch (view.getId()) {
            case R.id.signBtn:
                doLogin();
                break;

            case R.id.signup_btn:
                goToSignup();
                break;

            case R.id.forgot_paswd_btn:
                goToForgotView();
                break;

            case R.id.fb_login_button:
                doFbLogin();
                break;

            case R.id.google_login_button:
                doGoogleLogin();
                break;
        }
    }

    private void doGoogleLogin() {

        Intent signInIntent = mGoogleSignInClient.getSignInIntent();
        startActivityForResult(signInIntent, RC_SIGN_IN);

    }

    private void doFbLogin() {

        Loader.showLoader(this);

        LoginManager.getInstance().logIn(this, Arrays.asList(EMAIL));
    }

    private void goToForgotView() {

        Intent forgot = new Intent(this, ForgotView.class);
        startActivity(forgot);
    }

    private void goToSignup() {

        Intent signup = new Intent(this, Signup.class);
        startActivity(signup);
    }

    private void doLogin() {

        if (validate()) {
            createLoginRequest();
        }
    }

    private boolean validate() {
        if (TextUtils.isEmpty(loginBinding.emailEdit.getText().toString())) {
            loginBinding.emailEdit.setError(getString(R.string.email_valid_erro));
            return false;
        }
        if (TextUtils.isEmpty(loginBinding.passwordEdit.getText().toString())) {
            loginBinding.passwordEdit.setError(getString(R.string.password_valid_erro));
            return false;
        }
        return true;
    }

    private void createLoginRequest() {

        Loader.showLoader(this);

        JSONObject json = new JSONObject();
        try {

            json.put("email", loginBinding.emailEdit.getText().toString());
            json.put("password", loginBinding.passwordEdit.getText().toString());

        } catch (JSONException e) {
            e.printStackTrace();
        }

        RequestBody body = RequestBody.Companion.create(json.toString(), Apiservice.JSON);

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {
                    String response = Apiservice.getInstance().makePost(Constants.LOGIN_URL, body);
                    validateLoginResults(response);
                } catch (IOException e) {
                    e.printStackTrace();
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            Loader.hide();
                            ToastUtils.show(LoginActivity.this, e.getLocalizedMessage());
                        }
                    });

                }
            }
        });

    }

    private void validateLoginResults(String response) {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Logger.debug(TAG, response);
                Loader.hide();

                try {
                    JSONObject jsonObject = new JSONObject(response);
                    if (jsonObject.has("status")) {
                        String status = jsonObject.getString("status");
                        if (status.equals("failed")) {
                            String message = jsonObject.getString("msg");
                            Loader.showAlert(LoginActivity.this, getString(R.string.login_error), message);
                            return;
                        }

                        parseLoginDetails(jsonObject);
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        });

    }

    private void parseLoginDetails(JSONObject jsonObject) {

        try {
            JSONObject userData = jsonObject.getJSONObject("data");

            Gson gson = new Gson();

            User user = gson.fromJson(userData.toString(), User.class);

            SharePrefUtils.saveData(this, SharePrefUtils.SESSION_KEY, true);

            String results = gson.toJson(user);

            SharePrefUtils.saveData(this, SharePrefUtils.USER_DETAILS, results);

            Intent home = new Intent(this, MainActivity.class);
            home.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(home);
            finish();

        } catch (JSONException e) {
            e.printStackTrace();
        }


    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        if (requestCode == RC_SIGN_IN) {
            // The Task returned from this call is always completed, no need to attach
            // a listener.
            Task<GoogleSignInAccount> task = GoogleSignIn.getSignedInAccountFromIntent(data);
            handleSignInResult(task);
            return;
        }

        callbackManager.onActivityResult(requestCode, resultCode, data);
        super.onActivityResult(requestCode, resultCode, data);
    }

    private void handleSignInResult(Task<GoogleSignInAccount> completedTask) {
        try {
            GoogleSignInAccount account = completedTask.getResult(ApiException.class);

            // Signed in successfully, show authenticated UI.
            updateUI(account);
        } catch (ApiException e) {
            // The ApiException status code indicates the detailed failure reason.
            // Please refer to the GoogleSignInStatusCodes class reference for more information.
            Log.w(TAG, "signInResult:failed code=" + e.getStatusCode());
            updateUI(null);
        }
    }

    private void updateUI(GoogleSignInAccount account) {
        if (account != null) {
            Logger.debug(TAG, "Google signin" + account.getEmail());
            Logger.debug(TAG, "Google token" + account.getId());

            processGoogleLogin(account.getId());

        } else {
            ToastUtils.show(this, getString(R.string.google_login_erro));
        }
    }

    private void processGoogleLogin(String id) {

        Loader.showLoader(this);

        RequestBody requestBody = Apiservice.createGoogleloginRequest(id);

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {
                    String loginResults = Apiservice.getInstance().makePost(Constants.LOGIN_GOOGLE, requestBody);
                    validateLoginResults(loginResults);
                } catch (IOException e) {
                    e.printStackTrace();
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            Loader.hide();
                            Loader.showAlert(LoginActivity.this, "", e.getLocalizedMessage());
                        }
                    });
                }

            }
        });

    }

    private void processFBLogin(String id) {


        RequestBody requestBody = Apiservice.createFBloginRequest(id);

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {
                    String loginResults = Apiservice.getInstance().makePost(Constants.LOGIN_FB, requestBody);
                    validateLoginResults(loginResults);
                } catch (IOException e) {
                    e.printStackTrace();
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            Loader.hide();
                            Loader.showAlert(LoginActivity.this, "", e.getLocalizedMessage());
                        }
                    });
                }

            }
        });

    }
}
