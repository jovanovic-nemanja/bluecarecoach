package com.app.bdo.activity;

import android.Manifest;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentStatePagerAdapter;
import androidx.viewpager.widget.ViewPager;

import com.app.bdo.R;
import com.app.bdo.activity.auth.LoginActivity;
import com.app.bdo.fragments.profile.ProfileFragment;
import com.app.bdo.fragments.document.DocumentFragment;
import com.app.bdo.fragments.home.HomeFragment;
import com.app.bdo.helper.AppHelper;
import com.app.bdo.model.User;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.ToastUtils;
import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.gson.Gson;
import com.gun0912.tedpermission.PermissionListener;
import com.gun0912.tedpermission.TedPermission;

import java.util.ArrayList;
import java.util.List;

import static androidx.fragment.app.FragmentStatePagerAdapter.BEHAVIOR_RESUME_ONLY_CURRENT_FRAGMENT;

public class MainActivity extends AppCompatActivity {

    private String[] titles = new String[]{"Home", "Documents", "Profile"};

    private User user;

    private Gson gson = new Gson();

    private List<Fragment> mFragmentList = new ArrayList<>();

    private HomeFragment homeFragment;

    private DocumentFragment documentFragment;

    private ProfileFragment profileFragment;

    private ViewPager viewPager;

    private BottomNavigationView navigationView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Fragment init
        homeFragment = new HomeFragment();

        documentFragment = new DocumentFragment();

        profileFragment = new ProfileFragment();

//       Load Fragment List
        mFragmentList.add(homeFragment);
        mFragmentList.add(documentFragment);
        mFragmentList.add(profileFragment);

//       Hide Navigation bar
        getSupportActionBar().hide();

//       set Current instance
        AppHelper.getInstance().setmContext(this);

//       start fragments
        setupFragments();

//       Permission setup
        checkPermission();

//        AppHelper.getInstance().initDownloadconfig(this,100);

    }

    // Configure Fragments
    private void setupFragments() {

        viewPager = findViewById(R.id.view_pager);

        FragmentStatePagerAdapter adapter = new FragmentStatePagerAdapter(getSupportFragmentManager(),BEHAVIOR_RESUME_ONLY_CURRENT_FRAGMENT) {
            @Override
            public Fragment getItem(int position) {
                return mFragmentList.get(position);
            }

            @Override
            public int getCount() {
                return mFragmentList.size();
            }
        };

        viewPager.setAdapter(adapter);

        navigationView = findViewById(R.id.bottomBar);

        navigationView.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {

                switch (item.getItemId()){
                    case R.id.page_1:

                        AppHelper.getInstance().SELECTED_FRAGMENT = 0;
                        viewPager.setCurrentItem(0);
                        break;
                    case R.id.page_2:

                        AppHelper.getInstance().SELECTED_FRAGMENT = 1;
                        viewPager.setCurrentItem(1);
                        break;
                    case R.id.page_3:

                        AppHelper.getInstance().SELECTED_FRAGMENT = 2;
                        viewPager.setCurrentItem(2);

                        break;
                }
                return true;
            }
        });

        viewPager.addOnPageChangeListener(new ViewPager.OnPageChangeListener() {
            @Override
            public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {

            }

            @Override
            public void onPageSelected(int position) {

                switch (position){
                    case 0:

                       MenuItem item =  navigationView.getMenu().getItem(0);
                       item.setChecked(true);;
                       homeFragment.restartVideo();
                       break;

                    case 1:

                        MenuItem item2 =  navigationView.getMenu().getItem(1);
                        item2.setChecked(true);
                        homeFragment.stopVideo();
                        break;

                    case 2:

                        MenuItem item3 =  navigationView.getMenu().getItem(1);
                        item3.setChecked(true);
                        homeFragment.stopVideo();
                        profileFragment.bindProfileValues();

                        break;
                }
            }

            @Override
            public void onPageScrollStateChanged(int state) {

            }
        });
    }

    // Menu prepare
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.main_menu, menu);
        return true;
    }

    // Menu item onclick

    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {

        if (item.getItemId() == R.id.logout) {
            askLogout();
        }
        return super.onOptionsItemSelected(item);

    }

    // onBack button event
    @Override
    public void onBackPressed() {

//      Asking permission to close application
        Loader.showExitAlert(this, getString(R.string.ask_to_close_App));

    }

//     Logout App
    public void askLogout() {

//        Logout permission

        Loader.showAlert(this, getString(R.string.logout_perm), new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

                AppHelper.getInstance().clearData(MainActivity.this);

                Intent auth = new Intent(MainActivity.this, LoginActivity.class);

                startActivity(auth);

                finish();
            }
        }, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        });
    }

//    onDestory activity
    @Override
    protected void onDestroy() {
        super.onDestroy();

        if(homeFragment != null){

            homeFragment.stopVideo();
        }
    }

//    Switch Fragment view
    public void onChangeFragment() {

        if(viewPager!=null){

            navigationView.setSelectedItemId(R.id.page_2);
            viewPager.setCurrentItem(1,true);
        }
    }


//   Request Permission
    public void checkPermission(){

        PermissionListener permissionlistener = new PermissionListener() {
            @Override
            public void onPermissionGranted() {

                if(AppHelper.getInstance().getHomeData() == null){
                    AppHelper.getInstance().loadVideoLink(homeFragment);

                }

            }

            @Override
            public void onPermissionDenied(List<String> deniedPermissions) {
                ToastUtils.show(MainActivity.this,getString(R.string.scan_permission));
            }

        };

        TedPermission.with(this)
                .setPermissionListener(permissionlistener)
                .setDeniedMessage("If you reject permission,you can not use this service\n\nPlease turn on permissions at [Setting] > [Permission]")
                .setPermissions(Manifest.permission.CAMERA, Manifest.permission.WRITE_EXTERNAL_STORAGE,Manifest.permission.READ_EXTERNAL_STORAGE,Manifest.permission.RECORD_AUDIO)
                .check();

    }
}