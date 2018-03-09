package com.iscreate.mobile.indoormap.activity;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import com.iscreate.mobile.indoormap.R;

public class MoreActivity extends Activity {

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.moretab);
		Button SignalTestMenu_btn = (Button) findViewById(R.id.SignalTestMenu_btn);
		SignalTestMenu_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(MoreActivity.this,
						signaltestActivity.class);
				startActivity(intent);
			}
		});
	}
}