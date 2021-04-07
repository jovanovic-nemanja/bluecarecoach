package com.app.bdo.fragments.home;

import android.media.MediaPlayer;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.databinding.DataBindingUtil;
import androidx.fragment.app.Fragment;

import com.app.bdo.R;
import com.app.bdo.activity.MainActivity;
import com.app.bdo.databinding.FragmentHomeBinding;
import com.app.bdo.helper.AppHelper;
import com.app.bdo.utils.Loader;
import com.app.bdo.utils.Logger;

public class HomeFragment extends Fragment {

    private Boolean videoUrlSet = false;

    private FragmentHomeBinding homeBinding;

    private MediaPlayer player;

    public HomeFragment() {
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

        homeBinding = DataBindingUtil.inflate(inflater, R.layout.fragment_home, container, false);

        return homeBinding.getRoot();
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        homeBinding.videoview.setOnPreparedListener(new MediaPlayer.OnPreparedListener() {
            @Override
            public void onPrepared(MediaPlayer mp) {
                Logger.debug("HomeFrag", "video onPrepared");

                player = mp;

                if (AppHelper.getInstance().SELECTED_FRAGMENT == 0) {

                    mp.setLooping(true);
                    mp.start();

                } else {

                    AppHelper.getInstance().lastPlayedDuration = mp.getCurrentPosition();
                    mp.stop();
                }

            }
        });

        homeBinding.videoview.setOnCompletionListener(new MediaPlayer.OnCompletionListener() {
            @Override
            public void onCompletion(MediaPlayer mediaPlayer) {

                AppHelper.getInstance().lastPlayedDuration = 0;

            }
        });

        homeBinding.filesContent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                ((MainActivity) getActivity()).onChangeFragment();
            }
        });

    }

    //   Refresh Video Player
    public void onRefresh() {

        if (getActivity() == null) {
            return;
        }

        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                HomeData homeData = AppHelper.getInstance().getHomeData();
                Loader.hide();
                if (homeData == null) {
                    return;
                }
                if (TextUtils.isEmpty(homeData.getLink())) {
                    Logger.debug("HomeFrag", "video link is empty");
                    return;
                }

                restartVideo();

                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {

                        homeBinding.uploadCredentialsCount.setText(String.valueOf(homeData.getAll_uploaded_credentials_count()));

                        homeBinding.expireCredentialsCount.setText(String.valueOf(homeData.getExpired_credentials_count()));

                        homeBinding.extraCredentialsCount.setText(String.valueOf(homeData.getExtra_credentials_count()));

                        if (!TextUtils.isEmpty(homeData.getTagline())) {

                            homeBinding.tagLine.setText(homeData.getTagline());

                            homeBinding.tagLine.bringToFront();
                        }
                    }
                });

            }
        });

    }

    //    Stop Video Player
    public void stopVideo() {

        try {
            if (homeBinding != null && homeBinding.videoview != null) {

                if (homeBinding.videoview.isPlaying()) {

                    AppHelper.getInstance().lastPlayedDuration = homeBinding.videoview.getCurrentPosition();

                    homeBinding.videoview.pause();

                }

            }

        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    //    Restart VideoPlayer
    public void restartVideo() {
        try {

            if (homeBinding.videoview != null) {

                String videoRemoteUrl = AppHelper.getInstance().getHomeData().getLink();

                if (AppHelper.getInstance().SELECTED_FRAGMENT == 0) {

                    homeBinding.videoview.setVideoPath(videoRemoteUrl);

                    homeBinding.videoview.seekTo(AppHelper.getInstance().lastPlayedDuration);

                    homeBinding.videoview.start();

                    homeBinding.videoview.requestFocus();

                }
            }
        } catch (Exception e) {
            e.printStackTrace();
            Log.e("HomeFragment", e.getLocalizedMessage());
        }

//        Update TextViews

        HomeData homeData = AppHelper.getInstance().getHomeData();

        if (homeData != null) {

            homeBinding.uploadCredentialsCount.setText(String.valueOf(homeData.getAll_uploaded_credentials_count()));

            homeBinding.expireCredentialsCount.setText(String.valueOf(homeData.getExpired_credentials_count()));

            homeBinding.extraCredentialsCount.setText(String.valueOf(homeData.getExtra_credentials_count()));

            if (!TextUtils.isEmpty(homeData.getTagline())) {

                homeBinding.tagLine.setText(homeData.getTagline());
            }
        }

//        Reload Data
        if (AppHelper.getInstance().needHomeviewRefresh) {

            AppHelper.getInstance().needHomeviewRefresh = false;

            AppHelper.getInstance().loadVideoLink(this);

        }

    }

    //    onVideo download Callabcks
    public void onFiledownloaded() {
        if (AppHelper.getInstance().SELECTED_FRAGMENT == 0) {
            if (homeBinding.videoview != null) {
                restartVideo();
            }
        }
    }
}