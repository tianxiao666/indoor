package com.iscreate.mobile.indoormap.widget;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Paint.Style;
import android.graphics.Point;
import android.util.AttributeSet;

public class indoorimageview extends ZoomImageView {
	private float radius = 10;
	private Integer onMeasureWidth = null;
	private Integer onMeasureHeight = null;
	private List<Point> locationlist = null;
	private HashMap<String, HashMap<String, String>> resultMap = null;

	public indoorimageview(Context context) {
		super(context);
	}

	public indoorimageview(Context context, AttributeSet attrs) {
		super(context, attrs);
	}

	public indoorimageview(Context context, AttributeSet attrs, int defStyle) {
		super(context, attrs, defStyle);
	}

	public void addLocation(int x, int y) {
		if (locationlist == null) {
			locationlist = new ArrayList<Point>();
		}
		Point p = new Point(x, y);
		locationlist.add(p);
		invalidate();
	}

	public void setCircle(HashMap<String, HashMap<String, String>> resultMap) {
		this.resultMap = resultMap;
		invalidate();
	}

	private void DrawLine(Canvas canvas, float x0, float y0, float x1, float y1) {
		Paint paint = new Paint();
		paint.setColor(Color.BLACK);
		paint.setStrokeWidth(2);
		paint.setStyle(Style.FILL);
		canvas.drawLine(x0, y0, x1, y1, paint);
	}

	private void DrawStartPoint(Canvas canvas, float x, float y, float radius) {
		Paint paint = new Paint();
		// paint.setColor(Color.RED);
		// paint.setStyle(Style.STROKE);
		paint.setStrokeWidth(4);
		// canvas.drawCircle(x, y, radius, paint);
		// paint.setColor(Color.GREEN);
		paint.setStyle(Style.FILL);
		// canvas.drawCircle(x, y, radius - 3, paint);
		paint.setColor(Color.BLUE);
		canvas.drawLine(x - radius, y, x + radius, y, paint);
		canvas.drawLine(x, y - radius, x, y + radius, paint);
	}

	private void DrawLastPoint(Canvas canvas, float x, float y, float radius) {
		Paint paint = new Paint();
		// paint.setColor(Color.RED);
		// paint.setStyle(Style.STROKE);
		paint.setStrokeWidth(4);
		// canvas.drawCircle(x, y, radius, paint);
		// paint.setColor(Color.GREEN);
		paint.setStyle(Style.FILL);
		// canvas.drawCircle(x, y, radius - 3, paint);
		paint.setColor(Color.RED);
		canvas.drawLine(x - radius, y, x + radius, y, paint);
		canvas.drawLine(x, y - radius, x, y + radius, paint);
	}

	private void DrawPoint(Canvas canvas, float x, float y, float radius) {
		Paint paint = new Paint();
		// paint.setColor(Color.GREEN);
		// paint.setStyle(Style.STROKE);
		paint.setStrokeWidth(4);
		// canvas.drawCircle(x, y, radius, paint);
		// paint.setColor(Color.RED);
		paint.setStyle(Style.FILL);
		// canvas.drawCircle(x, y, radius - 3, paint);
		paint.setColor(Color.BLACK);
		canvas.drawLine(x - radius, y, x + radius, y, paint);
		canvas.drawLine(x, y - radius, x, y + radius, paint);
	}

	private void DrawWifiStationCircle(Canvas canvas,
			HashMap<String, String> circleMap) {
		if (circleMap != null) {
			try {
				float x = getXOnView(Float.valueOf(circleMap.get("x"))) * 28;
				float y = getYOnView(Float.valueOf(circleMap.get("y"))) * 28;
				float r = Float.valueOf(circleMap.get("r")) * 28;
				Paint paint = new Paint();
				paint.setStyle(Style.STROKE);
				paint.setColor(Color.RED);
				paint.setStrokeWidth(2);
				canvas.drawCircle(x, y, r, paint);
				paint.setColor(Color.MAGENTA);
				paint.setStyle(Style.FILL);
				canvas.drawCircle(x, y, 4, paint);
			} catch (Exception e) {
			}
		}
	}

	private void DrawWifiStationCircle(Canvas canvas) {
		if (resultMap != null) {
			HashMap<String, String> circle0 = resultMap.get("cicle1");
			HashMap<String, String> circle1 = resultMap.get("cicle2");
			HashMap<String, String> circle2 = resultMap.get("cicle3");
			DrawWifiStationCircle(canvas, circle0);
			DrawWifiStationCircle(canvas, circle1);
			DrawWifiStationCircle(canvas, circle2);
		}
	}

	private void DrawLocation(Canvas canvas) {
		if (locationlist != null) {
			int count = locationlist.size();
			if (count > 0) {
				Point p;
				Point p0;
				int i = 0;
				while (i < count) {
					p = getPointOnView(locationlist.get(i));
					if (i == 0) {
						DrawStartPoint(canvas, p.x, p.y, radius);
					} else {
						p0 = getPointOnView(locationlist.get(i - 1));
						if (i + 1 == count) {
							DrawLastPoint(canvas, p.x, p.y, radius);
							DrawLine(canvas, p0.x, p0.y, p.x, p.y);
						} else {
							DrawPoint(canvas, p.x, p.y, radius);
							DrawLine(canvas, p0.x, p0.y, p.x, p.y);
						}
					}
					++i;
				}
			}
		}
		DrawWifiStationCircle(canvas);
	}

	@Override
	protected void onMeasure(int widthMeasureSpec, int heightMeasureSpec) {
		int width = MeasureSpec.getSize(widthMeasureSpec);
		int height = MeasureSpec.getSize(heightMeasureSpec);
		if (onMeasureWidth == null) {
			onMeasureWidth = width;
		}
		if (onMeasureHeight == null) {
			onMeasureHeight = height;
		}
		super.onMeasure(widthMeasureSpec, heightMeasureSpec);
	}

	@Override
	protected void onDraw(Canvas canvas) {
		super.onDraw(canvas);
		DrawLocation(canvas);
	}
}