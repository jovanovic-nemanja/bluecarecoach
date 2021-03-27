package com.app.bdo.utils;

import android.content.Context;
import android.widget.Toast;

/**
 * Created by MobiDev on 26/03/21.
 */
public class ToastUtils {


    public static void show(Context context, String message) {
        Toast.makeText(context, message, Toast.LENGTH_SHORT).show();
    }
}
