package com.iscreate.mobile.widget;

import android.content.Context;
import android.graphics.Matrix;
import android.graphics.Point;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.widget.ImageView;

public class ZoomImageView extends ImageView {
	/**
	 * this scale will be set if a double click occurred
	 */
	private final float ZOOM_DOUBLE_CLICK_SCALE = 2f;
	/**
	 * the max scale by two finger zoom
	 */
	private final float ZOOM_MAX_SCALE = 5f;
	/**
	 * the min scale by two finger zoom
	 */
	private final float ZOOM_MIN_SCALE = 0.2f;
	/**
	 * the init scale
	 */
	private final float INIT_SCALE = 1f;
	/**
	 * the Matrix of this ImageView
	 */
	private Matrix imgMatrix = null;
	/**
	 * current scale
	 */
	private float currentScale = INIT_SCALE;
	/**
	 * max translate value in x
	 */
	private float maxTranslateX = 0f;
	/**
	 * max translate value in y
	 */
	private float maxTranslateY = 0f;
	/**
	 * max value to translate right
	 */
	private float limitX1 = 0f;
	/**
	 * max value to translate left
	 */
	private float limitX2 = 0f;
	/**
	 * max value to translate down
	 */
	private float limitY1 = 0f;
	/**
	 * max value to translate up
	 */
	private float limitY2 = 0f;
	/**
	 * Touch事件处理器
	 */
	private TouchEventHandler touchEventHandler = null;

	public ZoomImageView(Context context) {
		super(context);
		init();
	}

	public ZoomImageView(Context context, AttributeSet attrs) {
		super(context, attrs);
		init();
	}

	public ZoomImageView(Context context, AttributeSet attrs, int defStyle) {
		super(context, attrs, defStyle);
		init();
	}

	/**
	 * init scale
	 */
	private void init() {
		setScaleType(ScaleType.MATRIX);
		imgMatrix = new Matrix();
		initScale();
		touchEventHandler = new TouchEventHandler() {
			@Override
			public void onDoubleClick(float x, float y) {
				zoom(x, y);
			}

			@Override
			public void onDrag(float offsetx, float offsety) {
				drag(offsetx, offsety);
			}

			@Override
			public void onZoom(float centerx, float centery, float holdx,
					float holdy, float scale) {
				zoom(scale, scale, centerx, centery, holdx, holdy);
			}
		};
	}

	/**
	 * init the scale and calculate the translate limit of right,left,top,bottom
	 * if a new drawable is setting
	 */
	@Override
	public void setImageDrawable(Drawable drawable) {
		super.setImageDrawable(drawable);
		initScaleLimit();
	}

	/**
	 * init the scale and calculate the translate limit of right,left,top,bottom
	 * if a new image resource is setting
	 */
	@Override
	public void setImageResource(int resId) {
		super.setImageResource(resId);
		initScaleLimit();
	}

	/**
	 * init the scale and calculate the translate limit of right,left,top,bottom
	 * if a new image uri is setting
	 */
	@Override
	public void setImageURI(Uri uri) {
		super.setImageURI(uri);
		initScaleLimit();
	}

	/**
	 * init the scale
	 */
	private void initScale() {
		imgMatrix.reset();
		currentScale = INIT_SCALE;
		imgMatrix.postScale(INIT_SCALE, INIT_SCALE);
		setImageMatrix(imgMatrix);
	}

	/**
	 * calculate the translate limit of right,left,top,bottom
	 */
	private void calcLimit() {
		Drawable drawable = getDrawable();
		if (drawable != null) {
			float mapScaleWidth = drawable.getIntrinsicWidth() * currentScale;
			float mapScaleHeight = drawable.getIntrinsicHeight() * currentScale;
			maxTranslateX = getWidth() - mapScaleWidth;
			maxTranslateY = getHeight() - mapScaleHeight;
			limitX1 = maxTranslateX / 2;
			limitY1 = maxTranslateY / 2;
			limitX2 = limitX1;
			limitY2 = limitY1;
			if (maxTranslateX < 0) {
				limitX1 = maxTranslateX;
				limitX2 = 0;
			}
			if (maxTranslateY < 0) {
				limitY1 = maxTranslateY;
				limitY2 = 0;
			}
		}
	}

	/**
	 * init the scale and calculate the translate limit of right,left,top,bottom
	 */
	private void initScaleLimit() {
		currentScale = INIT_SCALE;
		calcLimit();
		imgMatrix.reset();
		imgMatrix.postScale(currentScale, currentScale);
		imgMatrix.postTranslate(maxTranslateX / 2, maxTranslateY / 2);
		setImageMatrix(imgMatrix);
	}

	/**
	 * do action on touch event
	 */
	@Override
	public boolean onTouchEvent(MotionEvent event) {
		touchEventHandler.updateTouchEvent(event);
		return (true);
	}

	private void drag(float transX, float transY) {
		float[] matrixValues = new float[9];
		imgMatrix.getValues(matrixValues);
		if (transX + matrixValues[Matrix.MTRANS_X] < limitX1) {
			transX = limitX1 - matrixValues[Matrix.MTRANS_X];
		}
		if (transX + matrixValues[Matrix.MTRANS_X] > limitX2) {
			transX = limitX2 - matrixValues[Matrix.MTRANS_X];
		}
		if (transY + matrixValues[Matrix.MTRANS_Y] < limitY1) {
			transY = limitY1 - matrixValues[Matrix.MTRANS_Y];
		}
		if (transY + matrixValues[Matrix.MTRANS_Y] > limitY2) {
			transY = limitY2 - matrixValues[Matrix.MTRANS_Y];
		}
		imgMatrix.postTranslate(transX, transY);
		setImageMatrix(imgMatrix);
	}

	/**
	 * 
	 * @param centerX
	 *            a x-coordinate where you want to keep
	 * @param centerY
	 *            a y-coordinate where you want to keep
	 */
	private void zoom(float centerX, float centerY) {
		float[] matrixValues = new float[9];
		imgMatrix.getValues(matrixValues);
		if (matrixValues[Matrix.MSCALE_X] != INIT_SCALE) {
			initScaleLimit();
		} else {
			zoom(ZOOM_DOUBLE_CLICK_SCALE, ZOOM_DOUBLE_CLICK_SCALE, centerX,
					centerY, null, null);
		}
	}

	/**
	 * set to current scale, and keep (centerX,centerY) as center
	 * 
	 * @param scaleX
	 *            the x scale you want to set
	 * @param scaleY
	 *            the y scale you want to set
	 * @param centerX
	 *            a x-coordinate where you want to keep
	 * @param centerY
	 *            a y-coordinate where you want to keep
	 */
	private void zoom(float scaleX, float scaleY, float centerX, float centerY,
			Float holdx, Float holdy) {
		float[] matrixValues = new float[9];
		imgMatrix.getValues(matrixValues);
		float toScaleX = scaleX * matrixValues[Matrix.MSCALE_X];
		float toScaleY = scaleY * matrixValues[Matrix.MSCALE_Y];
		if (toScaleX < ZOOM_MIN_SCALE) {
			scaleX = ZOOM_MIN_SCALE / matrixValues[Matrix.MSCALE_X];
		}
		if (toScaleY < ZOOM_MIN_SCALE) {
			scaleY = ZOOM_MIN_SCALE / matrixValues[Matrix.MSCALE_Y];
		}
		if (toScaleY > ZOOM_MAX_SCALE) {
			scaleX = ZOOM_MAX_SCALE / matrixValues[Matrix.MSCALE_X];
		}
		if (toScaleY > ZOOM_MAX_SCALE) {
			scaleY = ZOOM_MAX_SCALE / matrixValues[Matrix.MSCALE_Y];
		}
		toScaleX = scaleX * matrixValues[Matrix.MSCALE_X];
		toScaleY = scaleY * matrixValues[Matrix.MSCALE_Y];
		currentScale = toScaleX;
		imgMatrix.postScale(scaleX, scaleY);
		Drawable drawable = getDrawable();
		if (drawable != null) {
			float mapScaleWidth = drawable.getIntrinsicWidth() * currentScale;
			float mapScaleHeight = drawable.getIntrinsicHeight() * currentScale;
			maxTranslateX = getWidth() - mapScaleWidth;
			maxTranslateY = getHeight() - mapScaleHeight;
			float transX = 0;
			float transY = 0;
			if (maxTranslateX > 0) {
				transX = maxTranslateX / 2 - matrixValues[Matrix.MTRANS_X]
						* scaleX;
			} else {
				if (scaleX != 1) {
					if (holdx == null) {
						transX = (getWidth() / 2 - centerX * scaleX);
					} else {
						transX = holdx * (1 - scaleX);
					}
				}
			}
			if (maxTranslateY > 0) {
				transY = maxTranslateY / 2 - matrixValues[Matrix.MTRANS_Y]
						* scaleY;
			} else {
				if (scaleY != 1) {
					if (holdy == null) {
						transY = (getHeight() / 2 - centerY * scaleY);
					} else {
						transY = holdy * (1 - scaleY);
					}
				}
			}
			limitX1 = maxTranslateX / 2;
			limitY1 = maxTranslateY / 2;
			limitX2 = limitX1;
			limitY2 = limitY1;
			if (maxTranslateX < 0) {
				limitX1 = maxTranslateX;
				limitX2 = 0;
			}
			if (maxTranslateY < 0) {
				limitY1 = maxTranslateY;
				limitY2 = 0;
			}
			if (transX + matrixValues[Matrix.MTRANS_X] * scaleX < limitX1) {
				transX = limitX1 - matrixValues[Matrix.MTRANS_X] * scaleX;
			}
			if (transX + matrixValues[Matrix.MTRANS_X] * scaleX > limitX2) {
				transX = limitX2 - matrixValues[Matrix.MTRANS_X] * scaleX;
			}
			if (transY + matrixValues[Matrix.MTRANS_Y] * scaleY < limitY1) {
				transY = limitY1 - matrixValues[Matrix.MTRANS_Y] * scaleY;
			}
			if (transY + matrixValues[Matrix.MTRANS_Y] * scaleY > limitY2) {
				transY = limitY2 - matrixValues[Matrix.MTRANS_Y] * scaleY;
			}
			imgMatrix.postTranslate(transX, transY);
			setImageMatrix(imgMatrix);
		}
	}

	/**
	 * convert x-coordinate from drawable to view
	 * 
	 * @param x
	 *            x-coordinate on your drawable
	 * @return x-coordinate on this view
	 */
	public float getXOnView(float x) {
		float[] matrixValues = new float[9];
		imgMatrix.getValues(matrixValues);
		return (x * matrixValues[Matrix.MSCALE_X] + matrixValues[Matrix.MTRANS_X]);
	}

	/**
	 * convert y-coordinate from drawable to view
	 * 
	 * @param y
	 *            y-coordinate on your drawable
	 * @return y-coordinate on this view
	 */
	public float getYOnView(float y) {
		float[] matrixValues = new float[9];
		imgMatrix.getValues(matrixValues);
		return (y * matrixValues[Matrix.MSCALE_Y] + matrixValues[Matrix.MTRANS_Y]);
	}

	/**
	 * convert a Point from drawable to view
	 * 
	 * @param p
	 *            a Point on this drawable
	 * @return a Point on this view
	 */
	public Point getPointOnView(Point p) {
		int x = (int) getXOnView(p.x);
		int y = (int) getYOnView(p.y);
		return (new Point(x, y));
	}

	/**
	 * set this view's center coordinate (x,y) on this drawable
	 * 
	 * @param x
	 *            x-coordinate on this drawable to be the center of this view
	 * @param y
	 *            y-coordinate on this drawable to be the center of this view
	 */
	public void setCenter(float x, float y) {
		float[] matrixValues = new float[9];
		imgMatrix.getValues(matrixValues);
		x = (int) getXOnView(x);
		y = (int) getYOnView(y);
		float transX = 0;
		float transY = 0;
		if (limitX1 != limitX2) {
			transX = (getWidth() / 2 - x);
		}
		if (limitY1 != limitY2) {
			transY = (getWidth() / 2 - y);
		}
		if (transX + matrixValues[Matrix.MTRANS_X] < limitX1) {
			transX = limitX1 - matrixValues[Matrix.MTRANS_X];
		}
		if (transX + matrixValues[Matrix.MTRANS_X] > limitX2) {
			transX = limitX2 - matrixValues[Matrix.MTRANS_X];
		}
		if (transY + matrixValues[Matrix.MTRANS_Y] < limitY1) {
			transY = limitY1 - matrixValues[Matrix.MTRANS_Y];
		}
		if (transY + matrixValues[Matrix.MTRANS_Y] > limitY2) {
			transY = limitY2 - matrixValues[Matrix.MTRANS_Y];
		}
		imgMatrix.postTranslate(transX, transY);
		setImageMatrix(imgMatrix);
	}
}