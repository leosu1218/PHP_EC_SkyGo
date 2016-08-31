--
-- 2016-05-18
-- Ares
CREATE TABLE `sub_three_category_tag` (
`id`  int(15) NOT NULL ,
`name`  varchar(45) NOT NULL ,
`mct_id`  int(15) NOT NULL AUTO_INCREMENT ,
PRIMARY KEY (`id`),
CONSTRAINT `sub_three_mct_id` FOREIGN KEY (`mct_id`) REFERENCES `main_category_tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
INDEX `mct_id_idx` (`mct_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=16
ROW_FORMAT=COMPACT
;

--
-- 2016/05/20
-- Ares
ALTER TABLE `wholesale_product`
ADD COLUMN `cost_price`  float NOT NULL AFTER `suggest_price`,
ADD COLUMN `propose_price`  float NOT NULL AFTER `cost_price`;

--
--2016/05/26
--Ares
ALTER TABLE `wholesale_product`
MODIFY COLUMN `ready_time`  datetime NULL COMMENT '上架日期' AFTER `id`,
MODIFY COLUMN `removed_time`  datetime NULL COMMENT '下架日期' AFTER `ready_time`;

