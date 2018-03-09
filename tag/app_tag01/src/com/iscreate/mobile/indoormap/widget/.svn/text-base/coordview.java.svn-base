package com.iscreate.mobile.indoormap.widget;

import java.util.ArrayList;
import java.util.List;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Paint.Style;
import android.util.AttributeSet;
import android.widget.ImageView;
import android.widget.LinearLayout;

public class coordview extends ImageView {

	private List<Float> data = new ArrayList<Float>();
	private float gap = 5f;
	private float time = 30;

	public coordview(Context context) {
		super(context);
	}

	public coordview(Context context, AttributeSet attrs) {
		super(context, attrs);
	}

	public coordview(Context context, AttributeSet attrs, int defStyle) {
		super(context, attrs, defStyle);
	}

	private void drawData(Canvas canvas) {
		Paint paint = new Paint();
		paint.setStyle(Style.FILL);
		paint.setColor(Color.GREEN);
		int h = getHeight();
		int w = getWidth();
		float y = (float) h / 2;
		canvas.drawLine(0, y, w, y, paint);
		paint.setColor(Color.MAGENTA);
		canvas.drawLine(0, y - 9.80665f * time, w, y - 9.7803185f * time, paint);
		if (data != null) {
			paint.setStyle(Style.FILL);
			paint.setColor(Color.RED);
			int i = 0;
			int count = data.size();
			while (i < count) {
				if ((int) ((i + 1) * gap) >= w) {
					LinearLayout.LayoutParams params = (LinearLayout.LayoutParams) getLayoutParams();
					params.leftMargin = params.leftMargin + w
							- (int) ((i + 1) * gap);
					setLayoutParams(params);
				}
				canvas.drawCircle((i + 1) * gap, y - (data.get(i) * time),
						gap / 2, paint);
				++i;
			}
		}
	}

	public void appendData(Float d) {
		data.add(d);
		invalidate();
	}

	@Override
	protected void onDraw(Canvas canvas) {
		super.onDraw(canvas);
		drawData(canvas);
	}
}