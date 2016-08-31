--
--Ares
--2016/07/15
ALTER TABLE `order_has_spec`
ADD COLUMN `status`  int(11) NOT NULL DEFAULT 0 AFTER `activity_id`,
ADD COLUMN `delivery_datetime`  datetime NULL DEFAULT NULL AFTER `status`,
ADD COLUMN `product_number`  int(45) NULL DEFAULT NULL AFTER `delivery_datetime`;

ALTER TABLE `unified_order` DROP FOREIGN KEY `uo_has_fare_id`;

ALTER TABLE `order_has_spec`
MODIFY COLUMN `product_number`  varchar(45) NULL DEFAULT NULL AFTER `delivery_datetime`;
