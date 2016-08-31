--
--Ares
--2016/06/07
ALTER TABLE `consumer_user`
ADD COLUMN `gender`  int(15) NOT NULL AFTER `create_datetime`,
ADD COLUMN `birthday`  date NULL AFTER `gender`,
ADD COLUMN `address`  varchar(45) NULL AFTER `birthday`;