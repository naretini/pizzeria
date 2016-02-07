<?php
require_once '../classes/require.inc.php';

$newStatus = $_REQUEST['status'];
$idOrder = $_REQUEST['id'];
// die($newStatus);

if(Order::setOrderStatus($idOrder, $newStatus){
	header("Location: admin_orders.php");
	exit;
}else{
	die("Error updating status to $newStatus for order id: $idOrder");
}