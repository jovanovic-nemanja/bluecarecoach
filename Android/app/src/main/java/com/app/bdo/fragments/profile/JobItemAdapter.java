package com.app.bdo.fragments.profile;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.app.bdo.R;
import com.app.bdo.utils.Logger;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by MobiDev on 02/04/21.
 */
public class JobItemAdapter  extends RecyclerView.Adapter<JobItemAdapter.JobItemHolder>{

    private List<JobItem> jobItemList = new ArrayList<>();
    private JobItemClickedListener jobItemClickedListener;

    public void addItem(JobItem item) {
        jobItemList.add(item);
        notifyDataSetChanged();
    }

    public JobItem getitem(int position) {
        return jobItemList.get(position);
    }

    public void updatetem(JobItem data) {
        Logger.debug("updatetem","pos "+data.getPosition() + " val "+data.getValue());
        jobItemList.set(data.getPosition(),data);
        notifyDataSetChanged();

    }

    public List<JobItem> getList() {
        return jobItemList;
    }

    public interface JobItemClickedListener{
        void onItemClicked(JobItem data,int pos);
    }

    public JobItemAdapter(JobItemClickedListener callbacks){

        this.jobItemClickedListener = callbacks;
    }

    public void setData(List<JobItem> itemList){
        this.jobItemList = itemList;
        notifyDataSetChanged();

    }

    @NonNull
    @Override
    public JobItemHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {

        LayoutInflater layoutInflater = LayoutInflater.from(parent.getContext());
        View view = layoutInflater.inflate(R.layout.job_item_view, parent, false);
        return new JobItemHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull JobItemHolder holder, int position) {
        JobItem item = jobItemList.get(position);

        holder.title.setText(item.getTitle());
        holder.valueSet.setText(item.getValue());

        holder.itemView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                jobItemClickedListener.onItemClicked(item,position);
            }
        });

    }

    @Override
    public int getItemCount() {
        return jobItemList.size();
    }

    public class JobItemHolder extends RecyclerView.ViewHolder {
        public TextView title;
        public TextView valueSet;
        public JobItemHolder(@NonNull View itemView) {
            super(itemView);

            title = itemView.findViewById(R.id.titleView);
            valueSet = itemView.findViewById(R.id.value);
        }
    }
}
