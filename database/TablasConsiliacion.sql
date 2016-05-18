
CREATE TABLE `p_controlfacturas`.`t_catalogo_tipo_transaccion` (
  `idctrans` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(128) NULL,
  `active` INT NULL,
  PRIMARY KEY (`idctrans`),
  UNIQUE INDEX `idctrans_UNIQUE` (`idctrans` ASC));

CREATE TABLE `p_controlfacturas`.`t_conciliacion` (
  `idconciliacion` INT NOT NULL AUTO_INCREMENT,
  `dia` VARCHAR(12) NULL,
  `concepto` VARCHAR(255) NULL,
  `cargo` FLOAT NULL,
  `abono` FLOAT NULL,
  `saldo` FLOAT NULL,
  `comentario` VARCHAR(255) NULL,
  `idcuenta` INT NULL,
  `flag` INT NULL,
  PRIMARY KEY (`idconciliacion`),
  UNIQUE INDEX `idconciliacion_UNIQUE` (`idconciliacion` ASC));

CREATE TABLE `p_controlfacturas`.`t_conciliacion_referencias` (
  `idconcilref` INT NOT NULL AUTO_INCREMENT,
  `idfactura` INT NULL,
  `comentario` VARCHAR(255) NULL,
  PRIMARY KEY (`idconcilref`),
  UNIQUE INDEX `idconcilref_UNIQUE` (`idconcilref` ASC));

CREATE TABLE `p_controlfacturas`.`t_conciliacion_attachments` (
  `idconcilattach` INT NOT NULL AUTO_INCREMENT,
  `filepath` VARCHAR(255) NULL,
  PRIMARY KEY (`idconcilattach`),
  UNIQUE INDEX `idconcilattach_UNIQUE` (`idconcilattach` ASC));

ALTER TABLE `p_controlfacturas`.`t_conciliacion` 
ADD COLUMN `mes` INT NULL AFTER `flag`,
ADD COLUMN `anio` INT NULL AFTER `mes`;

CREATE TABLE `p_controlfacturas`.`t_cuentas_conciliacion` (
  `idcuenta` INT NOT NULL AUTO_INCREMENT,
  `cuenta` VARCHAR(20) NULL,
  `descripcion` VARCHAR(255) NULL,
  PRIMARY KEY (`idcuenta`),
  UNIQUE INDEX `idcuentacon_UNIQUE` (`idcuenta` ASC));

ALTER TABLE `p_controlfacturas`.`t_conciliacion` 
ADD COLUMN `idctrans` INT NULL AFTER `anio`;

ALTER TABLE `p_controlfacturas`.`t_conciliacion_referencias` 
DROP INDEX `idconcilref_UNIQUE` ;

ALTER TABLE `p_controlfacturas`.`t_conciliacion_referencias` 
ADD COLUMN `idconciliacion` INT NULL AFTER `comentario`;
