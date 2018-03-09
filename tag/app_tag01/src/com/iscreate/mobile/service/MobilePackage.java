package com.iscreate.mobile.service;

/**
 * 包的域模�?
 * 
 * @author zoubing
 */
public class MobilePackage {
	private String content;
	private String result;
	private String action;
	private String fileData;
	public static final String RESULT_PACKAGESTR = "RESULT_PACKAGESTRING";
	public static final String RESULT_SUCCESS = "success";
	public static final String RESULT_FAILED = "failed";
	public static final String RESULT_TIMEOUT = "timeout";

	public MobilePackage() {
	}

	public String getContent() {
		return content;
	}

	public void setContent(String content) {
		this.content = content;
	}

	public String getResult() {
		return result;
	}

	public void setResult(String result) {
		this.result = result;
	}

	public String getAction() {
		return action;
	}

	public void setAction(String action) {
		this.action = action;
	}

	public String getFileData() {
		return fileData;
	}

	public void setFileData(String fileData) {
		this.fileData = fileData;
	}
}
