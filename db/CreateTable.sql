-- MySQL Script generated by MySQL Workbench
-- 02/24/17 10:04:50
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema tmt
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `tmt` ;

-- -----------------------------------------------------
-- Schema tmt
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `tmt` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ;
USE `tmt` ;

-- -----------------------------------------------------
-- Table `tmt`.`SUT`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tmt`.`SUT` ;

CREATE TABLE IF NOT EXISTS `tmt`.`SUT` (
  `SUT_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `ActiveDateFrom` DATETIME NOT NULL,
  `ActiveDateTo` DATETIME NULL,
  `LastUpdate` DATETIME NULL,
  `ProjectDescription` VARCHAR(1023) CHARACTER SET 'utf8' NULL,
  `TestingDescription` VARCHAR(1023) NULL,
  `HwRequirements` VARCHAR(255) NULL,
  `SwRequirements` VARCHAR(255) NULL,
  `TestEstimatedDate` DATE NULL,
  `Note` VARCHAR(255) NULL,
  PRIMARY KEY (`SUT_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tmt`.`TestRequirement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tmt`.`TestRequirement` ;

CREATE TABLE IF NOT EXISTS `tmt`.`TestRequirement` (
  `TestRequirement_id` INT UNSIGNED NOT NULL,
  `SUT_id` INT UNSIGNED NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `ActiveDateFrom` DATETIME NOT NULL,
  `ActiveDateTo` DATETIME NULL,
  `LastUpdate` DATETIME NULL,
  `CoverageCriteria` VARCHAR(45) NULL,
  `RequirementDescription` VARCHAR(1023) NULL,
  PRIMARY KEY (`TestRequirement_id`),
  INDEX `fk_Test Requerements_SUT_idx` (`SUT_id` ASC),
  CONSTRAINT `fk_Test Requerements_SUT`
    FOREIGN KEY (`SUT_id`)
    REFERENCES `tmt`.`SUT` (`SUT_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tmt`.`TestExecutor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tmt`.`TestExecutor` ;

CREATE TABLE IF NOT EXISTS `tmt`.`TestExecutor` (
  `TestExecutor_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `IsPerson` TINYINT(1) NULL,
  `RemoteTool` VARCHAR(45) NULL,
  `TestExecutorcol` VARCHAR(45) NULL,
  PRIMARY KEY (`TestExecutor_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tmt`.`TestRun`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tmt`.`TestRun` ;

CREATE TABLE IF NOT EXISTS `tmt`.`TestRun` (
  `TestRun_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `SUT_id` INT UNSIGNED NOT NULL,
  `TestExecutor_id` INT UNSIGNED NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `ActiveDateFrom` DATETIME NOT NULL,
  `ActiveDateTo` DATETIME NULL,
  `LastUpdate` DATETIME NULL,
  `Author` VARCHAR(45) NULL,
  `TestRunDescription` VARCHAR(1023) NULL,
  PRIMARY KEY (`TestRun_id`),
  INDEX `fk_Test Run_SUT1_idx` (`SUT_id` ASC),
  INDEX `fk_Test Run_Test Executor1_idx` (`TestExecutor_id` ASC),
  CONSTRAINT `fk_Test Run_SUT1`
    FOREIGN KEY (`SUT_id`)
    REFERENCES `tmt`.`SUT` (`SUT_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Test Run_Test Executor1`
    FOREIGN KEY (`TestExecutor_id`)
    REFERENCES `tmt`.`TestExecutor` (`TestExecutor_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tmt`.`TestSuite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tmt`.`TestSuite` ;

CREATE TABLE IF NOT EXISTS `tmt`.`TestSuite` (
  `TestSuite_id` INT UNSIGNED NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `ActiveDateFrom` DATETIME NOT NULL,
  `ActiveDateTo` DATETIME NULL,
  `LastUpdate` DATETIME NULL,
  `TestSuiteGoals` VARCHAR(1023) NULL,
  `TestSuiteVersion` VARCHAR(45) NULL,
  `TestSuiteDocumentation` VARCHAR(1023) NULL,
  PRIMARY KEY (`TestSuite_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tmt`.`TestCase`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tmt`.`TestCase` ;

CREATE TABLE IF NOT EXISTS `tmt`.`TestCase` (
  `TestCase_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `TestSuite_id` INT UNSIGNED NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `ActiveDateFrom` DATETIME NOT NULL,
  `ActiveDateTo` DATETIME NULL,
  `LastUpdate` DATETIME NULL,
  `IsManual` TINYINT(1) NULL,
  `TestCasePrefixes` VARCHAR(1023) NULL,
  `TestCaseSuffixes` VARCHAR(1023) NULL,
  `SourceCode` VARCHAR(255) NULL,
  `Author` VARCHAR(45) NULL,
  `TestCaseDescription` VARCHAR(1023) NULL,
  PRIMARY KEY (`TestCase_id`),
  INDEX `fk_TestCase_TestSuite1_idx` (`TestSuite_id` ASC),
  CONSTRAINT `fk_TestCase_TestSuite1`
    FOREIGN KEY (`TestSuite_id`)
    REFERENCES `tmt`.`TestSuite` (`TestSuite_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tmt`.`TestCase_has_TestRun`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tmt`.`TestCase_has_TestRun` ;

CREATE TABLE IF NOT EXISTS `tmt`.`TestCase_has_TestRun` (
  `TestCase_id` INT UNSIGNED NOT NULL,
  `TestRun_id` INT UNSIGNED NOT NULL,
  `Pass` TINYINT(1) NULL,
  PRIMARY KEY (`TestCase_id`, `TestRun_id`),
  INDEX `fk_Test Case_has_Test Run_Test Run1_idx` (`TestRun_id` ASC),
  INDEX `fk_Test Case_has_Test Run_Test Case1_idx` (`TestCase_id` ASC),
  CONSTRAINT `fk_Test Case_has_Test Run_Test Case1`
    FOREIGN KEY (`TestCase_id`)
    REFERENCES `tmt`.`TestCase` (`TestCase_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Test Case_has_Test Run_Test Run1`
    FOREIGN KEY (`TestRun_id`)
    REFERENCES `tmt`.`TestRun` (`TestRun_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tmt`.`TestCase_has_TestRequirement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tmt`.`TestCase_has_TestRequirement` ;

CREATE TABLE IF NOT EXISTS `tmt`.`TestCase_has_TestRequirement` (
  `TestCase_TestCase_id` INT UNSIGNED NOT NULL,
  `TestRequirement_TestRequirement_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`TestCase_TestCase_id`, `TestRequirement_TestRequirement_id`),
  INDEX `fk_TestCase_has_TestRequirement_TestRequirement1_idx` (`TestRequirement_TestRequirement_id` ASC),
  INDEX `fk_TestCase_has_TestRequirement_TestCase1_idx` (`TestCase_TestCase_id` ASC),
  CONSTRAINT `fk_TestCase_has_TestRequirement_TestCase1`
    FOREIGN KEY (`TestCase_TestCase_id`)
    REFERENCES `tmt`.`TestCase` (`TestCase_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TestCase_has_TestRequirement_TestRequirement1`
    FOREIGN KEY (`TestRequirement_TestRequirement_id`)
    REFERENCES `tmt`.`TestRequirement` (`TestRequirement_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tmt`.`TestSuite_has_TestRun`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tmt`.`TestSuite_has_TestRun` ;

CREATE TABLE IF NOT EXISTS `tmt`.`TestSuite_has_TestRun` (
  `TestSuite_TestSuite_id` INT UNSIGNED NOT NULL,
  `TestRun_TestRun_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`TestSuite_TestSuite_id`, `TestRun_TestRun_id`),
  INDEX `fk_TestSuite_has_TestRun_TestRun1_idx` (`TestRun_TestRun_id` ASC),
  INDEX `fk_TestSuite_has_TestRun_TestSuite1_idx` (`TestSuite_TestSuite_id` ASC),
  CONSTRAINT `fk_TestSuite_has_TestRun_TestSuite1`
    FOREIGN KEY (`TestSuite_TestSuite_id`)
    REFERENCES `tmt`.`TestSuite` (`TestSuite_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TestSuite_has_TestRun_TestRun1`
    FOREIGN KEY (`TestRun_TestRun_id`)
    REFERENCES `tmt`.`TestRun` (`TestRun_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
