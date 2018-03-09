<?php
/**
 * @author binzhao
 */


class CDAOADMIN_PRIV extends CCDAOSYS_ADMIN_PRIV 
{
	var $_table = 'SYS_ADMIN_PRIV';
	
	public function getRolesBySubsId($subsId = 0){
		if(!$subsId) return false;
		$sql = "SELECT OBJ_CODE,OBJ_ID FROM {$this->_table} WHERE SUBS_ID={$subsId} AND OBJ_TYPE='R'";
		$result = $this->DB()->getAll($sql);
		return $result[0];
	}
	
	public function getRoleInfoBySubsId($subsId = 0){
		if(!$subsId) return false;
		$sql = "SELECT ROLE_CODE,ROLE_NAME FROM SYS_ADMIN_PRIV ROL LEFT JOIN SYS_ROLE SYROL ON ROL.OBJ_CODE = SYROL.ROLE_CODE
 				WHERE SUBS_ID={$subsId} AND OBJ_TYPE='R'";
		$result = $this->DB()->getAll($sql);
		return $result[0];
	}
	
	public function DeleteRoleBySubsId($subsId = 0){
		if(!$subsId) return false;
		$sql = "DELETE FROM {$this->_table} WHERE SUBS_ID={$subsId} AND OBJ_TYPE='R'";
		return $this->DB()->Execute($sql);
	}
	
}