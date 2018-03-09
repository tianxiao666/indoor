package com.iscreate.mobile.indoormap.activity;

import java.util.List;

import android.content.Context;
import android.os.Bundle;
import android.telephony.NeighboringCellInfo;
import android.telephony.PhoneStateListener;
import android.telephony.SignalStrength;
import android.telephony.TelephonyManager;
import android.telephony.gsm.GsmCellLocation;

import com.iscreate.mobile.utils.Signal;

public abstract class cellDetecterActivity extends stepDetecterByNetActivity {
	/**
	 * TelephonyManager
	 */
	private TelephonyManager telephonyManager = null;
	/**
	 * the cell signal strength
	 */
	private int signalLevel = 0;

	/**
	 * a listener to the phone state
	 */
	private class MyPhoneStateListener extends PhoneStateListener {
		public void onSignalStrengthsChanged(SignalStrength signalStrength) {
			super.onSignalStrengthsChanged(signalStrength);
			if (signalStrength.isGsm()) {
				if (signalStrength.getGsmSignalStrength() != 99) {
					signalLevel = Signal.todBm(signalStrength
							.getGsmSignalStrength());
				} else {
					signalLevel = signalStrength.getGsmSignalStrength();
				}
			} else {
				signalLevel = signalStrength.getCdmaDbm();
			}
		}
	}

	/**
	 * if there is a sim card ,then listen to the signal strength
	 */
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		telephonyManager = (TelephonyManager) getSystemService(Context.TELEPHONY_SERVICE);
		if (telephonyManager.getSimState() != TelephonyManager.SIM_STATE_ABSENT) {
			telephonyManager.listen(new MyPhoneStateListener(),
					PhoneStateListener.LISTEN_SIGNAL_STRENGTHS);
			telephonyManager.listen(new MyPhoneStateListener(),
					PhoneStateListener.LISTEN_CELL_LOCATION);
		}
	}

	/**
	 * get the signal strength
	 */
	public int getSignalLevel() {
		return (signalLevel);
	}

	/**
	 * get GsmCellLocation information
	 */
	public GsmCellLocation getGsmCellLocation() {
		return ((GsmCellLocation) telephonyManager.getCellLocation());
	}

	/**
	 * get NeighboringCellInfo,this one is not working
	 */
	public List<NeighboringCellInfo> getNeighboringCellInfo() {
		return (telephonyManager.getNeighboringCellInfo());
	}
}