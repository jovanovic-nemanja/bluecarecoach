package com.app.bdo.helper;

import android.content.Context;
import android.database.Cursor;
import android.graphics.pdf.PdfDocument;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Environment;
import android.provider.MediaStore;
import android.text.TextUtils;

import androidx.core.content.ContextCompat;

import com.app.bdo.fragments.document.CredentialData;
import com.app.bdo.fragments.document.DocumentDetailsView;
import com.app.bdo.fragments.document.DocumentFragment;
import com.app.bdo.fragments.home.HomeData;
import com.app.bdo.fragments.home.HomeFragment;
import com.app.bdo.fragments.profile.ProfileFragment;
import com.app.bdo.model.User;
import com.app.bdo.services.Apiservice;
import com.app.bdo.utils.Logger;
import com.app.bdo.utils.SharePrefUtils;
import com.facebook.login.LoginManager;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.gson.Gson;
import com.tonyodev.fetch2.Download;
import com.tonyodev.fetch2.Error;
import com.tonyodev.fetch2.Fetch;
import com.tonyodev.fetch2.FetchConfiguration;
import com.tonyodev.fetch2.FetchListener;
import com.tonyodev.fetch2.NetworkType;
import com.tonyodev.fetch2.Priority;
import com.tonyodev.fetch2.Request;
import com.tonyodev.fetch2core.DownloadBlock;
import com.tonyodev.fetch2core.FetchObserver;
import com.tonyodev.fetch2core.Reason;

import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;

import okhttp3.RequestBody;

//import com.github.haggholm.scanlibrary.ScanConstants;

/**
 * Created by MobiDev on 26/03/21.
 */
public class AppHelper {

    private static final AppHelper ourInstance = new AppHelper();

    private User user;
    private Context mContext;
    private Gson gson = new Gson();
    private HomeData homeData;
    private Fetch fetch;
    private DocumentFragment currentDocFrgment;
    private HomeFragment mHomeFragment;
    public HashMap<String, Object> downloadedList = new HashMap<>();

    private List<CredentialData> credentialDataSource = new ArrayList<>();
    private List<CredentialData> credentialDataList = new ArrayList<>();
    public CredentialData selectedDocument;

    public int SELECTED_FRAGMENT = 0;
    public int lastPlayedDuration = 0;
    public int totaldownloaedList = 0;
    public int currentDownloadList = 0;
    public boolean needHomeviewRefresh = true;

    public static AppHelper getInstance() {
        return ourInstance;
    }

    private AppHelper() {
    }

    public Context getmContext() {
        return mContext;
    }

    public void setmContext(Context mContext) {
        this.mContext = mContext;
    }

    public DocumentFragment getCurrentDocFrgment() {
        return currentDocFrgment;
    }

    public void setCurrentDocFrgment(DocumentFragment currentDocFrgment) {
        this.currentDocFrgment = currentDocFrgment;
    }

    public HomeData getHomeData() {
        return homeData;
    }

    public void setHomeData(HomeData homeData) {
        this.homeData = homeData;
    }

    public List<CredentialData> getCredentialDataSource() {
        return credentialDataSource;
    }

    public void setCredentialDataSource(List<CredentialData> credentialDataSource) {
        this.credentialDataSource = credentialDataSource;
    }

    public List<CredentialData> getCredentialDataList() {
        return credentialDataList;
    }

    public void setCredentialDataList(List<CredentialData> credentialDataList) {
        this.credentialDataList = credentialDataList;
    }

    public List<CredentialData> getList() {
        return getCredentialDataSource();
    }

    public List<CredentialData> getAttachment() {
        List<CredentialData> attachmentList = new ArrayList<>();
        for (int i = 0; i < getCredentialDataSource().size(); i++) {
            CredentialData data = getCredentialDataSource().get(i);
            if (!TextUtils.isEmpty(data.getFile_name())) {
                attachmentList.add(data);
            }
        }
        return attachmentList;
    }

    public List<CredentialData> getExtras() {
        List<CredentialData> extras = new ArrayList<>();
        for (int i = 0; i < getCredentialDataSource().size(); i++) {
            CredentialData data = getCredentialDataSource().get(i);
            if (!TextUtils.isEmpty(data.getCreated_by()) && data.getCreated_by().equals(String.valueOf(getUser().getId()))) {
                extras.add(data);
            }
        }
        return extras;
    }

    public String getFormateDate(Date date) {

        SimpleDateFormat fmt = new SimpleDateFormat("yyyy-MM-dd");
        return fmt.format(date);
//        Date date = fmt.parse(dateString);
//
//        SimpleDateFormat fmtOut = new SimpleDateFormat("dd-MM-yyyy");
//        return fmtOut.format(date);
    }

    public User getUser() {

        String userString = SharePrefUtils.getStringData(getmContext(), SharePrefUtils.USER_DETAILS);
        user = gson.fromJson(userString, User.class);
        return user;
    }

    public void setUser(User data) {
        this.user = data;
    }

    public void clearData(Context context) {

        AppHelper.getInstance().needHomeviewRefresh = true;

        AppHelper.getInstance().setHomeData(null);

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

    Boolean isLoadinVideoData = false;

    public void loadVideoLink(HomeFragment fragment) {

        if (isLoadinVideoData) {
            Logger.debug("loadVideoLink", "progress");

            return;
        }

        isLoadinVideoData = true;
        Logger.debug("loadVideoLink", "called");

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {

                try {
                    String url = Constants.GET_VIDEO_LINK.concat("?userid=").concat(String.valueOf(AppHelper.getInstance().getUser().getId()));
                    String results = Apiservice.getInstance().makeGet(url);
                    isLoadinVideoData = false;
                    Logger.debug("AppHelper*Video*Data", "Video link " + results);
                    try {

                        JSONObject jsonObject = new JSONObject(results);
                        if (jsonObject.has("status")) {
                            if (jsonObject.getString("status").equals("success")) {
                                JSONObject data = jsonObject.getJSONObject("data");
                                HomeData homeData = gson.fromJson(data.toString(), HomeData.class);
                                setHomeData(homeData);
                                fragment.onRefresh();
                            }
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        fragment.onRefresh();
                        isLoadinVideoData = false;
                    }
                } catch (IOException e) {
                    e.printStackTrace();
                    fragment.onRefresh();
                    isLoadinVideoData = false;
                }
            }
        });

    }

    public void loadCredentials(DocumentFragment documentFragment) {

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {

                try {
                    String userid = String.valueOf(AppHelper.getInstance().getUser().getId());
                    String url = Constants.GET_CREDENTIALS.concat("?userid=").concat(userid).concat("&created_by=").concat(userid);
                    String results = Apiservice.getInstance().makeGet(url);
                    Logger.debug("AppHelper", "loadCredentials " + results);
                    try {

                        JSONObject jsonObject = new JSONObject(results);
                        if (jsonObject.has("status")) {
                            getCredentialDataSource().clear();
                            getCredentialDataList().clear();
                            if (jsonObject.getString("status").equals("success")) {
                                JSONArray data = jsonObject.getJSONArray("data");
                                String path = jsonObject.getString("path");
                                for (int i = 0; i < data.length(); i++) {
                                    JSONObject dic = data.getJSONObject(i);
                                    CredentialData credentialData = gson.fromJson(dic.toString(), CredentialData.class);
                                    credentialData.setPath(path);
                                    getCredentialDataList().add(credentialData);
                                    getCredentialDataSource().add(credentialData);
                                }

                                documentFragment.onRefresh("credentials", false);
                            }
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        documentFragment.onRefresh("credentials", false);
                    }
                } catch (IOException e) {
                    e.printStackTrace();
                    documentFragment.onRefresh("credentials", false);
                }

            }
        });
    }


    public void addCustomCredential(String name, DocumentFragment fragment) {

        RequestBody body = Apiservice.getInstance().createCustomCredential(name);

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {

                try {
                    String results = Apiservice.getInstance().makePost(Constants.ADD_CREDENTIALS, body);
                    try {
                        JSONObject jsonObject = new JSONObject(results.toString());

                        if (jsonObject.has("status") && jsonObject.getString("status").equals("success")) {
                            getCredentialDataList().clear();
                            getCredentialDataSource().clear();
                            JSONArray data = jsonObject.getJSONArray("data");
                            for (int i = 0; i < data.length(); i++) {
                                JSONObject dic = data.getJSONObject(i);
                                CredentialData credentialData = gson.fromJson(dic.toString(), CredentialData.class);
                                credentialData.setPath(Constants.UPLOAD_PATH);
                                getCredentialDataList().add(credentialData);
                                getCredentialDataSource().add(credentialData);
                            }

                            fragment.onAddCredentials();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        });
    }


    public String getPath(Uri uri) {
        String[] projection = {MediaStore.Images.Media.DATA};
        Cursor cursor = getmContext().getContentResolver().query(uri, projection, null, null, null);
        if (cursor == null) return null;
        int column_index = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
        cursor.moveToFirst();
        String s = cursor.getString(column_index);
        cursor.close();
        return s;
    }

    public void uploadFiles(CredentialData data, Uri selectedUri, String date, DocumentFragment documentFragment) {

        RequestBody requestBody = Apiservice.getInstance().createCredentialFileRequest(data, date, selectedUri.getPath());
        postFile(requestBody, documentFragment);

    }

    private void postFile(RequestBody requestBody, DocumentFragment documentFragment) {
        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {
                    String results = Apiservice.getInstance().makePost(Constants.UPLOAD_CREDENTIALS, requestBody);
                    Logger.debug("upload files", results);

                    try {

                        JSONObject jsonObject = new JSONObject(results);
                        if (jsonObject.has("status")) {
                            getCredentialDataList().clear();
                            getCredentialDataSource().clear();
                            if (jsonObject.getString("status").equals("success")) {
                                JSONArray data = jsonObject.getJSONArray("data");
                                String path = jsonObject.getString("path");
                                for (int i = 0; i < data.length(); i++) {
                                    JSONObject dic = data.getJSONObject(i);
                                    CredentialData credentialData = gson.fromJson(dic.toString(), CredentialData.class);
                                    credentialData.setPath(path);
                                    getCredentialDataList().add(credentialData);
                                    getCredentialDataSource().add(credentialData);
                                }

                                documentFragment.onRefresh("credentials", true);
                            }
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        documentFragment.onRefresh("credentials", false);
                    }

                } catch (IOException e) {
                    e.printStackTrace();
                }

            }
        });
    }

    public void deleteCredentials(Boolean isExtra, String fileId, DocumentDetailsView activity, DocumentFragment fragment) {

        RequestBody requestBody = Apiservice.getInstance().createCredentialDeleteReq(isExtra, fileId);

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {
                    String results = Apiservice.getInstance().makePost(isExtra ? Constants.DELETE_EXTRA_CREDENTIALS : Constants.DELETE_CREDENTIALS, requestBody);
                    Logger.debug("AppHelper", "deleteCredentials " + results);
                    JSONObject jsonObject = null;
                    try {
                        jsonObject = new JSONObject(results);
                        if (jsonObject.has("status")) {
                            getCredentialDataList().clear();
                            getCredentialDataSource().clear();
                            if (jsonObject.getString("status").equals("success")) {
                                JSONArray data = jsonObject.getJSONArray("data");
                                String path = jsonObject.getString("path");
                                for (int i = 0; i < data.length(); i++) {
                                    JSONObject dic = data.getJSONObject(i);
                                    CredentialData credentialData = gson.fromJson(dic.toString(), CredentialData.class);
                                    credentialData.setPath(path);
                                    getCredentialDataList().add(credentialData);
                                    getCredentialDataSource().add(credentialData);
                                }

                                if (fragment != null) {
                                    fragment.onDeleted(true);
                                    return;
                                }
                                activity.onDeleted(true);
                            } else {
                                if (fragment != null) {
                                    fragment.onDeleted(false);
                                    return;
                                }
                                activity.onDeleted(false);
                            }
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        if (fragment != null) {
                            fragment.onDeleted(false);
                            return;
                        }
                        activity.onDeleted(false);
                    }


                } catch (IOException e) {
                    e.printStackTrace();
                    if (fragment != null) {
                        fragment.onDeleted(false);
                        return;
                    }
                    activity.onDeleted(false);
                }
            }
        });
    }


    public String getRootDirPath(Context context) {

        if (Environment.MEDIA_MOUNTED.equals(Environment.getExternalStorageState())) {
            File file = ContextCompat.getExternalFilesDirs(context.getApplicationContext(),
                    null)[0];
            return file.getAbsolutePath();
        } else {
            return context.getApplicationContext().getFilesDir().getAbsolutePath();
        }
    }

    public File getFileLoc() {

        File location = new File(Environment.getExternalStorageDirectory(), "credentails.txt");
        return location;
    }

    public File createTextFile(List<String> stringArrayList) {

        FileWriter fileWriter = null;
        try {

            File location = new File(getRootDirPath(getmContext()), "credentails.txt");

            fileWriter = new FileWriter(location);

            BufferedWriter writer = new BufferedWriter(fileWriter);

            for (String contents : stringArrayList
            ) {
                writer.write(contents + "\n");
            }


            writer.close();

            Logger.debug("AppHelper", "createTextFile " + location.getPath());

            return location;


        } catch (IOException e) {
            e.printStackTrace();
        }


        return null;

    }

    public void uploadTextFile(CredentialData credentialData, File file, String selectedExpireDate, DocumentFragment fragment) {

        RequestBody requestBody = Apiservice.getInstance().createCredentialTextFileRequest(credentialData, selectedExpireDate, file);
        postFile(requestBody, fragment);
    }


    public void creatPDF(CredentialData credentialData, PdfDocument pdfDocument, String selectedExpireDate, DocumentFragment fragment) {


        if (pdfDocument == null) {
            return;
        }
        File file = new File(getRootDirPath(getmContext()), "credentails_pdf.pdf");

        try {
            FileOutputStream fileOutputStream = new FileOutputStream(file);
            pdfDocument.writeTo(fileOutputStream);
        } catch (IOException e) {
            e.printStackTrace();
        }
        pdfDocument.close();


        RequestBody requestBody = Apiservice.getInstance().createCredentialFileRequest(credentialData, selectedExpireDate, Uri.fromFile(file).getPath());
        postFile(requestBody, fragment);

    }

    public void initDownloadconfig(Context context, int size) {

        FetchConfiguration fetchConfiguration = new FetchConfiguration.Builder(context)
                .setDownloadConcurrentLimit(size)
                .enableLogging(true)
                .build();

        fetch = Fetch.Impl.getInstance(fetchConfiguration);

        addListener();
    }

    private void addListener() {

        FetchListener fetchListener = new FetchListener() {
            @Override
            public void onWaitingNetwork(@NotNull Download download) {

            }

            @Override
            public void onStarted(@NotNull Download download, @NotNull List<? extends DownloadBlock> list, int i) {
                Logger.debug("Download", "onStarted " + download.getId());

            }

            @Override
            public void onError(@NotNull Download download, @NotNull Error error, @Nullable Throwable throwable) {
                Logger.debug("Download", "onError " + error);
                currentDownloadList += 1;
            }

            @Override
            public void onDownloadBlockUpdated(@NotNull Download download, @NotNull DownloadBlock downloadBlock, int i) {
                Logger.debug("Download", "onDownloadBlockUpdated " + download.getId());

            }

            @Override
            public void onAdded(@NotNull Download download) {
                Logger.debug("Download", "onAdded " + download.getId());

            }

            @Override
            public void onQueued(@NotNull Download download, boolean waitingOnNetwork) {
                Logger.debug("Download", "onQueued " + download.getId());

            }

            @Override
            public void onCompleted(@NotNull Download download) {
                Logger.debug("Download", "complete " + download.getId());
                currentDownloadList += 1;
                if (totaldownloaedList == currentDownloadList) {
                    getCurrentDocFrgment().onEmailAttachmentDone();
                }

                if (mHomeFragment != null) {
                    mHomeFragment.onFiledownloaded();
                }


            }

            @Override
            public void onProgress(@NotNull Download download, long etaInMilliSeconds, long downloadedBytesPerSecond) {
                Logger.debug("Download", "onProgress " + downloadedBytesPerSecond);

            }

            @Override
            public void onPaused(@NotNull Download download) {

            }

            @Override
            public void onResumed(@NotNull Download download) {

            }

            @Override
            public void onCancelled(@NotNull Download download) {

            }

            @Override
            public void onRemoved(@NotNull Download download) {

            }

            @Override
            public void onDeleted(@NotNull Download download) {

            }
        };

        fetch.addListener(fetchListener);
    }

    public Request buildRequest(String fileName) {
        Logger.debug("download url", Constants.UPLOAD_PATH.concat(fileName));
        File location = new File(getRootDirPath(getmContext()), fileName);
        final Request request = new Request(Constants.UPLOAD_PATH.concat(fileName), location.getPath());
        request.setPriority(Priority.HIGH);
        request.setNetworkType(NetworkType.ALL);
        return request;

    }

    public void downloadFiles(List<CredentialData> dataList, DocumentFragment fragment) {
        // initDownloadconfig(dataList.size());
        this.totaldownloaedList = dataList.size();
        setCurrentDocFrgment(fragment);

        for (CredentialData item : dataList) {
            Request request = buildRequest(item.getFile_name());

            File location = new File(getRootDirPath(getmContext()), item.getFile_name());
            if (!location.exists()) {
                fetch.attachFetchObserversForDownload(request.getId(), new FetchObserver<Download>() {
                    @Override
                    public void onChanged(Download download, @NotNull Reason reason) {

                    }
                }).enqueue(request, updatedRequest -> {
                    //Request was successfully enqueued for download.
                    Logger.debug("Download", "download started");
                }, error -> {
                    //An error occurred enqueuing the request.
                    Logger.debug("Download", "download error");

                });
            } else {
                Logger.debug("AppHelper", "File already downloaded " + location);
                currentDownloadList += 1;
                if (currentDownloadList == totaldownloaedList) {
                    fragment.onEmailAttachmentDone();
                    break;
                }
            }

        }

    }

    public void resetEmailAttachmentLog() {
        currentDownloadList = 0;
        totaldownloaedList = 0;

    }


    public static boolean hasRealRemovableSdCard(Context context) {
        return ContextCompat.getExternalFilesDirs(context, null).length >= 2;
    }

    public Uri getVideoLinkFromLocal(HomeFragment fragment) {
        this.mHomeFragment = fragment;
        File file;

        if (hasRealRemovableSdCard(getmContext())) {
            // yes SD-card is present

            Logger.debug("SDCARD STAUTS", "true");

            File extStore = Environment.getExternalStorageDirectory();
            file = new File(extStore, "video_link.mp4");
        } else {
            // Sorry
            Logger.debug("SDCARD STAUTS", "false");
            file = new File(getRootDirPath(getmContext()), "video_link.mp4");
        }


        if (file.exists()) {
            return Uri.fromFile(file);
        }

        final Request request = new Request(AppHelper.getInstance().getHomeData().getLink(), file.getPath());
        request.setPriority(Priority.HIGH);
        request.setNetworkType(NetworkType.ALL);

        fetch.attachFetchObserversForDownload(request.getId(), new FetchObserver<Download>() {
            @Override
            public void onChanged(Download download, @NotNull Reason reason) {

            }
        }).enqueue(request, updatedRequest -> {
            //Request was successfully enqueued for download.
            Logger.debug("Download", "download started");
        }, error -> {
            //An error occurred enqueuing the request.
            Logger.debug("Download", "download error");

        });


        return null;
    }


    public void deleteAccount(ProfileFragment fragment) {

        RequestBody body = Apiservice.getInstance().createDeleteAccount(getUser().getId());

        try {
            String results =  Apiservice.getInstance().makePost(Constants.DELETE_ACCOUNT,body);
            if(results != null){
                JSONObject jsonObject = new JSONObject(results.toString());
                if (jsonObject.has("status") && jsonObject.getString("status").equals("success")) {
                    fragment.onAccountDeleted(true);
                    return;
                }
            }
            fragment.onAccountDeleted(false);
        } catch (IOException e) {
            fragment.onAccountDeleted(false);
            e.printStackTrace();
        }catch (JSONException e) {
            fragment.onAccountDeleted(false);
            e.printStackTrace();
        }
    }
}
