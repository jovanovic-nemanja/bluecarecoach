package com.app.bdo.fragments.document;

import android.graphics.PorterDuff;
import android.text.TextUtils;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.RecyclerView;

import com.app.bdo.R;
import com.app.bdo.helper.AppHelper;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by MobiDev on 28/03/21.
 */
public class DocumentAdapter extends RecyclerView.Adapter<DocumentAdapter.DocumentHolder> {

    public String docuementType= "credentials";

    private List<CredentialData> selectedDataList = new ArrayList<>();

    public onRowItemClickListener onRowItemClickListener;

    public Boolean showCheckBox = false;

    public int currentView = 0;

//    OnRowItem OnClick Callbacks

    public interface onRowItemClickListener {

        void onSelectedItem(int pos,CredentialData data);
        void onLongPressed(int pos,CredentialData data);
    }

    public DocumentAdapter(onRowItemClickListener callback){

        this.onRowItemClickListener  = callback;
    }

    //Checkbox Enable / disable

    public void startSelection(boolean show) {
        this.showCheckBox = show;
        notifyDataSetChanged();
    }

    public List<CredentialData> getSelectedDataList() {
        return selectedDataList;
    }

    public void setSelectedDataList(List<CredentialData> selectedDataList) {
        this.selectedDataList = selectedDataList;
    }

    public String getDocuementType() {
        return docuementType;
    }

    public void setDocuementType(String docuementType) {
        this.docuementType = docuementType;
    }

    @NonNull
    @Override
    public DocumentHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {

        LayoutInflater layoutInflater = LayoutInflater.from(parent.getContext());

        View view = layoutInflater.inflate(R.layout.document_item, parent, false);

        return new DocumentHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull DocumentHolder holder, int position) {

        if(getDocuementType().equals("credentials")){

            CredentialData data = AppHelper.getInstance().getCredentialDataList().get(position);

            holder.titleView.setText(data.getTitle());

            holder.itemView.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    onRowItemClickListener.onSelectedItem(position,data);
                }
            });

            if(TextUtils.isEmpty(data.getFile_name())) {

                holder.checkBox.setVisibility(View.GONE);

                holder.iconView.setColorFilter(ContextCompat.getColor(AppHelper.getInstance().getmContext(), android.R.color.darker_gray), android.graphics.PorterDuff.Mode.SRC_IN);

            }else{

                if(showCheckBox){

                    holder.checkBox.setVisibility(View.VISIBLE);

                }else{

                    holder.checkBox.setVisibility(View.GONE);
                }

                if(!TextUtils.isEmpty(data.getExpired()) && Integer.parseInt(data.getExpired()) < 0){

                    holder.iconView.setColorFilter(ContextCompat.getColor(AppHelper.getInstance().getmContext(),R.color.red), PorterDuff.Mode.SRC_IN);

                }else{

                    holder.iconView.setColorFilter(ContextCompat.getColor(AppHelper.getInstance().getmContext(), R.color.sign_btn_clr), android.graphics.PorterDuff.Mode.SRC_IN);
                }

            }

            holder.itemView.setOnLongClickListener(new View.OnLongClickListener() {
                @Override
                public boolean onLongClick(View view) {

                    Boolean isCreatedByyou = String.valueOf(AppHelper.getInstance().getUser().getId()).equals(data.getCreated_by());

                    if(isCreatedByyou && currentView == 2){

                        onRowItemClickListener.onLongPressed(position,data);
                    }
                    else if(!isCreatedByyou && TextUtils.isEmpty(data.getFile_name())){

                        onRowItemClickListener.onSelectedItem(position,data);

                    }else{

                        onRowItemClickListener.onLongPressed(position,data);
                    }
                    return true;
                }
            });

            if(isExist(data)){

                holder.checkBox.setChecked(true);

            }else{
                holder.checkBox.setChecked(false);

            }

            holder.checkBox.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {

                    if(isExist(data)){

                        removeItem(data);
                        notifyItemRemoved(position);


                    }else{

                        getSelectedDataList().add(data);
                        notifyItemChanged(position);
                    }
                }
            });

        }
    }

    public void removeItem(CredentialData data){

        for (CredentialData item:getSelectedDataList()
        ) {
            if(data.getId().equals(item.getId())){

                getSelectedDataList().remove(item);
                break;
            }
        }
    }

    public Boolean isExist(CredentialData data){

        for (CredentialData item:getSelectedDataList()
             ) {

            if(data.getId().equals(item.getId())){
                return  true;
            }
        }

        return false;
    }

    @Override
    public int getItemCount() {

        return docuementType.equals("credentials") ? AppHelper.getInstance().getCredentialDataList().size() : 0;
    }

    public class DocumentHolder extends RecyclerView.ViewHolder {

        public TextView titleView;

        public ImageView iconView;

        public CheckBox checkBox;

        public DocumentHolder(@NonNull View itemView) {
            super(itemView);

            titleView = itemView.findViewById(R.id.title);

            iconView = itemView.findViewById(R.id.icon);

            checkBox = itemView.findViewById(R.id.checkbox);
        }
    }
}
