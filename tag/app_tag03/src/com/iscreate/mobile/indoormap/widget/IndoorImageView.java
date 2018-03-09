package com.iscreate.mobile.indoormap.widget;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Matrix;
import android.graphics.Paint;
import android.graphics.Paint.Style;
import android.graphics.drawable.Drawable;
import android.util.AttributeSet;

import com.iscreate.mobile.indoormap.R;
import com.iscreate.mobile.utils.utils;
import com.iscreate.mobile.widget.ZoomImageView;

public class IndoorImageView extends ZoomImageView {
	/**
	 * the x-coordinate of the current location
	 */
	private Integer Location_X = null;
	/**
	 * the y-coordinate of the current location
	 */
	private Integer Location_Y = null;
	/**
	 * the bitmap for current location icon
	 */
	private Bitmap Location_BM = null;

	public IndoorImageView(Context context) {
		super(context);
	}

	public IndoorImageView(Context context, AttributeSet attrs) {
		super(context, attrs);
	}

	public IndoorImageView(Context context, AttributeSet attrs, int defStyle) {
		super(context, attrs, defStyle);
	}

	/**
	 * clear the location coordinate if a new drawable is setting
	 */
	@Override
	public void setImageDrawable(Drawable drawable) {
		super.setImageDrawable(drawable);
		Location_X = null;
		Location_Y = null;
	}

	/**
	 * draw current location while onDraw occurred
	 */
	@Override
	protected void onDraw(Canvas canvas) {
		super.onDraw(canvas);
		DrawLocation(canvas);
	}

	/**
	 * set current location to (x,y)
	 * 
	 * @param x
	 *            the x-coordinate of the current location to be set
	 * @param y
	 *            the y-coordinate of the current location to be set
	 */
	public void setLocation(int x, int y) {
		Location_X = x;
		Location_Y = y;
		setCenter(x, y);
		invalidate();
	}

	/**
	 * draw a cross
	 * 
	 * @param canvas
	 *            the canvas on which the background will be drawn
	 * @param x
	 *            the x-coordinate where to draw the cross
	 * @param y
	 *            the y-coordinate where to draw the cross
	 * @param radius
	 *            the radius from the externally tangent circle of this cross
	 */
	private void DrawCross(Canvas canvas, float x, float y, float radius) {
		Paint paint = new Paint();
		paint.setStrokeWidth(4);
		paint.setStyle(Style.FILL);
		paint.setColor(Color.BLACK);
		canvas.drawLine(x - radius, y, x + radius, y, paint);
		canvas.drawLine(x, y - radius, x, y + radius, paint);
	}

	/**
	 * draw current location (x,y)
	 * 
	 * @param canvas
	 *            the canvas on which the background will be drawn
	 * @param x
	 *            the x-coordinate where to draw current location
	 * @param y
	 *            the y-coordinate where to draw current location
	 */
	private void DrawLocationPic(Canvas canvas, float x, float y) {
		if (Location_BM == null) {
			Drawable drawable = getContext().getResources().getDrawable(
					R.drawable.icon_locations);
			Location_BM = utils.drawableToBitmap(drawable);
		}
		if (Location_BM != null) {
			Matrix matrix = new Matrix();
			matrix.postTranslate(x - Location_BM.getWidth() / 2, y
					- Location_BM.getHeight() / 2);
			canvas.drawBitmap(Location_BM, matrix, null);
		}
		DrawCross(canvas, getXOnView(Location_X), getYOnView(Location_Y), 10);
	}

	/**
	 * draw current location
	 * 
	 * @param canvas
	 *            the canvas on which the background will be drawn
	 */
	private void DrawLocation(Canvas canvas) {
		if (Location_X != null && Location_Y != null) {
			DrawLocationPic(canvas, getXOnView(Location_X),
					getYOnView(Location_Y));
		}
	}
}