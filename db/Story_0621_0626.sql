--
--Ares
--2016/06/21
CREATE TABLE `delivery_program` (
`id`  int(11) NOT NULL ,
`program_name`  varchar(45) NOT NULL ,
`pay_type`  varchar(45) NOT NULL ,
`delivery_type`  int(11) NOT NULL ,
PRIMARY KEY (`id`)
)
;

ALTER TABLE `delivery_program`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT FIRST ,
AUTO_INCREMENT=13;

--
--Ares
--2016/06/23
CREATE TABLE `product_has_delivery` (
`id`  int(11) NOT NULL ,
`product_id`  int(25) NOT NULL ,
`delivery_id`  int(25) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=60
ROW_FORMAT=COMPACT
;

--
--Ares
--2016/06/24
ALTER TABLE `delivery_program`
ADD COLUMN `global`  int(11) NOT NULL AFTER `delivery_type`;

--
--Ares
--2016/06/28
ALTER TABLE `product_has_delivery`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT FIRST ;
