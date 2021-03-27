package com.app.bdo.activity;

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
import com.app.bdo.fragments.HomeFragment;
import com.app.bdo.helper.AppHelper;
import com.app.bdo.model.User;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.Logger;
import com.app.bdo.utils.SharePrefUtils;
import com.app.bdo.utils.TabFlashyAnimator;
import com.google.android.material.tabs.TabLayout;
import com.google.gson.Gson;

import java.util.ArrayList;
import java.util.List;

public class MainActivity extends AppCompatActivity {

    private User user;
    private Gson gson = new Gson();
    private List<Fragment> mFragmentList = new ArrayList<>();
    private String[] titles = new String[]{"Home", "Documents", "Profile"};

    private TabFlashyAnimator tabFlashyAnimator;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        String userString = SharePrefUtils.getStringData(this, SharePrefUtils.USER_DETAILS);
        user = gson.fromJson(userString, User.class);
        Logger.debug("Main", (user.getProfile_logo()) + " name => " + user.getFirstname());

        setupFragments();
    }

    private void setupFragments() {

        mFragmentList.add(new HomeFragment());
        mFragmentList.add(new HomeFragment());
        mFragmentList.add(new HomeFragment());
        ViewPager viewPager = findViewById(R.id.view_pager);
        FragmentStatePagerAdapter adapter = new FragmentStatePagerAdapter(getSupportFragmentManager()) {
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

        TabLayout tabLayout = findViewById(R.id.tabLayout);
        tabLayout.setupWithViewPager(viewPager);

        tabFlashyAnimator = new TabFlashyAnimator(tabLayout);
        tabFlashyAnimator.addTabItem(titles[0], R.drawable.home_icon);
        tabFlashyAnimator.addTabItem(titles[1], R.drawable.home_icon);
        tabFlashyAnimator.addTabItem(titles[2], R.drawable.home_icon);

        tabFlashyAnimator.highLightTab(0);
        viewPager.addOnPageChangeListener(tabFlashyAnimator);

    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.main_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {

        if (item.getItemId() == R.id.logout) {
            askLogout();
        }
        return super.onOptionsItemSelected(item);

    }

    @Override
    public void onBackPressed() {
        Loader.showExitAlert(this, getString(R.string.ask_to_close_App));

    }

    private void askLogout() {

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
}