ALTER TABLE `counter_settlement` ADD `settlement_amount` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `paid_amount`; 
ALTER TABLE `counter_settlement` ADD `pending_previous_amount` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `settlement_amount`; 

CREATE TABLE `pilot_buzz` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pilot_id` int(11) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `pilot_buzz` ADD `merchant_id` INT(11) NOT NULL AFTER `pilot_id`; 

CREATE TABLE `order_removed_products` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT ,
  `merchant_id` INT(11) NOT NULL ,
  `order_id` INT(11) NOT NULL , 
  `product_id` INT(11) NOT NULL , 
  `order_count` INT(11) NOT NULL ,
  `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
  `created_by` INT(11) NULL DEFAULT NULL , 
  PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

ALTER TABLE `order_removed_products` ADD `price` DECIMAL(10,2) NOT NULL AFTER `order_count`; 

ALTER TABLE `orders` ADD `cancelled_role_name` VARCHAR(50) NULL DEFAULT NULL AFTER `closed_by`, ADD `cancelled_by_name` VARCHAR(50) NULL DEFAULT NULL AFTER `cancelled_role_name`;

CREATE TABLE `merchant_printers` (
                                     `ID` INT(11) NOT NULL AUTO_INCREMENT ,
                                     `merchant_id` INT(11) NOT NULL ,
                                     `printer_real_name` VARCHAR(255) NOT NULL ,
                                     `printer_alias_name` VARCHAR(255) NOT NULL ,
                                     `paper_size` VARCHAR(100) NOT NULL ,
                                     `created_by` INT(11) NOT NULL ,
                                     `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
                                     `updated_by` INT(11) NULL DEFAULT NULL ,
                                     `updated_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
                                     PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

ALTER TABLE `food_categeries` ADD `category_printer` INT(11) NULL DEFAULT NULL AFTER `reg_date`;

ALTER TABLE `merchant` ADD `reverse_buzz` INT(3) NOT NULL DEFAULT '1' COMMENT '1. Enabled , 2.Disabled' AFTER `cancel_with_otp`;

CREATE TABLE `parking_token` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT ,
  `merchant_id` INT(11) NOT NULL ,
  `token_number` INT(11) NOT NULL , 
  `download_qr` INT(11) NOT NULL , 
  `multi_qr` INT(11) NOT NULL ,
  `status` enum('0','1') NOT NULL COMMENT '0=inactive,1=active', 
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

ALTER TABLE `parking_token` ADD `qr_path` VARCHAR(100) NULL DEFAULT NULL AFTER `token_number`;

CREATE TABLE `allocate_parking_token` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT ,
  `merchant_id` INT(11) NOT NULL ,
  `valet_id` INT(11) NOT NULL ,
  `token_number` INT(11) NOT NULL ,
  `ticket_id` INT(11) NOT NULL ,
  `customer_name` varchar(100) NULL,
  `customer_mobile` varchar(20) NULL,
  `vehicle_number` varchar(10) NULL,
  `parking_pic` varchar(100) NULL,
  `recorded_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
  `from_time` INT(11) NULL , 
  `to_time` INT(11) NULL ,
  `ticket_status` enum('1') NOT NULL COMMENT '1=Submitted,2=Parked,3=On Fire,4=Released', 
  PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

ALTER TABLE `allocate_parking_token` CHANGE `ticket_status` `ticket_status` INT(10) NOT NULL COMMENT '1=Submitted,2=Parked,3=On Fire,4=Released';
ALTER TABLE `allocate_parking_token` CHANGE `from_time` `from_time` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, CHANGE `to_time` `to_time` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
ALTER TABLE `allocate_parking_token` ADD `latitude` VARCHAR(100) NULL DEFAULT NULL AFTER `parking_pic`, ADD `longitude` VARCHAR(100) NULL DEFAULT NULL AFTER `latitude`;
ALTER TABLE `allocate_parking_token` ADD `get_time` VARCHAR(100) NULL DEFAULT NULL AFTER `to_time`, ADD `rating` VARCHAR(10) NULL DEFAULT NULL AFTER `get_time`;


CREATE TABLE `valet_notifications` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(30) NOT NULL,
  `valet_id` varchar(30) NOT NULL,
  `token_number` varchar(30) NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `seen` text NOT NULL,
  `reg_date` varchar(20) NOT NULL,
  `mod_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`ID`)
  ) ENGINE=InnoDB;

CREATE TABLE `valet_demo_requests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `business_name` varchar(100) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `pincode` text NOT NULL,
  `mobile_number` text NOT NULL,
  `alt_mobile_number` text NOT NULL,
  `lat` text NOT NULL,
  `long` text NOT NULL,
  `reg_date` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB;
ALTER TABLE `allocate_parking_token` ADD `latitude` VARCHAR(100) NULL DEFAULT NULL AFTER `parking_pic`, ADD `longitude` VARCHAR(100) NULL DEFAULT NULL AFTER `latitude`;

ALTER TABLE `counter_settlement` ADD `merchant_id` INT(11) NOT NULL AFTER `ID`;
ALTER TABLE `counter_settlement` ADD `requested_by` INT(3) NOT NULL DEFAULT '1' COMMENT '1.Pilot , 2.Merchant' AFTER `created_by`;
ALTER TABLE `counter_settlement` CHANGE `pilot_id` `pilot_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `counter_settlement` ADD `pilot_cut_order_id` INT(11) NULL DEFAULT NULL AFTER `cut_order_id`, ADD `pilot_settled_amount` DECIMAL(10,2) NULL DEFAULT NULL AFTER `pilot_cut_order_id`;
ALTER TABLE `counter_settlement` ADD `remarks` TEXT NULL AFTER `pilot_settled_amount`;
ALTER TABLE `orders` CHANGE `reg_date` `reg_date` DATETIME NOT NULL;

ALTER TABLE `merchant_printers` ADD `default_printer` INT(3) NULL DEFAULT '2' COMMENT '1.Yes , 2. No' AFTER `paper_size`;

ALTER TABLE `merchant_coupon` ADD `coupon_for` VARCHAR(100) NOT NULL DEFAULT 'DI' AFTER `mod_date`;  

CREATE TABLE `partner_with_us` (
  `id` INT(11) NOT NULL AUTO_INCREMENT , 
  `business_type` INT(3) NOT NULL , 
  `business_name` VARCHAR(255) NOT NULL , 
  `contact_person` VARCHAR(100) NULL DEFAULT NULL , 
  `mobile_number` VARCHAR(30) NULL DEFAULT NULL , 
  `email_id` VARCHAR(100) NULL DEFAULT NULL , 
  `location_city` VARCHAR(100) NULL DEFAULT NULL , 
  PRIMARY KEY (`id`)
  ) ENGINE = InnoDB; 

