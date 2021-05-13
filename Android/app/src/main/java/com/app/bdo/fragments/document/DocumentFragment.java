package com.app.bdo.fragments.document;

import android.Manifest;
import android.app.Activity;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.pdf.PdfDocument;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.text.TextUtils;
import android.util.Log;
import android.util.SparseArray;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.core.content.FileProvider;
import androidx.databinding.DataBindingUtil;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;

import com.app.bdo.R;
import com.app.bdo.databinding.FragmentDocumentBinding;
import com.app.bdo.helper.AppHelper;
import com.app.bdo.scanlibrary.ScanActivity;
import com.app.bdo.scanlibrary.ScanConstants;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.Logger;
import com.app.bdo.utils.ToastUtils;
import com.github.florent37.singledateandtimepicker.SingleDateAndTimePicker;
import com.github.florent37.singledateandtimepicker.dialog.SingleDateAndTimePickerDialog;
import com.google.android.gms.vision.Frame;
import com.google.android.gms.vision.text.TextBlock;
import com.google.android.gms.vision.text.TextRecognizer;
import com.gun0912.tedpermission.PermissionListener;
import com.gun0912.tedpermission.TedPermission;
import com.opensooq.supernova.gligar.GligarPicker;
import com.yalantis.ucrop.UCrop;

import java.io.File;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import static android.app.Activity.RESULT_OK;
import static com.facebook.FacebookSdk.getCacheDir;

//import com.github.haggholm.scanlibrary.ScanActivity;
//import com.github.haggholm.scanlibrary.ScanConstants;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link DocumentFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class DocumentFragment extends Fragment implements View.OnClickListener {

    private final String TAG = "DocumentFragment";

    private FragmentDocumentBinding documentBinding;

    private DocumentAdapter adapter;

    private int REQUEST_CODE = 99;

    private int REQUEST_CODE_CHOOSE = 300;

    private int REQUEST_PERMISSION = 100;

    CredentialData credentialData;

    Uri selectedUri;

    String selectedExpireDate;

    int selectedView = 0;

    Bitmap selectedBitmap;

    private Boolean showCamera = false;


    public DocumentFragment() {
        // Required empty public constructor
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        documentBinding = DataBindingUtil.inflate(inflater, R.layout.fragment_document, container, false);

        return documentBinding.getRoot();
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

//        Button action
        documentBinding.list.setOnClickListener(this);
        documentBinding.attachment.setOnClickListener(this);
        documentBinding.extras.setOnClickListener(this);


//        Setup Adapter
        adapter = new DocumentAdapter(new DocumentAdapter.onRowItemClickListener() {
            @Override
            public void onSelectedItem(int pos, CredentialData data) {

                credentialData = data;

                if (TextUtils.isEmpty(data.getFile_name())) {

                    upLoadCredentials(Integer.parseInt(data.getId()));

                } else {

//                   Setting selected document
                    AppHelper.getInstance().selectedDocument = data;

//                    Redirect to docuement Details view
                    Intent detailIntent = new Intent(getActivity(), DocumentDetailsView.class);

                    startActivity(detailIntent);
                }
            }

            @Override
            public void onLongPressed(int pos, CredentialData data) {

                askDeletePermission(pos, data);
            }
        });

//        Setup layout manager

        documentBinding.listview.setLayoutManager(new LinearLayoutManager(getContext()));
        documentBinding.listview.setAdapter(adapter);

        documentBinding.addBtn.setOnClickListener(this);
        documentBinding.emailBtn.setOnClickListener(this);

//        Loading Data from api
        loadData();

    }

    private void askDeletePermission(int pos, CredentialData data) {

        if (selectedView == 2) {

            Loader.showAlert(getContext(), getString(R.string.delete_file), new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {

                    Loader.showLoader(getContext());
                    Boolean extra = String.valueOf(AppHelper.getInstance().getUser().getId()).equals(data.getCreated_by()) ? true : false;
                    AppHelper.getInstance().deleteCredentials(extra, extra ? data.getId() : data.getCre_uid(), null, DocumentFragment.this);
                }
            }, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {

                }
            });
        } else {

            Loader.askDocumentEdit(data, getContext(), this);

        }
    }

    @Override
    public void onClick(View view) {

        switch (view.getId()) {

            case R.id.emailBtn:

                downloadEmailcontent();
                break;
            case R.id.addBtn:

                addCredentials();
                break;

            case R.id.list:

                selectedView = 0;
                if (adapter != null) {
                    adapter.currentView = selectedView;
                }

                listBtnSelected();

                doFilterList();
                break;
            case R.id.attachment:

                selectedView = 1;

                if (adapter != null) {
                    adapter.currentView = selectedView;
                }

                attachmentBtnSelected();

                doFilterAttachment();
                break;
            case R.id.extras:

                selectedView = 2;
                if (adapter != null) {
                    adapter.currentView = selectedView;
                }

                extrasBtnSelected();

                doFileterExtras();
                break;
        }

    }

    // Attach File in Email intent
    public void downloadEmailcontent() {

        if (AppHelper.getInstance().getCredentialDataSource().size() == 0) {
            return;
        }

        if (adapter.getSelectedDataList().size() == 0) {
            documentBinding.emailBtn.setText("Send");
            adapter.startSelection(true);
            updateAddBtn();
            return;
        }

        documentBinding.addBtn.setImageResource(R.drawable.add_icon);


        Loader.showLoader(getContext());

        documentBinding.emailBtn.setText("Email");
        adapter.startSelection(false);

        AppHelper.getInstance().downloadFiles(adapter.getSelectedDataList(), this);

    }

    //    Refresh buttons
    private void updateAddBtn() {

        if (adapter != null && adapter.showCheckBox) {
            documentBinding.addBtn.setImageResource(R.drawable.close_icon);

        } else {
            documentBinding.addBtn.setImageResource(R.drawable.add_icon);

        }
    }

    //    Send Email
    public void composeEmail(ArrayList<Uri> uris) {

        try {

            adapter.getSelectedDataList().clear();

            String email = "";
            if (TextUtils.isEmpty(AppHelper.getInstance().getUser().getEmail())) {
                email = AppHelper.getInstance().getUser().getEmail();
            }

            Intent intent = new Intent(Intent.ACTION_SEND_MULTIPLE);

            intent.setData(Uri.parse("mailto:")); // only email apps should handle this

            intent.putExtra(Intent.EXTRA_EMAIL, email);

            intent.putExtra(Intent.EXTRA_SUBJECT, "Credential File");

            intent.setType("text/plain");

            intent.putParcelableArrayListExtra(Intent.EXTRA_STREAM, uris);

            Loader.hide();

            if (intent.resolveActivity(getActivity().getPackageManager()) != null) {

                startActivity(intent);

                AppHelper.getInstance().resetEmailAttachmentLog();

            }

        } catch (Exception e) {
            Logger.debug(TAG, "composeEmail " + e.getLocalizedMessage());
        }
    }

    //    Reloading ListView
    private void doFilterList() {

        AppHelper.getInstance().getCredentialDataList().clear();

        List<CredentialData> data = AppHelper.getInstance().getList();

        AppHelper.getInstance().getCredentialDataList().addAll(data);

        relaodAdapter();
    }

    //    Reload Extras
    private void doFileterExtras() {

        AppHelper.getInstance().getCredentialDataList().clear();

        List<CredentialData> data = AppHelper.getInstance().getExtras();

        AppHelper.getInstance().getCredentialDataList().addAll(data);

        relaodAdapter();
    }

    //    Refresh Adapter
    private void relaodAdapter() {

        if (adapter != null) {
            adapter.notifyDataSetChanged();
        }
    }

    //    Reload Attachment view
    private void doFilterAttachment() {

        AppHelper.getInstance().getCredentialDataList().clear();

        List<CredentialData> data = AppHelper.getInstance().getAttachment();

        AppHelper.getInstance().getCredentialDataList().addAll(data);

        relaodAdapter();
    }

    //    Create Temp image file
    private Uri createImageFile() {

        String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new
                Date());

        File file = new File(ScanConstants.IMAGE_PATH, "IMG_" + timeStamp +
                ".jpg");

        selectedUri = Uri.fromFile(file);

        return selectedUri;
    }

    //    Activity Callbacks
    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == REQUEST_PERMISSION && resultCode == Activity.RESULT_OK) {

            requestPhoto(showCamera);
        }

        else if (requestCode == REQUEST_CODE_CHOOSE && resultCode == Activity.RESULT_OK) {

            String pathsList[] = data.getExtras().getStringArray(GligarPicker.IMAGES_RESULT);
            String selectedImage = pathsList[0];
            Logger.debug("Matisse", "selectedImage: " + selectedImage);

            openCropper(Uri.fromFile(new File(selectedImage)));

        }

        else if (requestCode == REQUEST_CODE && resultCode == RESULT_OK) {

            Uri uri = data.getExtras().getParcelable(ScanConstants.SCANNED_RESULT);

            Logger.debug("scanner results ", "uri" + uri);

            openCropper(uri);

        } else if (resultCode == RESULT_OK && requestCode == UCrop.REQUEST_CROP) {

            final Uri resultUri = UCrop.getOutput(data);

            selectedUri = resultUri;

            try {

                selectedBitmap = MediaStore.Images.Media.getBitmap(getActivity().getContentResolver(), resultUri);

            } catch (IOException e) {
                e.printStackTrace();
            }

            askExpireDateSelection();

        } else if (resultCode == UCrop.RESULT_ERROR) {
            final Throwable cropError = UCrop.getError(data);
            Logger.debug("Crop ", "Erro" + cropError);
        }
    }

    private void openCropper(Uri uri) {

        selectedUri = Uri.fromFile(new File(getCacheDir(), "SampleCropImage.png"));

        Logger.debug("scanner selectedUri ", "> " + selectedUri);

        UCrop.of(uri,selectedUri)
                .start(getContext(), this);
    }

    //    attachmentBtn  enable/disable
    private void attachmentBtnSelected() {

        documentBinding.list.setBackgroundColor(getResources().getColor(R.color.white));

        documentBinding.attachment.setBackgroundColor(getResources().getColor(R.color.sky_blue_clr));

        documentBinding.extras.setBackgroundColor(getResources().getColor(R.color.white));

        documentBinding.list.setTextColor(getResources().getColor(R.color.sky_blue_clr));

        documentBinding.attachment.setTextColor(getResources().getColor(R.color.white));

        documentBinding.extras.setTextColor(getResources().getColor(R.color.sky_blue_clr));

    }

    // extrasBtn  enable/disable
    private void extrasBtnSelected() {

        documentBinding.list.setBackgroundColor(getResources().getColor(R.color.white));

        documentBinding.attachment.setBackgroundColor(getResources().getColor(R.color.white));

        documentBinding.extras.setBackgroundColor(getResources().getColor(R.color.sky_blue_clr));

        documentBinding.list.setTextColor(getResources().getColor(R.color.sky_blue_clr));

        documentBinding.attachment.setTextColor(getResources().getColor(R.color.sky_blue_clr));

        documentBinding.extras.setTextColor(getResources().getColor(R.color.white));
    }

    // listBtn enable/disable
    private void listBtnSelected() {

        documentBinding.list.setBackgroundColor(getResources().getColor(R.color.sky_blue_clr));

        documentBinding.attachment.setBackgroundColor(ContextCompat.getColor(getContext(), R.color.white));

        documentBinding.extras.setBackgroundColor(ContextCompat.getColor(getContext(), R.color.white));

        documentBinding.list.setTextColor(ContextCompat.getColor(getContext(), R.color.white));

        documentBinding.attachment.setTextColor(ContextCompat.getColor(getContext(), R.color.sky_blue_clr));

        documentBinding.extras.setTextColor(ContextCompat.getColor(getContext(), R.color.sky_blue_clr));

    }


    //    Expire Date Picker Skip Option callbacks
    public void skipDateSelection() {

        Loader.showFileChooseDialog(getContext(), this);

    }

    // OCR METHODS

    public void extractText() {

        try {
            TextRecognizer textRecognizer = new TextRecognizer.Builder(getContext()).build();

            if (!textRecognizer.isOperational()) {
                // Note: The first time that an app using a Vision API is installed on a
                // device, GMS will download a native libraries to the device in order to do detection.
                // Usually this completes before the app is run for the first time.  But if that
                // download has not yet completed, then the above call will not detect any text,
                // barcodes, or faces.
                // isOperational() can be used to check if the required native libraries are currently
                // available.  The detectors will automatically become operational once the library
                // downloads complete on device.
                // Log.w(LOG_TAG, "Detector dependencies are not yet available.");

                // Check for low storage.  If there is low storage, the native library will not be
                // downloaded, so detection will not become operational.
                ToastUtils.show(getContext(), "OCR feature not supported on this device");
                return;

            }

            Loader.showLoader(getContext());

            Bitmap imageBitmap = selectedBitmap;

            Frame imageFrame = new Frame.Builder()
                    .setBitmap(imageBitmap)
                    .build();

            SparseArray<TextBlock> textBlocks = textRecognizer.detect(imageFrame);

            List<String> resultSet = new ArrayList<>();

            for (int i = 0; i < textBlocks.size(); i++) {

                TextBlock textBlock = textBlocks.get(textBlocks.keyAt(i));
                resultSet.add(textBlock.getValue());

            }

            if (resultSet.size() > 0) {

                File file = AppHelper.getInstance().createTextFile(resultSet);

                AppHelper.getInstance().uploadTextFile(credentialData, file, selectedExpireDate, this);

            } else {

                Loader.showAlert(getContext(), "", getString(R.string.invalid_ocr_content), new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {

                    }
                });
            }
        } catch (Exception e) {

            Log.e("OCR erro", e.getLocalizedMessage());
            getActivity().runOnUiThread(new Runnable() {
                @Override
                public void run() {

                    Loader.hide();
                    ToastUtils.show(getActivity(), "Please scan better quality image");
                }
            });
        }

    }

    //    onFile type didselect Callbacks
    public void onFileTypeChoosed(int type) {

        AppHelper.getInstance().needHomeviewRefresh = true;
        switch (type) {
            case 0:
                postFile(selectedUri);
                break;
            case 1:
                postPdfFile();
                break;

            case 2:
                extractText();
                break;
        }
    }

    //    Send pdf file to server
    private void postPdfFile() {

        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Loader.showLoader(getContext());

            }
        });

        // Create PDF
        Bitmap bitmap = selectedBitmap;

        PdfDocument pdfDocument = new PdfDocument();

        PdfDocument.PageInfo pageInfo = new PdfDocument.PageInfo.Builder(bitmap.getWidth(), bitmap.getHeight(), 1).create();

        PdfDocument.Page page = pdfDocument.startPage(pageInfo);

        Canvas canvas = page.getCanvas();

        Paint paint = new Paint();

        paint.setColor(Color.parseColor("#FFFFFF"));

        canvas.drawBitmap(bitmap, 0, 0, null);

        pdfDocument.finishPage(page);

//        Send to Server
        AppHelper.getInstance().creatPDF(credentialData, pdfDocument, selectedExpireDate, this);

    }

    //    Ask ExpireDate option
    public void askExpireDateSelection() {
        showCamera = false;
        Loader.askFileExpire(getContext(), this);

    }

//    Expire Date time picker

    public void chooseExpireDate() {

        new SingleDateAndTimePickerDialog.Builder(getContext())
                .bottomSheet()
                .curved()
                .displayMinutes(false)
                .displayHours(false)
                .displayDays(false)
                .displayMonth(true)
                .displayYears(true)
                .displayDaysOfMonth(true)
                .displayListener(new SingleDateAndTimePickerDialog.DisplayListener() {
                    @Override
                    public void onDisplayed(SingleDateAndTimePicker picker) {
                        // Retrieve the SingleDateAndTimePicker
                    }

                    @Override
                    public void onClosed(SingleDateAndTimePicker picker) {
                        // On dialog closed
                    }
                })
                .title("Choose Expire Date")
                .listener(new SingleDateAndTimePickerDialog.Listener() {
                    @Override
                    public void onDateSelected(Date date) {

                        selectedExpireDate = AppHelper.getInstance().getFormateDate(date);

                        Loader.showFileChooseDialog(getContext(), DocumentFragment.this);

                    }
                }).display();
    }

//    upload file to server

    private void postFile(Uri uri) {

        Loader.showLoader(getContext());
        AppHelper.getInstance().uploadFiles(credentialData, uri, selectedExpireDate, this);
    }

    //    addCredentials
    private void addCredentials() {

        if (adapter != null && adapter.showCheckBox) {

            documentBinding.addBtn.setImageResource(R.drawable.add_icon);

            adapter.startSelection(false);

            adapter.getSelectedDataList().clear();

            adapter.notifyDataSetChanged();

            documentBinding.emailBtn.setText("Email");
            return;
        }


        Loader.withEditText(getString(R.string.enter_credential_page), getContext(), new Loader.EditextListener() {
            @Override
            public void onSubmitted(String text) {

                Loader.showLoader(getContext());
                AppHelper.getInstance().addCustomCredential(text, DocumentFragment.this);

            }
        });
    }

//    upload file

    private void upLoadCredentials(int fileId) {

        PermissionListener permissionlistener = new PermissionListener() {
            @Override
            public void onPermissionGranted() {

                askChoices();

            }

            @Override
            public void onPermissionDenied(List<String> deniedPermissions) {
                ToastUtils.show(getActivity(), getString(R.string.scan_permission));
            }

        };

        TedPermission.with(getActivity())
                .setPermissionListener(permissionlistener)
                .setDeniedMessage("If you reject permission,you can not use this service\n\nPlease turn on permissions at [Setting] > [Permission]")
                .setPermissions(Manifest.permission.CAMERA, Manifest.permission.WRITE_EXTERNAL_STORAGE, Manifest.permission.READ_EXTERNAL_STORAGE)
                .check();

    }

    private void askChoices() {

        AlertDialog.Builder alertDialog = new AlertDialog.Builder(getContext());
        alertDialog.setTitle(getString(R.string.upload_document));
        String[] items = {getString(R.string.take_photo),getString(R.string.choose_from_gallery),getString(R.string.use_scanner)};
        alertDialog.setItems(items, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                switch(which) {
                    case 0:
                        showCamera = true;
                        openImagehooser(showCamera);
                        break;
                    case 1:
                        showCamera = false;
                        openImagehooser(showCamera);
                        break;
                    case 2:
                        openScanner();
                        break;

                }
            }
        });
        alertDialog.setNegativeButton(R.string.cancel, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                dialogInterface.cancel();
            }
        });
        AlertDialog alert = alertDialog.create();
        alert.setCanceledOnTouchOutside(false);
        alert.show();

    }

    private void openImagehooser(Boolean showCamera) {
        askForPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE, REQUEST_PERMISSION);
    }

    // Check permission
    private void askForPermission(String permission, Integer requestCode) {
        if (ContextCompat.checkSelfPermission(getActivity(), permission) != PackageManager.PERMISSION_GRANTED) {

            // Should we show an explanation?
            if (ActivityCompat.shouldShowRequestPermissionRationale(getActivity(), permission)) {

                //This is called if user has denied the permission before
                //In this case I am just asking the permission again
                ActivityCompat.requestPermissions(getActivity(), new String[]{permission}, requestCode);

            } else {

                ActivityCompat.requestPermissions(getActivity(), new String[]{permission}, requestCode);
            }
        } else {
            requestPhoto(showCamera);
        }
    }

    // open image picker
    private void requestPhoto(Boolean showCamera) {
        new GligarPicker()
                .cameraDirect(showCamera)
                .limit(1).requestCode(REQUEST_CODE_CHOOSE).withFragment(this).show();

    }

//    open Camera

    private void openScanner() {

        int preference = ScanConstants.OPEN_CAMERA;

        Intent intent = new Intent(getActivity(), ScanActivity.class);

        intent.putExtra(ScanConstants.OPEN_INTENT_PREFERENCE, preference);

        startActivityForResult(intent, REQUEST_CODE);
    }

    //    Load data from api
    public void loadData() {

        Loader.showLoader(getContext());
        AppHelper.getInstance().loadCredentials(this);

    }

//    Callbacks

    public void onAddCredentials() {

        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Loader.hide();
                onRefresh("credentials", true);
            }
        });

    }

//    Callbacks

    public void onDeleted(Boolean sucess) {

        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {

                Loader.hide();
                if (sucess) {

                    ToastUtils.show(getActivity(), getString(R.string.delete_file_credentials));
                    AppHelper.getInstance().needHomeviewRefresh = true;
                    onRefresh("credentials", false);

                } else {

                    ToastUtils.show(getActivity(), getString(R.string.file_deleted_error));
                }
            }
        });
    }

//    UIRefresh

    public void onRefresh(String type, Boolean isAdded) {

        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Loader.hide();
                if (isAdded) {
                    ToastUtils.show(getActivity(), getString(R.string.added_credentials));

                }
                if (adapter != null) {
                    adapter.setDocuementType(type);
                    if (selectedView == 0) {
                        doFilterList();
                    } else if (selectedView == 1) {
                        doFilterAttachment();
                    } else if (selectedView == 2) {
                        doFileterExtras();
                    }
                }
            }
        });

    }


//    Email Attachement Callback

    public void onEmailAttachmentDone() {

        Logger.debug(TAG, "onEmailAttachmentDone ...");

        ArrayList<Uri> uriList = new ArrayList<>();

        for (CredentialData item : adapter.getSelectedDataList()) {

            String fileName = item.getFile_name();

            File pathLoc = new File(AppHelper.getInstance().getRootDirPath(getContext()), fileName);
            if (pathLoc.exists()) {

                Uri tempFileUri = FileProvider.getUriForFile(
                        getActivity().getApplicationContext(),
                        "com.app.bdo.provider", // As defined in Manifest
                        pathLoc);

                uriList.add(tempFileUri);
            }
        }

        composeEmail(uriList);
    }

    //    Document edit view
    public void onEditDocument(CredentialData data) {

        credentialData = data;

        // askChoices();

        upLoadCredentials(Integer.parseInt(data.getId()));

    }

//    Delete document callbacks

    public void onDeleteDocument(CredentialData data) {

        Loader.showLoader(getContext());

        Boolean extra = String.valueOf(AppHelper.getInstance().getUser().getId()).equals(data.getCreated_by()) ? true : false;
        if (selectedView == 0) {
            extra = false;
        }
        AppHelper.getInstance().deleteCredentials(extra, extra ? data.getId() : data.getCre_uid(), null, DocumentFragment.this);

    }


}