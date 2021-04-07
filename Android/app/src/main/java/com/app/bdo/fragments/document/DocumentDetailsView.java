package com.app.bdo.fragments.document;

import android.content.ClipData;
import android.content.ClipboardManager;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.text.TextUtils;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;

import com.app.bdo.R;
import com.app.bdo.helper.AppHelper;
import com.app.bdo.helper.Constants;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.ToastUtils;
import com.bumptech.glide.Glide;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;

public class DocumentDetailsView extends AppCompatActivity {

    private String fileLink;

    private ImageView imageView;

    private Uri localUri;

    private CredentialData selectedDocument;

    private TextView textContent;

    String resultString = "";

    int fileType = 0;

    private WebView webview;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_document_details_view);

//      Setting current instance
        AppHelper.getInstance().setmContext(this);

//      init views
        webview = findViewById(R.id.webview);

        imageView = findViewById(R.id.imageview);

        TextView textView = findViewById(R.id.title);

        textContent = findViewById(R.id.textContent);

//      Configure Navigation BAR
        ActionBar actionBar = getSupportActionBar();
        actionBar.setTitle("");
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeAsUpIndicator(R.drawable.custom_back_btn_icon);

//        Parsing selected docuement
        selectedDocument = AppHelper.getInstance().selectedDocument;

        // Setting the Expire date
        if (!TextUtils.isEmpty(selectedDocument.getExpire_date()) && !selectedDocument.getExpire_date().contains("0000-00-00")) {

            textView.setText("Expire at " + selectedDocument.getExpire_date());
        }

        fileLink = Constants.UPLOAD_PATH.concat(selectedDocument.getFile_name());

        if (fileLink.endsWith(".txt")) {

            fileType = 1;

            imageView.setVisibility(View.GONE);

            textContent.setVisibility(View.VISIBLE);

            webview.setVisibility(View.GONE);

            AsyncTask.execute(new Runnable() {
                @Override
                public void run() {

                    showTextFile();

                }
            });
        } else if (fileLink.endsWith(".pdf")) {

            fileType = 2;

            imageView.setVisibility(View.GONE);

            textContent.setVisibility(View.GONE);

            webview.setVisibility(View.VISIBLE);

            webview.getSettings().setJavaScriptEnabled(true);

            webview.loadUrl("https://docs.google.com/viewer?url=".concat(fileLink));
        } else {

            webview.setVisibility(View.GONE);

            fileType = 0;

            imageView.setVisibility(View.VISIBLE);

            textContent.setVisibility(View.GONE);

            Glide.with(this).load(fileLink).into(imageView);

        }

    }

    //    Download document Text file
    private void showTextFile() {

        List<String> content = new ArrayList<>();
        try {
            // Create a URL for the desired page
            URL url = new URL(fileLink);

            // Read all the text returned by the server
            BufferedReader in = new BufferedReader(new InputStreamReader(url.openStream()));
            String str;
            while ((str = in.readLine()) != null) {
                // str is one line of text; readLine() strips the newline character(s)

                resultString = resultString.concat("\n").concat(str);
                content.add(resultString);


            }
            in.close();

            AppHelper.getInstance().createTextFile(content);

            runOnUiThread(new Runnable() {
                @Override
                public void run() {

                    textContent.setText(resultString);
                }
            });

        } catch (MalformedURLException e) {
        } catch (IOException e) {
        }
    }

    //  Menu prepare
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.main_menu, menu);
        if (fileType == 1 && fileType == 2) {
            menu.findItem(R.id.add_photos).setVisible(false);
        }
        return true;
    }

    //    Menu item click  events
    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:

                onBackPressed();
                break;
            case R.id.share:

                openShare();
                break;
            case R.id.add_photos:

                saveToPhotos();
                break;
            case R.id.copy:

                copyLink();
                break;
            case R.id.delete:

                askDeletePermission();
                break;
        }
        return super.onOptionsItemSelected(item);
    }

    //  Get File local uri
    private Uri getLocalUri() {

        if (imageView == null || imageView.getDrawable() == null) {
            return null;
        }
        if (localUri != null) {
            return localUri;
        }

        Bitmap mBitmap = ((BitmapDrawable) imageView.getDrawable()).getBitmap();

        String path = MediaStore.Images.Media.insertImage(getContentResolver(), mBitmap, "Share ", null);

        localUri = Uri.parse(path);

        return localUri;
    }

    //  Share Intent method
    private void openShare() {

        Intent intent = new Intent(Intent.ACTION_SEND);

        if (fileType == 0) {

            // Share Image

            if (getLocalUri() == null) {
                return;
            }

            intent.setType("image/jpeg");

            intent.putExtra(Intent.EXTRA_STREAM, getLocalUri());

        } else {

//            Share text file

            intent.setType("text/plain");

            if (fileType == 1) {

                intent.putExtra(Intent.EXTRA_TEXT, textContent.getText().toString());

            } else {
                intent.putExtra(Intent.EXTRA_TEXT, fileLink);

            }

        }

        startActivity(Intent.createChooser(intent, "Share Image"));

    }

    //  Copy Manager
    private void copyLink() {

        ClipboardManager clipboard = (ClipboardManager) getSystemService(Context.CLIPBOARD_SERVICE);

        ClipData clip = ClipData.newPlainText("Copy", fileType == 1 ? textContent.getText().toString() : fileLink);

        clipboard.setPrimaryClip(clip);

        showToast(getString(R.string.link_copied));

    }

    //    Get Bitmap
    private void saveToPhotos() {

        if (imageView == null || imageView.getDrawable() == null) {
            return;
        }

        Bitmap mBitmap = ((BitmapDrawable) imageView.getDrawable()).getBitmap();

        saveImage(mBitmap);
    }

    //    Save Image to gallery
    private void saveImage(Bitmap finalBitmap) {

        String root = Environment.getExternalStorageDirectory().toString();

        File myDir = new File(root + "/saved_images");

        myDir.mkdirs();

        Random generator = new Random();

        int n = 10000;

        n = generator.nextInt(n);

        String fname = "Image-" + n + ".jpg";

        File file = new File(myDir, fname);

        if (file.exists()) file.delete();

        try {

            FileOutputStream out = new FileOutputStream(file);

            finalBitmap.compress(Bitmap.CompressFormat.JPEG, 90, out);

            out.flush();

            out.close();

        } catch (Exception e) {
            e.printStackTrace();
        }

        showToast(getString(R.string.add_to_phtos_sucess));
    }

    // Ask Delete Permission
    private void askDeletePermission() {

        Loader.showAlert(this, getString(R.string.delete_file), new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                deleteFiles();
            }
        }, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        });
    }

    // Delete document method

    private void deleteFiles() {

        Loader.showLoader(this);

        Boolean extra = String.valueOf(AppHelper.getInstance().getUser().getId()).equals(selectedDocument.getCreated_by()) ? true : false;

        AppHelper.getInstance().deleteCredentials(extra, selectedDocument.getCre_uid(), this, null);

    }

    //    Show alert message
    private void showToast(String message) {
        ToastUtils.show(this, message);
    }

    //    onDelete Callbacks
    public void onDeleted(Boolean sucess) {

        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Loader.hide();
                if (sucess) {

                    showToast(getString(R.string.file_deleted));

                    AppHelper.getInstance().needHomeviewRefresh = true;

                    onBackPressed();
                } else {
                    showToast(getString(R.string.file_deleted_error));
                }

            }
        });
    }
}