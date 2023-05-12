<?php
namespace app\helpers;

use yii;
class MyConst 
{
	/* ROLE CONSTANTS */
	const ROLE_SUPER_ADMIN = '1';
	const ROLE_COACH = '2';
	const ROLE_USER = '3';
	const ROLE_INSTITUTION = '4';
	
	/* STATUS CONSTANTS */
	const _ACTIVE = 'ACTIVE';
	const _INACTIVE = 'INACTIVE';
	const TYPE_ACTIVE = '1';
	const TYPE_INACTIVE = '2';
	
	const ROOM_RESERVATION_TYPES = ['4' => 'Hotels','6' => 'Service Apartments' , '7' => 'P.G' ,'8' => 'Resorts'];
	const TASTE_CATEGORIES = ['1' => 'Spicy','2' => 'Sweet','3' => 'Normal'];
	const PAYMENT_METHODS = ['1'=>'Cash On Dine','2'=>'Online Payment','3'=>'UPI Scanner','4'=>'Card Swipe'];

	const _NEW = '42';
	const _COMPLETED = '33';
	const _REJECTED = '17';

	const _ORDER_SERVICE_TYPE = ['DI' => 'Dine In','PA' => 'Parcels', 'SE' => 'Self Pickup', 'DE' => 'Delivery'];

	const BUSINESS_TYPE = ['1' => 'Investment','2' => 'Delivery and Self-Pickup','3' => 'POS and Inventory-Management',
	'4' => 'Table reservation','5' => 'Hotel Reservation','6' => 'Flats Collaboration','7' => 'Water Services','8' => 'Home Chef',
	'9' => 'Personalized Chef','10' => 'Food items subscription'];
}

?>
