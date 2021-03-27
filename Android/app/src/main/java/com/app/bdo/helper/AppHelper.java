package com.app.bdo.helper;

import android.content.Context;

import com.app.bdo.utils.SharePrefUtils;
import com.facebook.login.LoginManager;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;

/**
 * Created by MobiDev on 26/03/21.
 */
public class AppHelper {
    private static final AppHelper ourInstance = new AppHelper();

    public static AppHelper getInstance() {
        return ourInstance;
    }

    private AppHelper() {
    }

    public void clearData(Context context) {

        SharePrefUtils.getSharedPrefEditor(context, SharePrefUtils.PREF_APP).clear().commit();

        logoutGoogle(context);

        logoutFb();

    }

    public void logoutGoogle(Context context) {
        try {
            GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                    .build();
            GoogleSignInClient mGoogleSignInClient = GoogleSignIn.getClient(context, gso);

            if (mGoogleSignInClient != null) {
                mGoogleSignInClient.signOut();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    public void logoutFb() {
        try {
            LoginManager.getInstance().logOut();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

}
