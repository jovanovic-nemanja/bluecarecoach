package com.app.bdo.activity.auth;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;

import androidx.annotation.NonNull;
import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.databinding.DataBindingUtil;

import com.app.bdo.R;
import com.app.bdo.activity.MainActivity;
import com.app.bdo.databinding.ActivityRegisterBinding;
import com.app.bdo.helper.Constants;
import com.app.bdo.model.License;
import com.app.bdo.model.User;
import com.app.bdo.services.Apiservice;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.Logger;
import com.app.bdo.utils.SharePrefUtils;
import com.app.bdo.utils.ToastUtils;
import com.google.gson.Gson;
import com.jaredrummler.materialspinner.MaterialSpinner;
import com.opensooq.supernova.gligar.GligarPicker;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;

import okhttp3.RequestBody;

public class Register extends AppCompatActivity {

    private String TAG = Register.class.getName();

    private int REQUEST_CODE_CHOOSE = 300;

    private int REQUEST_PERMISSION = 100;

    private ActivityRegisterBinding registerBinding;

    private String email;

    private String licenseCode;

    private String selectedImage;

    private User user;

    private Gson gson = new Gson();

    private ArrayList<License> licenseArrayList = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        registerBinding = DataBindingUtil.setContentView(this, R.layout.activity_register);

        /* Configure Naviagtion BAR */

        ActionBar actionBar = getSupportActionBar();
        actionBar.setTitle(getString(R.string.personal_data));

        /* Parse Email */

        email = getIntent().getStringExtra("email");

        /* Get List of license */

        getLicenseData();

        /* Edit Text Listener */

        addTextWatch();

        /* user photo onclick events */

        registerBinding.addUserPhoto.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                askForPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE, REQUEST_PERMISSION);
            }
        });

    }

    /* Back button events */

    @Override
    public void onBackPressed() {
    }

    /* Menu Resources */

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.register_menu, menu);
        return true;
    }

    /* Menu item onclick event */

    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {

        if (item.getItemId() == R.id.save) {

            registerUser();
        }
        return super.onOptionsItemSelected(item);

    }


    /* Permission Request */

    private void askForPermission(String permission, Integer requestCode) {
        if (ContextCompat.checkSelfPermission(Register.this, permission) != PackageManager.PERMISSION_GRANTED) {

            // Should we show an explanation?
            if (ActivityCompat.shouldShowRequestPermissionRationale(Register.this, permission)) {

                //This is called if user has denied the permission before
                //In this case I am just asking the permission again
                ActivityCompat.requestPermissions(Register.this, new String[]{permission}, requestCode);

            } else {

                ActivityCompat.requestPermissions(Register.this, new String[]{permission}, requestCode);
            }
        } else {
            requestPhoto();
        }
    }

    /* Open Image Picker */

    private void requestPhoto() {

        new GligarPicker().limit(1).requestCode(REQUEST_CODE_CHOOSE).withActivity(this).show();
    }


    /* Activity Callbacks */

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == REQUEST_PERMISSION && resultCode == RESULT_OK) {

            /* Open Image Picker */

            requestPhoto();
        }
        if (requestCode == REQUEST_CODE_CHOOSE && resultCode == RESULT_OK) {

            String pathsList[] = data.getExtras().getStringArray(GligarPicker.IMAGES_RESULT);

            selectedImage = pathsList[0];

            registerBinding.addUserPhoto.setImageURI(Uri.parse(pathsList[0]));
        }
    }


    /* Edit text Watcher */

    private void addTextWatch() {

        registerBinding.phnenumberEdit.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void afterTextChanged(Editable editable) {

                if (!editable.toString().startsWith("+1")) {

                    registerBinding.phnenumberEdit.setText("+1");
                }


            }
        });
    }

    /* Api Method to Get Lsit */

    private void getLicenseData() {

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {

                    String response = Apiservice.getInstance().makeGet(Constants.GET_LICENSES);

                    Logger.debug(TAG, "getLicenseData " + response);

                    parseLicense(response);

                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        });

    }

    /* Parse Data */

    private void parseLicense(String response) {

        try {
            JSONObject jsonObject = new JSONObject(response);

            if (jsonObject.has("status")) {

                String status = jsonObject.getString("status");

                if (status.equals("success")) {

                    JSONArray jsonArray = jsonObject.getJSONArray("data");

                    for (int i = 0; i < jsonArray.length(); i++) {

                        JSONObject item = jsonArray.getJSONObject(i);

                        License license = gson.fromJson(item.toString(), License.class);

                        licenseArrayList.add(license);
                    }
                }

                initSpinner();
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    /* Setup Dropdown menu */

    private void initSpinner() {

        registerBinding.licenseEdit.setItems(licenseArrayList);

        if (licenseArrayList.size() > 0) {

            licenseCode = String.valueOf(licenseArrayList.get(0).getId());
        }
        registerBinding.licenseEdit.setOnItemSelectedListener(new MaterialSpinner.OnItemSelectedListener() {
            @Override
            public void onItemSelected(MaterialSpinner view, int position, long id, Object item) {

                licenseCode = String.valueOf(licenseArrayList.get(position).getId());
                Logger.debug(TAG, "onItemSelected " + licenseCode);
            }
        });

    }


    /* Create User */

    private void registerUser() {

        if (validate()) {
            createUser();
        }
    }

    /* connect user to server */

    private void createUser() {

        Loader.showLoader(this);

        user = new User();

        user.setFirstname(registerBinding.firstnameEdit.getText().toString());

        user.setLastname(registerBinding.lastNameEdit.getText().toString());

        user.setEmail(email);

        user.setCare_giving_license(licenseCode);

        user.setZip_code(registerBinding.zipcodeEdit.getText().toString());

        user.setOver_18(registerBinding.ageSwitch.isSelected() ? "1" : "0");

        user.setPhone_number(registerBinding.phnenumberEdit.getText().toString());

        user.setProfile_logo(selectedImage);

        RequestBody body = Apiservice.getInstance().createRegisterRequest(user, registerBinding.passwordEdit.getText().toString());

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {

                    String response = Apiservice.getInstance().makePost(Constants.REGISTER_USER, body);
                    validateResults(response);

                } catch (IOException e) {
                    e.printStackTrace();

                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {

                            Loader.hide();
                            Loader.showAlert(Register.this, "", getString(R.string.error));
                        }
                    });
                }
            }
        });
    }

    /* Validate user Responses */

    private void validateResults(String response) {

        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                try {
                    Loader.hide();

                    JSONObject jsonObject = new JSONObject(response);

                    if (jsonObject.has("status")) {

                        String message = jsonObject.getString("msg");

                        if (jsonObject.getString("status").equals("success")) {

                            JSONObject data = jsonObject.getJSONObject("data");

                            int id = data.getInt("id");

                            user.setId(id);

                            saveUserSession();

                        } else {

                            Loader.showAlert(Register.this, "", message);

                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        });
    }

    /* Save user in local */

    private void saveUserSession() {

        SharePrefUtils.saveData(this, SharePrefUtils.SESSION_KEY, true);

        String results = gson.toJson(user);

        SharePrefUtils.saveData(this, SharePrefUtils.USER_DETAILS, results);

        Intent home = new Intent(this, MainActivity.class);

        home.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);

        startActivity(home);

        finish();

    }

    /* Validation */

    private Boolean validate() {

        if (TextUtils.isEmpty(registerBinding.firstnameEdit.getText().toString())) {

            registerBinding.firstnameEdit.setError(getString(R.string.firstname_required));
            return false;

        }
        if (TextUtils.isEmpty(registerBinding.lastNameEdit.getText().toString())) {

            registerBinding.lastNameEdit.setError(getString(R.string.lastname_required));
            return false;

        }
        if (TextUtils.isEmpty(registerBinding.phnenumberEdit.getText().toString())) {

            registerBinding.phnenumberEdit.setError(getString(R.string.phone_number_required));
            return false;

        }
        if (TextUtils.isEmpty(registerBinding.passwordEdit.getText().toString())) {

            registerBinding.passwordEdit.setError(getString(R.string.password_required));
            return false;

        }
        if (TextUtils.isEmpty(registerBinding.confirmPaswdEdit.getText().toString())) {

            registerBinding.confirmPaswdEdit.setError(getString(R.string.conf_password_required));
            return false;

        }

        if (!registerBinding.confirmPaswdEdit.getText().toString().equals(registerBinding.passwordEdit.getText().toString())) {

            ToastUtils.show(this, getString(R.string.password_not_match));
            return false;
        }

        if (TextUtils.isEmpty(selectedImage)) {

            ToastUtils.show(this, getString(R.string.profile_image_error));
            return false;
        }
        if (TextUtils.isEmpty(licenseCode)) {

            ToastUtils.show(this, getString(R.string.license_required));
            return false;
        }

        return true;
    }

}