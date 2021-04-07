package com.app.bdo.fragments.profile;

import android.Manifest;
import android.app.Activity;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CompoundButton;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.databinding.DataBindingUtil;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;

import com.app.bdo.R;
import com.app.bdo.activity.MainActivity;
import com.app.bdo.databinding.FragmentProfileBinding;
import com.app.bdo.fragments.profile.JobItem;
import com.app.bdo.fragments.profile.JobItemAdapter;
import com.app.bdo.helper.AppHelper;
import com.app.bdo.helper.Constants;
import com.app.bdo.model.License;
import com.app.bdo.model.User;
import com.app.bdo.services.Apiservice;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.Logger;
import com.app.bdo.utils.SharePrefUtils;
import com.app.bdo.utils.ToastUtils;
import com.bumptech.glide.Glide;
import com.google.gson.Gson;
import com.jaredrummler.materialspinner.MaterialSpinner;
import com.opensooq.supernova.gligar.GligarPicker;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import okhttp3.RequestBody;

public class ProfileFragment extends Fragment {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String TAG = "ProfileFragment";

    private int REQUEST_CODE_CHOOSE = 300;

    private int REQUEST_PERMISSION = 100;

    private FragmentProfileBinding profileBinding;

    private Gson gson = new Gson();

    private String licenseCode;

    private String selectedImage;

    private ArrayList<License> licenseArrayList = new ArrayList<>();

    private JobItemAdapter jobItemAdapter;

    private int skillsCount = 0;

    private Boolean isImageUpdated = false;

    User user = AppHelper.getInstance().getUser();

    public ProfileFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        profileBinding = DataBindingUtil.inflate(inflater, R.layout.fragment_profile, container, false);

        return profileBinding.getRoot();
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        addButtonActions();

        initAdapter();

    }

//    Button actions

    private void addButtonActions() {

        profileBinding.myTrainingLayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(Constants.WEB_URL));
                startActivity(browserIntent);
            }
        });

        profileBinding.logoutBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                ((MainActivity) getActivity()).askLogout();
            }
        });

        profileBinding.saveBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                updateUser();
            }
        });

        profileBinding.personalText.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                profileBinding.personalView.setVisibility(View.VISIBLE);
                profileBinding.jobTrainingLayout.setVisibility(View.GONE);

                profileBinding.jobTraining.setBackground(null);
                profileBinding.personalText.setBackground(getResources().getDrawable(R.drawable.cornor_textview));

            }
        });

        profileBinding.jobTraining.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                profileBinding.personalView.setVisibility(View.GONE);
                profileBinding.jobTrainingLayout.setVisibility(View.VISIBLE);

                profileBinding.personalText.setBackground(null);
                profileBinding.jobTraining.setBackground(getResources().getDrawable(R.drawable.cornor_textview));
            }
        });

        profileBinding.jobTrainingSwitch.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean b) {

                showJobDetails(b);

            }
        });

        profileBinding.photolayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                askForPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE, REQUEST_PERMISSION);
            }
        });

        profileBinding.addskill.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (skillsCount == 5) {
                    return;
                }

                findTotalSkills();

                Logger.debug("skillsCount ", String.valueOf(skillsCount));

                JobItem item = new JobItem();
                item.setTitle("Skills".concat(String.valueOf(skillsCount)));
                item.setValue("");
                item.setPosition(skillsCount);
                item.setType("skills");
                showEditOption(-1, item);
            }
        });
    }

//    init adapter

    private void initAdapter() {

        jobItemAdapter = new JobItemAdapter(new JobItemAdapter.JobItemClickedListener() {
            @Override
            public void onItemClicked(JobItem data, int pos) {

                showEditOption(pos, data);
            }
        });

        profileBinding.listview.setLayoutManager(new LinearLayoutManager(getActivity()));

        profileBinding.listview.setAdapter(jobItemAdapter);
    }

//    Edit profile view

    private void showEditOption(int postion, JobItem data) {

        if (data.getType().equals("job_pay")) {
            Loader.withDesirePayEdit(data, getContext(), this);
            return;
        }

        Loader.withProfileEditText(data, getContext(), new Loader.EditextListener() {
            @Override
            public void onSubmitted(String text) {

                if (data.getType().equals("skills")) {

                    Boolean isExists = checkSkills(data);
                    if (postion > 0) {
                        data.setValue(text);
                        jobItemAdapter.updatetem(data);

                        switch (data.getPosition()) {
                            case 4:
                                user.setSkill1(text);
                                break;
                            case 5:
                                user.setSkill2(text);
                                break;
                            case 6:
                                user.setSkill3(text);
                                break;
                            case 7:
                                user.setSkill4(text);
                                break;
                            case 8:
                                user.setSkill5(text);
                                break;

                        }
                    } else {

                        skillsCount += 1;

                        data.setValue(text);

                        switch (skillsCount) {
                            case 1:

                                user.setSkill1(text);
                                data.setTitle("Skills1");
                                jobItemAdapter.addItem(data);
                                break;

                            case 2:

                                user.setSkill2(text);
                                data.setTitle("Skills2");
                                jobItemAdapter.addItem(data);
                                break;

                            case 3:

                                user.setSkill3(text);
                                data.setTitle("Skills3");
                                jobItemAdapter.addItem(data);
                                break;

                            case 4:

                                user.setSkill4(text);
                                data.setTitle("Skills4");
                                jobItemAdapter.addItem(data);
                                break;

                            case 5:

                                user.setSkill5(text);
                                data.setTitle("Skills5");
                                jobItemAdapter.addItem(data);
                                break;

                        }
                    }


                } else {

                    data.setValue(text);

                    jobItemAdapter.updatetem(data);

                    switch (data.getType()) {

                        case "job_zip":

                            user.setLooking_job_zipcode(data.getValue());
                            break;

                        case "job_exp":

                            user.setCare_giving_experience(data.getValue());
                            break;

                        case "job_shift":

                            user.setPreferred_shift(data.getValue());
                            break;

                        case "job_pay":
                            break;
                    }

                }
            }
        });
    }

//    Check whether skills exists

    private Boolean checkSkills(JobItem data) {

        int pos = jobItemAdapter.getList().indexOf(data);
        Log.d("pos ", String.valueOf(pos));
        return pos < 0 ? false : true;
    }


    // update profile to server
    private void updateUser() {
        if (validate()) {
            saveValues();
        } else {
            ToastUtils.show(getContext(), "Please check required fields");
        }
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
            requestPhoto();
        }
    }

    // open image picker
    private void requestPhoto() {
        new GligarPicker().limit(1).requestCode(REQUEST_CODE_CHOOSE).withFragment(this).show();

    }


    //    Activity callbacks
    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == REQUEST_PERMISSION && resultCode == Activity.RESULT_OK) {

            requestPhoto();
        }

        if (requestCode == REQUEST_CODE_CHOOSE && resultCode == Activity.RESULT_OK) {

            String pathsList[] = data.getExtras().getStringArray(GligarPicker.IMAGES_RESULT);
            selectedImage = pathsList[0];
            Logger.debug("Matisse", "selectedImage: " + selectedImage);

            Uri uri = Uri.parse(selectedImage);

            profileBinding.addUserPhoto.setVisibility(View.VISIBLE);
            profileBinding.PreviewUserPhoto.setVisibility(View.GONE);

            profileBinding.addUserPhoto.setImageURI(uri);
            isImageUpdated = true;

        }
    }


    //    show job information
    private void showJobDetails(boolean b) {

        user.setLooking_job(b ? "1" : "0");

        if (b) {
            profileBinding.jobTrainingDetails.setVisibility(View.VISIBLE);
        } else {
            profileBinding.jobTrainingDetails.setVisibility(View.GONE);

        }
    }


    // Profile Save method
    private void saveValues() {

        Loader.showLoader(getContext());

        user.setFirstname(profileBinding.firstnameEdit.getText().toString());

        user.setLastname(profileBinding.lastNameEdit.getText().toString());

        user.setCare_giving_license(licenseCode);

        user.setZip_code(profileBinding.zipcodeEdit.getText().toString());

        user.setOver_18(profileBinding.ageSwitch.isSelected() ? "1" : "0");

        user.setPhone_number(profileBinding.phnenumberEdit.getText().toString());

        user.setProfiletagline(user.getProfiletagline());

        if (isImageUpdated) {
            user.setProfile_logo(selectedImage);
        }

        RequestBody body = Apiservice.getInstance().createUserUpdateRequest(user, isImageUpdated);

        AsyncTask.execute(new Runnable() {
            @Override
            public void run() {
                try {

                    String response = Apiservice.getInstance().makePost(Constants.UPDATE_PROFILE, body);

                    isImageUpdated = false;

                    validateResults(response);
                } catch (IOException e) {
                    e.printStackTrace();

                    getActivity().runOnUiThread(new Runnable() {
                        @Override
                        public void run() {

                            Loader.hide();
                            Loader.showAlert(getActivity(), "", getString(R.string.error));
                        }
                    });
                }
            }
        });
    }

    // Validate user results
    private void validateResults(String response) {

        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                try {
                    Loader.hide();

                    JSONObject jsonObject = new JSONObject(response);

                    if (jsonObject.has("status")) {

                        String message = jsonObject.getString("msg");

                        if (jsonObject.getString("status").equals("success")) {

                            Logger.debug("jsonObject ", String.valueOf(jsonObject));
                            saveUserSession(jsonObject);

                        } else {

                            Loader.showAlert(getActivity(), "", message);

                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        });
    }

    //    Save in local storage
    private void saveUserSession(JSONObject jsonObject) {

        try {
            JSONObject userData = jsonObject.getJSONObject("data");

            Gson gson = new Gson();

            Logger.debug("userData.toString() ", userData.toString());

            User user = gson.fromJson(userData.toString(), User.class);

            user.setProfiletagline(AppHelper.getInstance().getUser().getProfiletagline());


            Logger.debug("user ", String.valueOf(user.getId()));

            String results = gson.toJson(user);

            SharePrefUtils.saveData(getActivity(), SharePrefUtils.USER_DETAILS, results);

            bindProfileValues();

        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    //    Validation
    private Boolean validate() {

        if (TextUtils.isEmpty(profileBinding.firstnameEdit.getText().toString())) {
            profileBinding.firstnameEdit.setError(getString(R.string.firstname_required));
            return false;

        }
        if (TextUtils.isEmpty(profileBinding.lastNameEdit.getText().toString())) {
            profileBinding.lastNameEdit.setError(getString(R.string.lastname_required));
            return false;

        }
        if (TextUtils.isEmpty(profileBinding.phnenumberEdit.getText().toString())) {
            profileBinding.phnenumberEdit.setError(getString(R.string.phone_number_required));
            return false;

        }

        if (TextUtils.isEmpty(licenseCode)) {
            ToastUtils.show(getActivity(), getString(R.string.license_required));
            return false;
        }

        return true;
    }

    //    find skills counts
    public int findTotalSkills() {

        skillsCount = 0;

        if (!TextUtils.isEmpty(user.getSkill1())) {

            skillsCount = 1;
        }

        if (!TextUtils.isEmpty(user.getSkill2())) {

            skillsCount = 2;
        }

        if (!TextUtils.isEmpty(user.getSkill3())) {

            skillsCount = 3;
        }

        if (!TextUtils.isEmpty(user.getSkill4())) {
            skillsCount = 4;
        }

        if (!TextUtils.isEmpty(user.getSkill5())) {
            skillsCount = 5;
        }

        return skillsCount;
    }

    //    update view and create skills set
    public void bindProfileValues() {

        Logger.debug(TAG, "bindProfileValues ");

        User user = AppHelper.getInstance().getUser();

        String appName = getString(R.string.app_name);
        if (!TextUtils.isEmpty(user.getProfiletagline())) {

            profileBinding.appname.setText(appName.concat("\n").concat(user.getProfiletagline()));

        } else {
            profileBinding.appname.setText(appName);

        }

        profileBinding.firstnameEdit.setText(user.getFirstname());

        profileBinding.lastNameEdit.setText(user.getLastname());

        profileBinding.ageSwitch.setChecked(user.getOver_18().equals("1") ? true : false);

        profileBinding.phnenumberEdit.setText(user.getPhone_number());

        profileBinding.zipcodeEdit.setText(user.getZip_code());

        String imageURL = Constants.UPLOAD_PATH + user.getProfile_logo();

        Glide.with(getActivity()).load(imageURL).placeholder(R.drawable.add_photo_paceholder).error(R.drawable.add_photo_paceholder)
                .into(profileBinding.PreviewUserPhoto);

        profileBinding.addUserPhoto.setVisibility(View.GONE);

        profileBinding.PreviewUserPhoto.setVisibility(View.VISIBLE);

        getLicenseData();

        if (user.getLooking_job().equals("1")) {

            profileBinding.jobTrainingSwitch.setChecked(true);
            showJobDetails(true);

        } else {

            profileBinding.jobTrainingSwitch.setChecked(false);
            showJobDetails(false);
        }

        if (jobItemAdapter != null) {

            List<JobItem> itemList = new ArrayList<>();

            JobItem jobZipItem = new JobItem();
            jobZipItem.setPosition(0);
            jobZipItem.setTitle(getString(R.string.looking_job_zipcode));
            jobZipItem.setType("job_zip");
            jobZipItem.setValue(user.getLooking_job_zipcode());

            itemList.add(jobZipItem);

            JobItem jobExpItem = new JobItem();
            jobExpItem.setTitle(getString(R.string.care_giving_experience));
            jobExpItem.setType("job_exp");
            jobExpItem.setPosition(1);
            jobExpItem.setValue(user.getCare_giving_experience());

            itemList.add(jobExpItem);

            JobItem jobshiftItem = new JobItem();
            jobshiftItem.setTitle(getString(R.string.preferred_shift));
            jobshiftItem.setType("job_shift");
            jobshiftItem.setPosition(2);
            jobshiftItem.setValue(user.getPreferred_shift());

            itemList.add(jobshiftItem);


            JobItem jobpayItem = new JobItem();
            jobpayItem.setTitle(getString(R.string.desired_pay_to));
            jobpayItem.setType("job_pay");
            jobpayItem.setPosition(3);
            jobpayItem.setValue(user.jobPayVal());

            itemList.add(jobpayItem);
            skillsCount = 0;
            if (!TextUtils.isEmpty(user.getSkill1())) {
                JobItem skill1 = new JobItem();
                skill1.setPosition(4);
                skill1.setTitle("Skills1");
                skill1.setType("skills");
                skill1.setValue(user.getSkill1());

                itemList.add(skill1);
                skillsCount = 1;
            }


            if (!TextUtils.isEmpty(user.getSkill2())) {
                JobItem skill12 = new JobItem();
                skill12.setPosition(5);
                skill12.setTitle("Skills2");
                skill12.setType("skills");
                skill12.setValue(user.getSkill2());

                itemList.add(skill12);
                skillsCount = 2;
            }

            if (!TextUtils.isEmpty(user.getSkill3())) {
                JobItem skill3 = new JobItem();
                skill3.setPosition(6);
                skill3.setTitle("Skills3");
                skill3.setType("skills");
                skill3.setValue(user.getSkill3());

                itemList.add(skill3);
                skillsCount = 3;
            }

            if (!TextUtils.isEmpty(user.getSkill4())) {
                JobItem skill4 = new JobItem();
                skill4.setPosition(7);
                skill4.setTitle("Skills4");
                skill4.setType("skills");
                skill4.setValue(user.getSkill4());
                itemList.add(skill4);
                skillsCount = 4;
            }

            if (!TextUtils.isEmpty(user.getSkill5())) {
                JobItem skill5 = new JobItem();
                skill5.setPosition(8);
                skill5.setTitle("Skills5");
                skill5.setType("skills");
                skill5.setValue(user.getSkill5());

                itemList.add(skill5);
                skillsCount = 5;

            }

            jobItemAdapter.setData(itemList);

        }
    }

    //    Api to get list of licenselist
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

    //    parse data
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

    //    setup dropdown
    private void initSpinner() {

        profileBinding.licenseEdit.setItems(licenseArrayList);

        if (licenseArrayList.size() > 0) {

            Logger.debug(TAG, "license" + user.getCare_giving_license());

            if (!TextUtils.isEmpty(user.getCare_giving_license())) {

                int index = 0;
                for (License data : licenseArrayList) {

                    if (String.valueOf(data.getId()).equals(user.getCare_giving_license())) {

                        licenseCode = String.valueOf(data.getId());

                        profileBinding.licenseEdit.setSelectedIndex(index);
                        break;
                    }

                    index += 1;
                }
            } else {
                licenseCode = String.valueOf(licenseArrayList.get(0).getId());

            }
        }
        profileBinding.licenseEdit.setOnItemSelectedListener(new MaterialSpinner.OnItemSelectedListener() {
            @Override
            public void onItemSelected(MaterialSpinner view, int position, long id, Object item) {
                licenseCode = String.valueOf(licenseArrayList.get(position).getId());
                Logger.debug(TAG, "onItemSelected " + licenseCode);
            }
        });

    }

    public void onDesirepaysubmitted(JobItem data, String toString, String toString1) {

        user.setDesired_pay_from(toString);

        user.setDesired_pay_to(toString1);

        data.setValue(user.getDesired_pay_from().concat("-").concat(user.getDesired_pay_to()));

        jobItemAdapter.updatetem(data);


    }
}