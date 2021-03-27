package com.app.bdo.utils;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;

import com.app.bdo.R;

/**
 * Created by MobiDev on 26/03/21.
 */
public class Loader {

    private static ProgressDialog progressDialog;

    public static void showLoader(Context context) {

        progressDialog = new ProgressDialog(context);
        progressDialog.setMessage(context.getString(R.string.loading));
        progressDialog.setCancelable(false);
        progressDialog.setCanceledOnTouchOutside(false);
        progressDialog.show();
    }

    public static void hide() {

        if (progressDialog != null && progressDialog.isShowing()) {
            progressDialog.dismiss();
        }
    }

    public static void showAlert(Context context, String title, String message) {

        new AlertDialog.Builder(context)
                .setTitle(title)
                .setCancelable(false)
                .setMessage(message)

                .setPositiveButton(android.R.string.ok, new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // Continue with delete operation
                    }
                })

                .show();
    }

    public static void showAlert(Context context, String title, String message, DialogInterface.OnClickListener callbacks) {

        new AlertDialog.Builder(context)
                .setTitle(title)
                .setCancelable(false)
                .setMessage(message)
                .setPositiveButton(android.R.string.ok, callbacks)

                .show();
    }

    public static void showAlert(Context context, String message, DialogInterface.OnClickListener callbacks, DialogInterface.OnClickListener cancelCallback) {

        new AlertDialog.Builder(context)
                .setTitle("")
                .setCancelable(false)
                .setMessage(message)
                .setPositiveButton(context.getString(R.string.yes), callbacks)
                .setNegativeButton(context.getString(R.string.no), cancelCallback)
                .show();
    }


    public static void showExitAlert(Activity activity, String message) {

        new AlertDialog.Builder(activity)
                .setTitle("")
                .setCancelable(false)
                .setMessage(message)
                .setPositiveButton(activity.getString(R.string.yes), new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        activity.finish();

                    }
                })
                .setNegativeButton(activity.getString(R.string.no), new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {

                    }
                })
                .show();
    }


}
