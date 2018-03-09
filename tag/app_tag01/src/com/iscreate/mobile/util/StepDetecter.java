package com.iscreate.mobile.util;

public abstract class StepDetecter {
	private float[] lastAcc = new float[] { 0f, 0f, 0f };
	private static final int vhSize = 6;
	private double[] values_history = new double[vhSize];
	private int vhPointer = 0;
	private int step_timeout_ms = 666;
	private long last_step_ts = 0;
	private double peak = 0.5f;

	public abstract void onStep();

	public void setLastAcc(float[] acc) {
		System.arraycopy(acc, 0, lastAcc, 0, 3);
	}

	public void updateData() {
		long now_ms = System.currentTimeMillis();
		double lOld_z = lastAcc[2];
		addData(lOld_z);
		if ((now_ms - last_step_ts) > step_timeout_ms && checkForStep(peak)) {
			last_step_ts = now_ms;
			onStep();
		}
	}

	private void addData(double value) {
		values_history[vhPointer % vhSize] = value;
		vhPointer++;
		vhPointer = vhPointer % vhSize;
	}

	private boolean checkForStep(double peakSize) {
		// int lookahead = 5;
		// double diff = peakSize;
		int last = (vhPointer - 1 + vhSize) % vhSize;
		int loop = 0;
		for (int t = 1; t < vhSize; t++) {
			loop = (vhPointer - 1 + vhSize - t + vhSize) % vhSize;
			if ((values_history[loop] - values_history[last] > peakSize)) {
				return (true);
			}
		}
		return (false);
	}
}