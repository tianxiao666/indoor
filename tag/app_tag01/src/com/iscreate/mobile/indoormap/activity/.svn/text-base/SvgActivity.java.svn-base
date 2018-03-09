package com.iscreate.mobile.indoormap.activity;

import java.io.IOException;
import java.io.InputStream;

import android.app.Activity;
import android.content.res.AssetManager;
import android.graphics.drawable.PictureDrawable;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;

import com.iscreate.mobile.indoormap.R;
import com.larvalabs.svgandroid.SVG;
import com.larvalabs.svgandroid.SVGParser;

public class SvgActivity extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		// setContentView(R.layout.webviewmain);
		// WebView map_wv = (WebView)findViewById(R.id.map_wv);
		// map_wv.loadUrl("file:///android_asset/svg/xyx.htm");

		setContentView(R.layout.showmain);
		LinearLayout test_ll = (LinearLayout) findViewById(R.id.test_ll);
		String[] filelist = null;
		try {
			filelist = getAssets().list("svg");
		} catch (IOException e1) {
			e1.printStackTrace();
		}
		if (filelist != null) {
			for (String file : filelist) {
				InputStream is = null;
				AssetManager am = SvgActivity.this.getAssets();
				try {
					is = am.open("svg/" + file);
				} catch (Exception e) {
				}
				if (is != null) {
					SVG svg = SVGParser.getSVGFromInputStream(is);
					PictureDrawable svgpd = svg.createPictureDrawable();
					ImageView im = new ImageView(SvgActivity.this);
					im.setBackgroundDrawable(svgpd);
					test_ll.addView(im);
				}
			}
		}
	}
}