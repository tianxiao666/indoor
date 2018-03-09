package com.iscreate.mobile.indoormap.widget;

import android.content.Context;
import android.view.KeyEvent;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnKeyListener;
import android.view.View.OnTouchListener;
import android.widget.PopupWindow;

public class PopupNormalWindow extends PopupWindow {
	private boolean KEYCODE_BACK_OnTouch_Down = false;
	private boolean KEYCODE_BACK_OnKey_Down = false;

	public PopupNormalWindow(Context context) {
		super(context);
	}

	public PopupNormalWindow(View v, int w, int h) {
		super(v, w, h);
		if (v != null) {
			v.setFocusableInTouchMode(true);
			v.setOnTouchListener(popupViewOnTouch);
			v.setOnKeyListener(popupViewOnKey);
			setFocusable(true);
		}
	}

	private OnKeyListener popupViewOnKey = new OnKeyListener() {
		@Override
		public boolean onKey(View v, int keyCode, KeyEvent event) {
			if (keyCode == KeyEvent.KEYCODE_BACK) {
				int action = event.getAction();
				switch (action) {
				case KeyEvent.ACTION_DOWN:
					KEYCODE_BACK_OnKey_Down = true;
					break;
				case KeyEvent.ACTION_UP:
					if (KEYCODE_BACK_OnKey_Down) {
						dismiss();
						return (true);
					}
				}
			}
			return (false);
		}
	};

	private OnTouchListener popupViewOnTouch = new OnTouchListener() {
		@Override
		public boolean onTouch(View v, MotionEvent event) {
			int action = event.getAction();
			switch (action) {
			case KeyEvent.ACTION_DOWN:
				KEYCODE_BACK_OnTouch_Down = isOutOfView(getContentView(),
						event.getX(), event.getY());
				break;
			case KeyEvent.ACTION_UP:
				if (KEYCODE_BACK_OnTouch_Down) {
					if (isOutOfView(getContentView(), event.getX(),
							event.getY())) {
						dismiss();
						return (true);
					}
				}
			}
			return false;
		}
	};

	private boolean isOutOfView(View v, float x, float y) {
		return (v != null)
				&& ((x < 0) || (y < 0) || (x >= v.getWidth()) || (y >= v
						.getHeight()));
	}
}