/* 07/09/21 : Maintaining Active/Inactive status of daily finance application */
ALTER TABLE `df_vendor_application` ADD `status` INT(3) NOT NULL DEFAULT '1' AFTER `transaction_id`; 

CREATE TABLE `df_budget` (
     `ID` INT NOT NULL AUTO_INCREMENT ,
     `budget` DECIMAL(10,2) NOT NULL ,
     `investment` DECIMAL(10,2) NOT NULL ,
     `withdraw` DECIMAL(10,2) NOT NULL DEFAULT '0.00' ,
     `roi` DECIMAL(10,2) NOT NULL DEFAULT '0.00' ,
     `reg_date` DATETIME NOT NULL ,
      PRIMARY KEY (`ID`)
) ENGINE = InnoDB; 


CREATE TABLE `tbl_super_admin_details` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `super_admin_id` int(11) NOT NULL,
 `subscription_date` date NOT NULL,
 `expiry_date` date NOT NULL,
 `subscription_duration` int(11) NOT NULL,
 `no_of_managers` int(11) NOT NULL COMMENT 'Active Only',
 `no_of_executives` int(11) NOT NULL COMMENT 'Active Only',
 `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `tbl_super_admin_subscription_module` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `super_admin_id` int(11) NOT NULL,
 `module_id` int(11) NOT NULL,
 `status` int(11) NOT NULL,
 `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `tbl_modules` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `module_name` varchar(100) NOT NULL,
 `status` int(11) NOT NULL,
 `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
