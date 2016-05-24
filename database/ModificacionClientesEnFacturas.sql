ALTER TABLE `p_controlfacturas`.`t_facturas` 
ADD COLUMN `customer_id` INT NULL AFTER `numeroorden`,
ADD COLUMN `customer_name`  VARCHAR(255)  NULL AFTER `customer_id`;
