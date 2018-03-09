package com.iscreate.mobile.utils;

import com.iscreate.mobile.indoormap.widget.CustomAlertDialog;

import android.app.Activity;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Matrix;
import android.graphics.PixelFormat;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.view.MotionEvent;
import android.view.View;
import android.widget.ListView;

public class utils {
	/**
	 * convert drawable to bitmap
	 * 
	 * @param drawable
	 * @return
	 */
	public static Bitmap drawableToBitmap(Drawable drawable) {
		if (drawable instanceof BitmapDrawable) {
			return (((BitmapDrawable) drawable).getBitmap());
		}
		int w = drawable.getIntrinsicWidth();
		int h = drawable.getIntrinsicHeight();
		Bitmap bitmap = Bitmap
				.createBitmap(
						w,
						h,
						drawable.getOpacity() != PixelFormat.OPAQUE ? Bitmap.Config.ARGB_8888
								: Bitmap.Config.RGB_565);
		Canvas canvas = new Canvas(bitmap);
		drawable.setBounds(0, 0, canvas.getWidth(), canvas.getHeight());
		drawable.draw(canvas);
		return (bitmap);
	}

	/**
	 * zoom Drawable to the specified width and height
	 * 
	 * @param drawable
	 *            the Drawable to zoom
	 * @param w
	 *            the specified width
	 * @param h
	 *            the specified height
	 * @return the Drawable which is resized
	 */
	public static Drawable zoomDrawable(Drawable drawable, int w, int h) {
		Bitmap oldbmp = drawableToBitmap(drawable);
		int width = oldbmp.getWidth();
		int height = oldbmp.getHeight();
		float scaleWidth = ((float) w / width);
		float scaleHeight = ((float) h / height);
		Matrix matrix = new Matrix();
		matrix.postScale(scaleWidth, scaleHeight);
		Bitmap newbmp = Bitmap.createBitmap(oldbmp, 0, 0, width, height,
				matrix, true);
		Drawable newDrawable = new BitmapDrawable(newbmp);
		return (newDrawable);
	}

	/**
	 * zoom Drawable to the specified scale
	 * 
	 * @param drawable
	 *            the Drawable to zoom
	 * @param w
	 *            the specified scale
	 * @return the Drawable which is resized
	 */
	public static Drawable zoomDrawable(Drawable drawable, float scale) {
		Bitmap oldbmp = drawableToBitmap(drawable);
		int width = oldbmp.getWidth();
		int height = oldbmp.getHeight();
		Matrix matrix = new Matrix();
		matrix.postScale(scale, scale);
		Bitmap newbmp = Bitmap.createBitmap(oldbmp, 0, 0, width, height,
				matrix, true);
		Drawable newDrawable = new BitmapDrawable(newbmp);
		return (newDrawable);
	}

	/**
	 * get the center coordinate of all the pointers from MotionEvent
	 * 
	 * @param ev
	 *            a MotionEvent
	 * @return the center coordinate of all the pointers from MotionEvent
	 */
	public static float[] getEventPointerCenter(MotionEvent ev) {
		int PointerCount = ev.getPointerCount();
		int i = 0;
		double sumx = 0;
		double sumy = 0;
		while (i < PointerCount) {
			sumx = sumx + ev.getX(i);
			sumy = sumy + ev.getY(i);
			++i;
		}
		float[] pointerCenter = new float[2];
		pointerCenter[0] = (float) (sumx / PointerCount);
		pointerCenter[1] = (float) (sumy / PointerCount);
		return (pointerCenter);
	}

	/**
	 * calculate the perimeters of all the pointers from MotionEvent
	 * 
	 * @param ev
	 *            a MotionEvent
	 * @param skipThisPoint
	 *            true if want to skip this point
	 * @return the perimeters
	 */
	public static double getEventPointerPerimeter(MotionEvent ev,
			boolean skipThisPoint) {
		int PointerCount = ev.getPointerCount();
		int skipThisPointId = -2;
		if (skipThisPoint) {
			--PointerCount;
			skipThisPointId = ev.getPointerId(ev.getActionIndex());
		}
		if (PointerCount > 0) {
			float[][] PointerCoord = new float[PointerCount][2];
			int i = 0;
			while (i < PointerCount) {
				if (skipThisPointId != i) {
					PointerCoord[i][0] = ev.getX(i);
					PointerCoord[i][1] = ev.getY(i);
				}
				++i;
			}
			{
				i = 0;
				int j;
				float[] tmp;
				while (i < PointerCount) {
					j = i + 1;
					while (j < PointerCount) {
						if (PointerCoord[j][0] < PointerCoord[i][0]) {
							tmp = PointerCoord[i];
							PointerCoord[i] = PointerCoord[j];
							PointerCoord[j] = tmp;
						} else {
							if (PointerCoord[j][0] == PointerCoord[i][0]) {
								if (PointerCoord[j][1] > PointerCoord[i][1]) {
									tmp = PointerCoord[i];
									PointerCoord[i] = PointerCoord[j];
									PointerCoord[j] = tmp;
								}
							}
						}
						++j;
					}
					++i;
				}
			}
			double perimeter = 0;
			{
				float x;
				float y;
				i = 0;
				while (i < PointerCount) {
					x = (PointerCoord[i][0] - PointerCoord[(i + 1)
							% PointerCount][0]);
					y = (PointerCoord[i][1] - PointerCoord[(i + 1)
							% PointerCount][1]);
					perimeter = (perimeter + Math.sqrt(x * x + y * y));
					++i;
				}
			}
			return (perimeter);
		}
		return (0d);
	}

	/**
	 * exit app
	 * 
	 * @param activity
	 *            the Activity where you call this function
	 */
	public static void exitApp(Activity activity) {
		activity.finish();
		activity.getApplication().onTerminate();
		System.exit(0);
	}

	/**
	 * give a AlertDialog to confirm exit
	 * 
	 * @param activity
	 *            the Activity where you call this function
	 */
	public static void exitAppAlertDialog(final Activity activity) {
		new CustomAlertDialog(activity, "退出", "确定退出吗？") {
			@Override
			public void onConfirm() {
				exitApp(activity);
			}

			@Override
			public void onCancel() {
			}
		}.show();
	}

	/**
	 * get a Bitmap from a View
	 * 
	 * @param view
	 *            a View
	 * @return a Bitmap
	 */
	public static Bitmap getBitmapFromView(View view) {
		if (view != null) {
			view.destroyDrawingCache();
			view.measure(View.MeasureSpec.makeMeasureSpec(0,
					View.MeasureSpec.UNSPECIFIED), View.MeasureSpec
					.makeMeasureSpec(0, View.MeasureSpec.UNSPECIFIED));
			view.layout(0, 0, view.getMeasuredWidth(), view.getMeasuredHeight());
			view.setDrawingCacheEnabled(true);
			Bitmap bitmap = view.getDrawingCache(true);
			return (bitmap);
		}
		return (null);
	}

	/**
	 * get the specified index View in a ListView
	 * 
	 * @param lv
	 *            a ListView
	 * @param position
	 *            the index in the ListView
	 * @return the specified View
	 */
	public static View getListViewPositionView(ListView lv, int position) {
		if (lv != null) {
			View v;
			int count = lv.getChildCount();
			int i = 0;
			while (i < count) {
				v = lv.getChildAt(i);
				if (lv.getPositionForView(v) == position) {
					return (v);
				}
				++i;
			}
		}
		return (null);
	}

	/**
	 * 计算两点间的距离
	 */
	public static double getDistance(double x1, double y1, double x2, double y2) {
		return (Math.sqrt((((x2 - x1) * (x2 - x1)) + ((y2 - y1) * (y2 - y1)))));
	}
	// public static ProgressDialog newProgressDialog(Context context) {
	// ProgressDialog pd = new ProgressDialog(context);
	// pd.setTitle("请稍等");
	// pd.setMessage("正在读取数据中……");
	// pd.setCancelable(false);
	// return (pd);
	// }
}
