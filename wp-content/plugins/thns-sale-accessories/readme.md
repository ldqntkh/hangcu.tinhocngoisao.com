
##-------campaign--------
CREATE TABLE `gvn_sas_campaign` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` blob,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `user_create` varchar(100) DEFAULT NULL,
  `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
);

##-------group_discount_product
CREATE TABLE `gearvn_shop_1`.`gvn_sas_group_discount_product` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `campaign_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `display_name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(250) NULL,
  `discount_type` VARCHAR(50) NULL,
  `discount_value` INT NULL,
  `user_create` VARCHAR(100) NULL,
  `display_index` INT NULL,
  `active` TINYINT NULL,
  `create_at` DATETIME NULL DEFAULT 'CURRENT_TIMESTAMP',
  `update_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  INDEX `campaign_id_idx` (`campaign_id` ASC) VISIBLE,
  CONSTRAINT `group_campaign_id`
    FOREIGN KEY (`campaign_id`)
    REFERENCES `gearvn_shop_1`.`gvn_campaign` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);

##-------group_assigned_product
CREATE TABLE `gvn_sas_product_assigned_group` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_create` varchar(100) DEFAULT NULL,
  `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `product_group_id_idx` (`group_id`),
  CONSTRAINT `product_group_id` FOREIGN KEY (`group_id`) REFERENCES `gvn_sas_group_discount_product` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

##------product_assigned_campaign
CREATE TABLE `gearvn_shop_1`.`gvn_sas_product_assigned_campaign` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `campaign_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `user_create` VARCHAR(100) NOT NULL,
  `create_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  INDEX `product_campaign_id_idx` (`campaign_id` ASC) VISIBLE,
  CONSTRAINT `product_campaign_id`
    FOREIGN KEY (`campaign_id`)
    REFERENCES `gearvn_shop_1`.`gvn_sas_campaign` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
