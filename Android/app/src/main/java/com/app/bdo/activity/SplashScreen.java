package com.app.bdo.activity;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;

import androidx.appcompat.app.AppCompatActivity;

import com.app.bdo.R;
import com.app.bdo.activity.auth.LoginActivity;
import com.app.bdo.helper.AppHelper;
import com.app.bdo.utils.SharePrefUtils;

public class SplashScreen extends AppCompatActivity {

    private Handler mWaitHandler = new Handler();
    private int SPLASH_DURATION = 1000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash_screen);

        /* Setting current instances  */

        AppHelper.getInstance().setmContext(this);

        /* Delay screen tasks */

        mWaitHandler.postDelayed(new Runnable() {
            @Override
            public void run() {
                Boolean hasSession = SharePrefUtils.getBooleanData(SplashScreen.this, SharePrefUtils.SESSION_KEY);
                if (hasSession) {
                    goToHome();
                    return;
                }
                goToLoginView();
            }
        }, SPLASH_DURATION);


    }

    /* Redirect to Main screen after user has session */

    private void goToHome() {
        Intent homeInent = new Intent(this, MainActivity.class);
        homeInent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(homeInent);
        finish();
    }

    /* Redirect to Login screen */

    private void goToLoginView() {

        Intent loginInent = new Intent(this, LoginActivity.class);
        startActivity(loginInent);
        finish();
    }

    @Override
    public void onDestroy() {
        super.onDestroy();

        /*

         Remove all the callbacks otherwise navigation will execute even after activity is killed or closed.

         */
        mWaitHandler.removeCallbacksAndMessages(null);
    }
}