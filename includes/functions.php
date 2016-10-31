<?php
function orderLinksByUrl($arr = array()) {
	$n_arr = array();
	if (empty($arr)) {
		$arr = array();
	}
	foreach ($arr as $k => $ar) {
		if (empty($n_arr[$ar['page_url']])) {
			$n_arr[$ar['page_url']] = array();
		}
		$n_arr[$ar['page_url']][] = $ar['issue_html'];
	}
	return $n_arr;
}
function magenet_in_array($v, $array = array()) {
	$return = false;
	foreach ($array as $k => $value) {
		if (substr($value, 0, 30) == $v) {
			$return = $k;
			break;
		}
	}
	return $return;
}
function magenetGetOrderByUrl($arrUrl = array(), $arrCurrent = array()) {
	$newArray = array();
	if (empty($arrUrl)) {
		$arrUrl = array();
	}
	if (!is_array($arrUrl)) {
		$arrUrl = array();
	}
	if (empty($arrCurrent)) {
		$arrCurrent = array();
	}
	if (!is_array($arrCurrent)) {
		$arrCurrent = array();
	}
	foreach ($arrCurrent as $kc => $vc) {
		$key_f = magenet_in_array($vc, $arrUrl);
		if ($key_f !== false) {
			$newArray[] = $arrUrl[$key_f];
			unset($arrUrl[$key_f]);
		}
	}
	foreach ($arrUrl as $v) {
		$newArray[] = $v;
	}
	return $newArray;
}	

?>