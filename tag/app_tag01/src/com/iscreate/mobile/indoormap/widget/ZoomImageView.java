package com.iscreate.mobile.indoormap.widget;

import android.content.Context;
import android.graphics.Matrix;
import android.graphics.Point;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.widget.ImageView;

public class ZoomImageView extends ImageView {
	private final int ZOOM_DOUBLE_CLICK_TIME_GAP = 350;
	private final int ZOOM_DOUBLE_CLICK_DISTANCE_GAP = 40;
	private final float ZOOM_DOUBLE_CLICK_SCALE = 2f;
	private final float ZOOM_MAX_SCALE = 5f;
	private final float ZOOM_MIN_SCALE = 0.2f;
	private final float INIT_SCALE = 1f;
	private final float ZOOM_POINTER_PERIMETER_DISTANCE_GAP = 10;
	private final int ZOOM_POINTER_COUNT = 2;

	private Matrix imgMatrix = null;
	private Float dragStartX = null;
	private Float dragStartY = null;
	private long lastClickTime = 0;
	private float lastClickX = 0;
	private float lastClickY = 0;
	private Float zoomHoldX = null;
	private Float zoomHoldY = null;
	private float currentScale = INIT_SCALE;
	private float maxTranslateX = 0f;
	private float maxTranslateY = 0f;
	private float limitX1 = 0f;
	private float limitX2 = 0f;
	private float limitY1 = 0f;
	private float limitY2 = 0f;
	private double PointerPerimeter = -1;

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

	private void init() {
		setScaleType(ScaleType.MATRIX);
		imgMatrix = new Matrix();
		initScale();
	}

	@Override
	public void setImageDrawable(Drawable drawable) {
		super.setImageDrawable(drawable);
		initScaleLimit();
	}

	@Override
	public void setImageResource(int resId) {
		super.setImageResource(resId);
		initScaleLimit();
	}

	@Override
	public void setImageURI(Uri uri) {
		super.setImageURI(uri);
		initScaleLimit();
	}

	private void initScale() {
		imgMatrix.reset();
		currentScale = INIT_SCALE;
		imgMatrix.postScale(INIT_SCALE, INIT_SCALE);
		setImageMatrix(imgMatrix);
	}

	private void calcLimit() {
		if (getDrawable() != null) {
			float mapScaleWidth = getDrawable().getIntrinsicWidth()
					* currentScale;
			float mapScaleHeight = getDrawable().getIntrinsicHeight()
					* currentScale;
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

	private void initScaleLimit() {
		currentScale = INIT_SCALE;
		calcLimit();
		imgMatrix.reset();
		imgMatrix.postScale(currentScale, currentScale);
		imgMatrix.postTranslate(maxTranslateX / 2, maxTranslateY / 2);
		setImageMatrix(imgMatrix);
	}

	@Override
	public boolean onTouchEvent(MotionEvent event) {
		int action = event.getActionMasked();
		int PointerCount = event.getPointerCount();
		switch (action) {
		case MotionEvent.ACTION_DOWN:
			PointerPerimeter = -1;
			dragStartX = event.getX();
			dragStartY = event.getY();
			long thisClickTime = event.getEventTime();
			if (((thisClickTime - lastClickTime) < ZOOM_DOUBLE_CLICK_TIME_GAP)
					&& (Math.sqrt((dragStartY - lastClickY)
							* (dragStartY - lastClickY)
							+ (dragStartX - lastClickX)
							* (dragStartX - lastClickX)) < ZOOM_DOUBLE_CLICK_DISTANCE_GAP)) {
				zoom(event.getX(), event.getY());
				lastClickTime = 0;
			} else {
				lastClickTime = thisClickTime;
				lastClickY = dragStartY;
				lastClickX = dragStartX;
			}
			break;
		case MotionEvent.ACTION_UP:
			PointerPerimeter = -1;
			dragStartX = null;
			dragStartY = null;
			zoomHoldX = null;
			zoomHoldY = null;
			break;
		case MotionEvent.ACTION_MOVE:
			if (PointerCount < ZOOM_POINTER_COUNT) {
				zoomHoldX = null;
				zoomHoldY = null;
				if ((dragStartX == null) || (dragStartY == null)) {
					dragStartX = event.getX();
					dragStartY = event.getY();
				}
				float transX = 0;
				float transY = 0;
				if (dragStartX != null) {
					float moveX = event.getX();
					transX = moveX - dragStartX;
					dragStartX = moveX;
				}
				if (dragStartY != null) {
					float moveY = event.getY();
					transY = moveY - dragStartY;
					dragStartY = moveY;
				}
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
			} else {
				double nowPointersRound = getEventPointerPerimeter(event, false);
				if (Math.abs(PointerPerimeter - nowPointersRound) > ZOOM_POINTER_PERIMETER_DISTANCE_GAP
						* PointerCount) {
					setImageMatrix(imgMatrix);
					float[] pointerCenter = getEventPointerCenter(event);
					if (zoomHoldX == null) {
						zoomHoldX = pointerCenter[0];
						zoomHoldY = pointerCenter[1];
					}
					float scale = (float) (nowPointersRound / PointerPerimeter);
					zoom(scale, scale, pointerCenter[0], pointerCenter[1]);
					PointerPerimeter = nowPointersRound;
				}
			}
			break;
		case MotionEvent.ACTION_POINTER_DOWN:
			PointerPerimeter = getEventPointerPerimeter(event, false);
			break;
		case MotionEvent.ACTION_POINTER_UP:
			if (PointerCount == ZOOM_POINTER_COUNT) {
				PointerPerimeter = -1;
				dragStartX = null;
				dragStartY = null;
				zoomHoldX = null;
				zoomHoldY = null;
			} else {
				PointerPerimeter = getEventPointerPerimeter(event, true);
			}
			break;
		}
		return (true);
	}

	/**
	 * 获取所有Pointer中心点
	 * 
	 * @param ev
	 * @return
	 */
	private float[] getEventPointerCenter(MotionEvent ev) {
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
	 * 获取所有Pointer的周长
	 * 
	 * @param ev
	 * @param skipThisPoint
	 * @return
	 */
	private double getEventPointerPerimeter(MotionEvent ev,
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

	private void zoom(float centerX, float centerY) {
		float[] matrixValues = new float[9];
		imgMatrix.getValues(matrixValues);
		if (matrixValues[Matrix.MSCALE_X] != INIT_SCALE) {
			initScaleLimit();
		} else {
			zoom(ZOOM_DOUBLE_CLICK_SCALE, ZOOM_DOUBLE_CLICK_SCALE, centerX,
					centerY);
		}
	}

	private void zoom(float scaleX, float scaleY, float centerX, float centerY) {
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
		float mapScaleWidth = getDrawable().getIntrinsicWidth() * currentScale;
		float mapScaleHeight = getDrawable().getIntrinsicHeight()
				* currentScale;
		maxTranslateX = getWidth() - mapScaleWidth;
		maxTranslateY = getHeight() - mapScaleHeight;
		float transX = 0;
		float transY = 0;
		if (maxTranslateX > 0) {
			transX = maxTranslateX / 2 - matrixValues[Matrix.MTRANS_X] * scaleX;
		} else {
			if (scaleX != 1) {
				if (zoomHoldX == null) {
					transX = (getWidth() / 2 - centerX * scaleX);
				} else {
					transX = zoomHoldX * (1 - scaleX);
				}
			}
		}
		if (maxTranslateY > 0) {
			transY = maxTranslateY / 2 - matrixValues[Matrix.MTRANS_Y] * scaleY;
		} else {
			if (scaleY != 1) {
				if (zoomHoldY == null) {
					transY = (getHeight() / 2 - centerY * scaleY);
				} else {
					transY = zoomHoldY * (1 - scaleY);
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

	public float getXOnView(float x) {
		float[] matrixValues = new float[9];
		imgMatrix.getValues(matrixValues);
		return (x * matrixValues[Matrix.MSCALE_X] + matrixValues[Matrix.MTRANS_X]);
	}

	public float getYOnView(float y) {
		float[] matrixValues = new float[9];
		imgMatrix.getValues(matrixValues);
		return (y * matrixValues[Matrix.MSCALE_Y] + matrixValues[Matrix.MTRANS_Y]);
	}

	public Point getPointOnView(Point p) {
		int x = (int) getXOnView(p.x);
		int y = (int) getYOnView(p.y);
		return (new Point(x, y));
	}
}