<?php
	require_once('controller/common_func.php');
	require_once('controller/order_controller.php');
	require_once 'controller/facebook.php';

//
//		function get_rand_pw(){
//			$pw="";
//			while(strlen($pw)<6){
//			  switch(rand(1,2)){
//			    case 1:
//			      $pw=$pw.chr(rand(97,122));
//			      break;
//			    case 2:
//			      $pw=$pw.chr(rand(48,57));
//			      break;
//			  }
//			}
//			return $pw;
//		}
//
//		for($i=0;$i<10;$i++){
//			$coupon=substr(md5(get_rand_pw()),0,10);
////			echo $coupon."-".strlen($coupon)."<br>";
//		}
//
//		for($i=0;$i<=122;$i++){
////			echo get_rand_pw()."<br>";
//		}

	function get_coupon_no(){
		$coupon_no="";
		while(strlen($coupon_no)<6){
		  switch(rand(2,2)){
		    case 1:
		      $coupon_no.=chr(rand(97,122));
		      break;
		    case 2:
		      $$coupon_no.=chr(rand(48,57));
		      break;
		  }
		}
		return $coupon_no;
	}

	echo get_coupon_no();

?>