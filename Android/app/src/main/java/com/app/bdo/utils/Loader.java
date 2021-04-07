package com.app.bdo.utils;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.text.TextUtils;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;

import com.app.bdo.R;
import com.app.bdo.fragments.profile.ProfileFragment;
import com.app.bdo.fragments.document.CredentialData;
import com.app.bdo.fragments.document.DocumentFragment;
import com.app.bdo.fragments.profile.JobItem;

/**
 * Created by MobiDev on 26/03/21.
 */
public class Loader {

    private static ProgressDialog progressDialog;


    public interface EditextListener {

        void onSubmitted(String text);
    }

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

    public static void withEditText(String title,Context context,EditextListener editextListener) {
        LayoutInflater inflater = ((Activity)context).getLayoutInflater();
        View dialogView= inflater.inflate(R.layout.edittextview, null);

        AlertDialog builder = new AlertDialog.Builder(context)
        .setTitle(title).setView(dialogView).
        setCancelable(false).setNegativeButton(context.getString(R.string.cancel),null).setPositiveButton(context.getString(R.string.submit),null)
                .create();
        LinearLayout.LayoutParams lp = new LinearLayout.LayoutParams(
                LinearLayout.LayoutParams.MATCH_PARENT,
                LinearLayout.LayoutParams.MATCH_PARENT);

        EditText input = dialogView.findViewById(R.id.editeText);


        builder.setOnShowListener(new DialogInterface.OnShowListener() {
            @Override
            public void onShow(DialogInterface dialogInterface) {
                Button button = ((AlertDialog) builder).getButton(AlertDialog.BUTTON_POSITIVE);
                button.setOnClickListener(new View.OnClickListener() {

                    @Override
                    public void onClick(View view) {
                        // TODO Do something
                        if(TextUtils.isEmpty(input.getText().toString())){
                            ToastUtils.show(context,context.getString(R.string.input_field_required));
                            return;
                        }

                        editextListener.onSubmitted(input.getText().toString());
                        //Dismiss once everything is OK.
                        builder.dismiss();
                    }
                });
            }
        });

        builder.show();


    }

    public static void withProfileEditText(JobItem item, Context context, EditextListener editextListener) {
        LayoutInflater inflater = ((Activity)context).getLayoutInflater();
        View dialogView= inflater.inflate(R.layout.edittextview, null);
        String title = item.getTitle();
        if(item.getType().equals("skills")){
            title = context.getString(R.string.add_skills);
        }
        AlertDialog builder = new AlertDialog.Builder(context)
                .setTitle(title).setView(dialogView).
                        setCancelable(false).setNegativeButton(context.getString(R.string.cancel),null).setPositiveButton(context.getString(R.string.submit),null)
                .create();
        LinearLayout.LayoutParams lp = new LinearLayout.LayoutParams(
                LinearLayout.LayoutParams.MATCH_PARENT,
                LinearLayout.LayoutParams.MATCH_PARENT);

        EditText input = dialogView.findViewById(R.id.editeText);
        if(!TextUtils.isEmpty(item.getValue())){

            input.setText(item.getValue());
        }


        builder.setOnShowListener(new DialogInterface.OnShowListener() {
            @Override
            public void onShow(DialogInterface dialogInterface) {
                Button button = ((AlertDialog) builder).getButton(AlertDialog.BUTTON_POSITIVE);
                button.setOnClickListener(new View.OnClickListener() {

                    @Override
                    public void onClick(View view) {
                        // TODO Do something
                        if(TextUtils.isEmpty(input.getText().toString())){
                            ToastUtils.show(context,context.getString(R.string.input_field_required));
                            return;
                        }

                        editextListener.onSubmitted(input.getText().toString());
                        //Dismiss once everything is OK.
                        builder.dismiss();
                    }
                });
            }
        });

        builder.show();


    }


    public static void withDesirePayEdit(JobItem item, Context context, ProfileFragment fragment) {

        LayoutInflater inflater = ((Activity)context).getLayoutInflater();
        View dialogView= inflater.inflate(R.layout.edittextview, null);
        AlertDialog builder = new AlertDialog.Builder(context)
                .setTitle(item.getTitle()).setView(dialogView).
                        setCancelable(false).setNegativeButton(context.getString(R.string.cancel),null).setPositiveButton(context.getString(R.string.submit),null)
                .create();
        LinearLayout.LayoutParams lp = new LinearLayout.LayoutParams(
                LinearLayout.LayoutParams.MATCH_PARENT,
                LinearLayout.LayoutParams.MATCH_PARENT);

        EditText input = dialogView.findViewById(R.id.editeText);
        EditText inputTwo = dialogView.findViewById(R.id.editeText_2);
        inputTwo.setVisibility(View.VISIBLE);

        input.setHint("Min");
        inputTwo.setHint("Max");


        if(!TextUtils.isEmpty(item.getValue())){

            String [] sepaArray = item.getValue().split("-");
            if(sepaArray.length == 2 ){
                input.setText(sepaArray[0]);
                inputTwo.setText(sepaArray[1]);
            }
        }


        builder.setOnShowListener(new DialogInterface.OnShowListener() {
            @Override
            public void onShow(DialogInterface dialogInterface) {
                Button button = ((AlertDialog) builder).getButton(AlertDialog.BUTTON_POSITIVE);
                button.setOnClickListener(new View.OnClickListener() {

                    @Override
                    public void onClick(View view) {
                        // TODO Do something
                        if(TextUtils.isEmpty(input.getText().toString())||TextUtils.isEmpty(inputTwo.getText().toString())){
                            ToastUtils.show(context,context.getString(R.string.input_field_required));
                            return;
                        }

                        fragment.onDesirepaysubmitted(item,input.getText().toString(),inputTwo.getText().toString());

                        //Dismiss once everything is OK.
                        builder.dismiss();
                    }
                });
            }
        });

        builder.show();


    }

    public static  void askFileExpire(Context context, DocumentFragment fragment){

        AlertDialog alertDialog = new AlertDialog.Builder(context).create();

        alertDialog.setTitle(context.getString(R.string.ask_file_expire_title));

        alertDialog.setButton(AlertDialog.BUTTON_POSITIVE, context.getText(R.string.yes), new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int id) {

                //...

                fragment.chooseExpireDate();

            } });

        alertDialog.setButton(AlertDialog.BUTTON_NEGATIVE, context.getString(R.string.skip), new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int id) {

                //...

                fragment.skipDateSelection();

            }});

        alertDialog.setButton(AlertDialog.BUTTON_NEUTRAL, context.getString(R.string.cancel_upload), new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int id) {

                //...

            }});

        alertDialog.show();
    }


    public static  void askDocumentEdit(CredentialData data,Context context, DocumentFragment fragment){

        AlertDialog alertDialog = new AlertDialog.Builder(context).create();

        alertDialog.setTitle(context.getString(R.string.edit_option));

        alertDialog.setButton(AlertDialog.BUTTON_POSITIVE, context.getText(R.string.edit), new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int id) {

                //...

                fragment.onEditDocument(data);

            } });

        alertDialog.setButton(AlertDialog.BUTTON_NEGATIVE, context.getString(R.string.delete), new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int id) {

                //...

                fragment.onDeleteDocument(data);

            }});

        alertDialog.setButton(AlertDialog.BUTTON_NEUTRAL, context.getString(R.string.cancel), new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int id) {

                //...

            }});

        alertDialog.show();
    }

    public static void showFileChooseDialog(Context context,DocumentFragment fragment) {
        AlertDialog.Builder alertDialog = new AlertDialog.Builder(context);
        alertDialog.setTitle("Choose File Type");
        String[] items = {"Image","PDF","TEXT"};
        int checkedItem = -1;
        alertDialog.setSingleChoiceItems(items, checkedItem, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                Logger.debug("showFileChooseDialog ", String.valueOf(which));
                dialog.dismiss();
                switch (which) {
                    case 0:
                        fragment.onFileTypeChoosed(0);
                        break;
                    case 1:
                        fragment.onFileTypeChoosed(1);
                        break;
                    case 2:
                        fragment.onFileTypeChoosed(2);
                        break;
                }
            }
        });
        AlertDialog alert = alertDialog.create();
        alert.setCanceledOnTouchOutside(false);
        alert.show();
    }


}
