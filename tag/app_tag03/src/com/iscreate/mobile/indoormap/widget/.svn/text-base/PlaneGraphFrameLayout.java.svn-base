package com.iscreate.mobile.indoormap.widget;

import java.util.HashMap;

import android.content.Context;
import android.graphics.Matrix;
import android.graphics.drawable.Drawable;
import android.util.AttributeSet;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.ImageView.ScaleType;

import com.iscreate.mobile.widget.ZoomPlaneGraphFrameLayout;

public class PlaneGraphFrameLayout extends ZoomPlaneGraphFrameLayout {
	public final static String LAYER_OUTWALL = "OUT_W";
	public final static String LAYER_TOILE = "TOILE";
	public final static String LAYER_ELEVATE = "ELEVA";
	public final static String LAYER_STAIR = "STAIR";
	public final static String LAYER_ROUTE = "ROUTE";
	public final static String LAYER_BUSSINESS = "BUSSI";
	public final static String LAYER_AP = "AP";
	public final static String LAYER_POI = "POI";
	public final static String[] LAYER_LIST = { LAYER_OUTWALL,//
			LAYER_TOILE,//
			LAYER_ELEVATE, //
			LAYER_STAIR,//
			LAYER_ROUTE,//
			LAYER_BUSSINESS,//
			LAYER_AP,//
			LAYER_POI //
	};
	public final static String LAYER_OVERLAYER = "OVERLAYER";
	private HashMap<String, ImageView> PlaneGraphLayerList = null;
	private OverLayerImageView im_overlayer = null;

	public PlaneGraphFrameLayout(Context context) {
		super(context);
		init();
	}

	public PlaneGraphFrameLayout(Context context, AttributeSet attrs) {
		super(context, attrs);
		init();
	}

	public PlaneGraphFrameLayout(Context context, AttributeSet attrs,
			int defStyle) {
		super(context, attrs, defStyle);
		init();
	}

	private void init() {
		PlaneGraphLayerList = new HashMap<String, ImageView>();
		for (String layer : LAYER_LIST) {
			addImageView(layer);
		}
		im_overlayer = new OverLayerImageView(getContext());
		FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(
				FrameLayout.LayoutParams.MATCH_PARENT,
				FrameLayout.LayoutParams.MATCH_PARENT);
		im_overlayer.setLayoutParams(params);
		im_overlayer.setScaleType(ScaleType.MATRIX);
		im_overlayer.setTag(LAYER_OVERLAYER);
		addView(im_overlayer);
	}

	private void addImageView(String layer) {
		ImageView im = new ImageView(getContext());
		FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(
				FrameLayout.LayoutParams.MATCH_PARENT,
				FrameLayout.LayoutParams.MATCH_PARENT);
		im.setLayoutParams(params);
		im.setScaleType(ScaleType.MATRIX);
		im.setTag(layer);
		addView(im);
		PlaneGraphLayerList.put(layer, im);
	}

	public void setImageDrawable(Drawable drawable) {
		if (drawable != null) {
			setPlaneGraphSize(drawable.getIntrinsicWidth(),
					drawable.getIntrinsicHeight());
		}
		im_overlayer.clrLocation();
		PlaneGraphLayerList.get(LAYER_OUTWALL).setImageDrawable(drawable);
	}

	public void setImageDrawable(String layer, Drawable drawable) {
		PlaneGraphLayerList.get(layer).setImageDrawable(drawable);
	}

	public void setLocation(Float x, Float y) {
		if ((x != null) && (y != null)) {
			setCenter(x, y);
		}
		im_overlayer.setLocation(x, y);
	}

	public void clrLocation() {
		im_overlayer.clrLocation();
	}

	private void setImageMatrix(Matrix matrix) {
		for (String layer : LAYER_LIST) {
			PlaneGraphLayerList.get(layer).setImageMatrix(matrix);
		}
		im_overlayer.setImageMatrix(matrix);
	}

	@Override
	public void onSetImageMatrix(Matrix matrix) {
		setImageMatrix(matrix);
	}
}
