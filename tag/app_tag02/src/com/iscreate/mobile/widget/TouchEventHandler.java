package com.iscreate.mobile.widget;

import android.view.MotionEvent;

import com.iscreate.mobile.utils.utils;

public abstract class TouchEventHandler {
	/**
	 * the time of double click
	 */
	private final int DOUBLE_CLICK_TIME_GAP = 350;
	/**
	 * the distance of double click
	 */
	private final int DOUBLE_CLICK_DISTANCE_GAP = 40;
	/**
	 * the sense difference between the perimeter of all pointers ,if larger
	 * than this value will call zoom
	 */
	private final float ZOOM_POINTER_PERIMETER_DISTANCE_GAP = 10;
	/**
	 * two finger
	 */
	private final int ZOOM_POINTER_COUNT = 2;

	/**
	 * drag start x-coordinate
	 */
	private Float dragStartX = null;
	/**
	 * drag start y-coordinate
	 */
	private Float dragStartY = null;
	/**
	 * the time of last click event
	 */
	private long lastClickTime = 0;
	/**
	 * the x-coordinate of last click event
	 */
	private float lastClickX = 0;
	/**
	 * the y-coordinate of last click event
	 */
	private float lastClickY = 0;
	/**
	 * the pointer perimeter of all pointers
	 */
	private double PointerPerimeter = -1;
	/**
	 * the center x-coordinate during zoom
	 */
	private Float zoomHoldX = null;
	/**
	 * the center y-coordinate during zoom
	 */
	private Float zoomHoldY = null;

	public abstract void onDoubleClick(float x, float y);

	public abstract void onDrag(float offsetx, float offsety);

	public abstract void onZoom(float centerx, float centery, float holdx,
			float holdy, float scale);

	public void updateTouchEvent(MotionEvent event) {
		int action = event.getActionMasked();
		switch (action) {
		case MotionEvent.ACTION_DOWN:
			onActionDown(event);
			break;
		case MotionEvent.ACTION_UP:
			onActionUp(event);
			break;
		case MotionEvent.ACTION_MOVE:
			onActionMove(event);
			break;
		case MotionEvent.ACTION_POINTER_DOWN:
			onActionPointerDown(event);
			break;
		case MotionEvent.ACTION_POINTER_UP:
			onActionPointerUp(event);
			break;
		}
	}

	/**
	 * mark the start event x,y
	 * 
	 * @param event
	 *            touch event while ACTION_DOWN
	 */
	private void onActionDown(MotionEvent event) {
		PointerPerimeter = -1;
		dragStartX = event.getX();
		dragStartY = event.getY();
		long thisClickTime = event.getEventTime();
		if (((thisClickTime - lastClickTime) < DOUBLE_CLICK_TIME_GAP)
				&& (Math.sqrt((dragStartY - lastClickY)
						* (dragStartY - lastClickY) + (dragStartX - lastClickX)
						* (dragStartX - lastClickX)) < DOUBLE_CLICK_DISTANCE_GAP)) {
			onDoubleClick(event.getX(), event.getY());
			lastClickTime = 0;
		} else {
			lastClickTime = thisClickTime;
			lastClickY = dragStartY;
			lastClickX = dragStartX;
		}
	}

	/**
	 * clear the variables
	 * 
	 * @param event
	 *            touch event while ACTION_UP
	 */
	private void onActionUp(MotionEvent event) {
		PointerPerimeter = -1;
		dragStartX = null;
		dragStartY = null;
		zoomHoldX = null;
		zoomHoldY = null;
	}

	/**
	 * calculate the translate value or zoom scale
	 * 
	 * @param event
	 *            touch event while ACTION_MOVE
	 */
	private void onActionMove(MotionEvent event) {
		int PointerCount = event.getPointerCount();
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
			onDrag(transX, transY);
		} else {
			double nowPointersRound = utils.getEventPointerPerimeter(event,
					false);
			if (Math.abs(PointerPerimeter - nowPointersRound) > ZOOM_POINTER_PERIMETER_DISTANCE_GAP
					* PointerCount) {
				float[] pointerCenter = utils.getEventPointerCenter(event);
				if (zoomHoldX == null) {
					zoomHoldX = pointerCenter[0];
					zoomHoldY = pointerCenter[1];
				}
				float scale = (float) (nowPointersRound / PointerPerimeter);
				onZoom(pointerCenter[0], pointerCenter[1], zoomHoldX,
						zoomHoldY, scale);
				PointerPerimeter = nowPointersRound;
			}
		}
	}

	/**
	 * recalculate the pointer perimeters
	 * 
	 * @param event
	 *            touch event while ACTION_POINTER_DOWN
	 */
	private void onActionPointerDown(MotionEvent event) {
		PointerPerimeter = utils.getEventPointerPerimeter(event, false);
	}

	/**
	 * clear the variables if pointer count is equal to
	 * ZOOM_POINTER_COUNT,otherwise recalculate the pointer perimeters
	 * 
	 * @param event
	 *            touch event while ACTION_POINTER_UP
	 */
	private void onActionPointerUp(MotionEvent event) {
		int PointerCount = event.getPointerCount();
		if (PointerCount == ZOOM_POINTER_COUNT) {
			PointerPerimeter = -1;
			dragStartX = null;
			dragStartY = null;
			zoomHoldX = null;
			zoomHoldY = null;
		} else {
			PointerPerimeter = utils.getEventPointerPerimeter(event, true);
		}
	}
}