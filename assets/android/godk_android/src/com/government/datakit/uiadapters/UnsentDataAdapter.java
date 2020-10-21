package com.government.datakit.uiadapters;

import java.io.File;
import java.util.ArrayList;

import org.json.JSONException;
import org.json.JSONObject;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.government.datakit.bo.FormsDataInfo;
import com.government.datakit.ui.MainScreen;
import com.government.datakit.ui.R;
import com.government.datakit.utils.CONSTANTS;

public class UnsentDataAdapter extends BaseAdapter{
	
	private LayoutInflater mInflater;
	private ArrayList<FormsDataInfo> arrayList;
	private Context context;
	
	public UnsentDataAdapter(Context context, ArrayList<FormsDataInfo> arrayList) {
		mInflater = LayoutInflater.from(context);
		this.arrayList = arrayList;
		this.context = context;
	}

	@Override
	public int getCount(){
		if(this.arrayList!=null && this.arrayList.size()>0){
			return arrayList.size();
		}else{
			return 0;
		}
	}

	public Object getItem(int position) {
	
		return arrayList.get(position);
	}


	public long getItemId(int position) {
	
		return position;
	}

	
	public View getView(int position, View convertView, ViewGroup parent) {
	
	ViewHolder holder;
	
	if (convertView == null){
		convertView = mInflater.inflate(R.layout.disease_unsent_row, null);
		holder = new ViewHolder();
		holder.textView1 = (TextView) convertView.findViewById(R.id.text1);
		holder.textView2 = (TextView) convertView.findViewById(R.id.date);
		holder.icon = (ImageView) convertView.findViewById(R.id.icon);
		convertView.setTag(holder);
	}else{	  
		holder = (ViewHolder) convertView.getTag();
	}
	
		FormsDataInfo dInfo = arrayList.get(position);
		String title=dInfo.formData;
		
		
		
		
		if( title != null)
		{
			JSONObject obj;
			try {
				obj = new JSONObject(title);
				String key = obj.optString("row_key", "");    
				
				if(!key.equalsIgnoreCase(""))
				{
					holder.textView1.setText(key+"\n"+dInfo.dateTime);
				}else{
					holder.textView1.setText(dInfo.dateTime);  
				}
				
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			
		}
		else{
			holder.textView1.setText(dInfo.dateTime);
		}
		
		
		holder.textView2.setVisibility(View.INVISIBLE);
		
		String iconPath = this.context.getFilesDir().getPath() + "/"+ MainScreen.htmlFilesDirectory + "/" + dInfo.formIconName;
		Log.i("FORM ICON PATH-UNSENT", ":: "+iconPath);
		if(dInfo.formIconName!=null && dInfo.formIconName.length()>0){
			File f=new File(iconPath);
			if(f.exists())
			{
				holder.icon.setImageBitmap(creatBitmap(iconPath));
			}
			
			/*Picasso.with(this.context)
			.load(iconPath)
			.placeholder(R.drawable.unsentdata_icon)
			.error(R.drawable.unsentdata_icon)
			.into(holder.icon, new Callback() {
				@Override
				public void onError() {
//					progressBar.setVisibility(View.GONE);
				}
				@Override
				public void onSuccess() {
//					progressBar.setVisibility(View.GONE);
				}
			});	*/

		}else if(dInfo.autoSave == CONSTANTS.AUTO_SAVE)
			
			holder.icon.setImageResource(R.drawable.unsentdata_icon);
		else
			holder.icon.setImageResource(R.drawable.editable_icon);
		
		return convertView;
	}
	

	class ViewHolder {
		
		TextView textView1;
		TextView textView2;
		ImageView icon;
		
	}
	
	public void setFormData(ArrayList<FormsDataInfo> arrayList){
		
		this.arrayList = arrayList;
		this.notifyDataSetChanged();
	}
	
	private Bitmap creatBitmap(String filePath){
		
		File image = new File(filePath);
		BitmapFactory.Options bmOptions = new BitmapFactory.Options();
		Bitmap bitmap = BitmapFactory.decodeFile(image.getAbsolutePath(), bmOptions);
		bitmap = Bitmap.createScaledBitmap(bitmap, 48, 48, true);
		return bitmap;
	}

}
