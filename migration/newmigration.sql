CREATE TABLE `articles` (
    `ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key' ,
    `title` VARCHAR(255) NOT NULL , `image` VARCHAR(255) NOT NULL ,
    `content` TEXT NOT NULL , `status` INT(3) NOT NULL DEFAULT '1' , 
    `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `created_by` INT(11) NULL DEFAULT NULL , 
    `updated_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `updated_by` INT(11) NULL DEFAULT NULL , PRIMARY KEY (`ID`)
) ENGINE = InnoDB; 


CREATE TABLE `food_shorts` (
    `ID` INT(11) NOT NULL AUTO_INCREMENT ,
    `title` VARCHAR(255) NOT NULL , 
    `content` TEXT NOT NULL , 
    `status` INT(3) NOT NULL , 
    `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `created_by` INT(11) NULL DEFAULT NULL , 
    `updated_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `updated_by` INT(11) NULL DEFAULT NULL , 
    PRIMARY KEY (`ID`)
) ENGINE = InnoDB; 


CREATE TABLE `food_shorts_images` (
    `ID` INT(11) NOT NULL AUTO_INCREMENT , 
    `food_short_id` INT(11) NOT NULL , 
    `image` VARCHAR(255) NOT NULL , PRIMARY KEY (`ID`)
) ENGINE = InnoDB; 

CREATE TABLE `client` ( 
    `ID` INT(11) NOT NULL AUTO_INCREMENT , 
    `merchant_id` INT(11) NOT NULL , 
    `subscription_amount` DECIMAL(10,2) NOT NULL , 
    `subscription_start_date` DATE NOT NULL , 
    `executive_details` VARCHAR(255) NULL DEFAULT NULL , 
    `payment_status` INT(3) NOT NULL , 
    `created_by` INT(11) NULL DEFAULT NULL , 
    `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `updated_by` INT(11) NULL DEFAULT NULL , 
    `updated_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    PRIMARY KEY (`ID`)
) ENGINE = InnoDB; 

CREATE TABLE `merchant_paytype_services` (
    `ID` INT NOT NULL AUTO_INCREMENT , 
    `merchant_id` INT(11) NOT NULL , 
    `paytype_id` INT(11) NOT NULL , 
    `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    PRIMARY KEY (`ID`)
) ENGINE = InnoDB; 

ALTER TABLE `merchant_paytype_services` ADD `service_type_id` INT(11) NOT NULL AFTER `paytype_id`; 

ALTER TABLE `partner_with_us` ADD `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `location_city`; 

ALTER TABLE `merchant` ADD `multi_kot_config` INT(3) NULL DEFAULT NULL COMMENT '1.Enable,2.Disable' AFTER `reverse_buzz`; 