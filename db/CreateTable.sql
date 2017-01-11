-- MySQL Script generated by MySQL Workbench
-- 01/11/17 11:18:34
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mydb` ;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`SUT`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`SUT` ;

CREATE TABLE IF NOT EXISTS `mydb`.`SUT` (
  `SUT_id` INT NOT NULL AUTO_INCREMENT,
  `StartDate` DATE NULL,
  `ProjectDescription` VARCHAR(255) CHARACTER SET 'utf8' NULL,
  `TestingDescription` VARCHAR(255) NULL,
  `HwRequirements` VARCHAR(45) NULL,
  `SwRequirements` VARCHAR(45) NULL,
  `TestEstimatedDate` DATE NULL,
  `Note` VARCHAR(255) NULL,
  PRIMARY KEY (`SUT_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TestRequirement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`TestRequirement` ;

CREATE TABLE IF NOT EXISTS `mydb`.`TestRequirement` (
  `TestRequirement_id` INT NOT NULL,
  `SUT_id` INT NOT NULL,
  `CoverageCriteria` VARCHAR(45) NULL,
  `RequirementDescription` VARCHAR(255) NULL,
  PRIMARY KEY (`TestRequirement_id`),
  INDEX `fk_Test Requerements_SUT_idx` (`SUT_id` ASC),
  CONSTRAINT `fk_Test Requerements_SUT`
    FOREIGN KEY (`SUT_id`)
    REFERENCES `mydb`.`SUT` (`SUT_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TestExecutor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`TestExecutor` ;

CREATE TABLE IF NOT EXISTS `mydb`.`TestExecutor` (
  `TestExecutor_id` INT NOT NULL AUTO_INCREMENT,
  `IsPerson` TINYINT(1) NULL,
  `RemoteTool` VARCHAR(45) NULL,
  PRIMARY KEY (`TestExecutor_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TestRun`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`TestRun` ;

CREATE TABLE IF NOT EXISTS `mydb`.`TestRun` (
  `TestRun_id` INT NOT NULL AUTO_INCREMENT,
  `SUT_id` INT NOT NULL,
  `TestExecutor_id` INT NOT NULL,
  `Author` VARCHAR(45) NULL,
  `StartDate` TIMESTAMP NULL,
  `EndDate` TIMESTAMP NULL,
  `TestRunDescription` VARCHAR(255) NULL,
  PRIMARY KEY (`TestRun_id`),
  INDEX `fk_Test Run_SUT1_idx` (`SUT_id` ASC),
  INDEX `fk_Test Run_Test Executor1_idx` (`TestExecutor_id` ASC),
  CONSTRAINT `fk_Test Run_SUT1`
    FOREIGN KEY (`SUT_id`)
    REFERENCES `mydb`.`SUT` (`SUT_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Test Run_Test Executor1`
    FOREIGN KEY (`TestExecutor_id`)
    REFERENCES `mydb`.`TestExecutor` (`TestExecutor_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TestSuite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`TestSuite` ;

CREATE TABLE IF NOT EXISTS `mydb`.`TestSuite` (
  `TestSuite_id` INT NOT NULL,
  `TestSuiteGoals` VARCHAR(255) NULL,
  `TestSuiteVersion` VARCHAR(45) NULL,
  `TestSuiteDocumentation` VARCHAR(255) NULL,
  `TestSuitecol` VARCHAR(45) NULL,
  PRIMARY KEY (`TestSuite_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TestCase`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`TestCase` ;

CREATE TABLE IF NOT EXISTS `mydb`.`TestCase` (
  `TestCase_id` INT NOT NULL AUTO_INCREMENT,
  `TestSuite_id` INT NOT NULL,
  `IsManual` TINYINT(1) NULL,
  `TestCaseFixtures` VARCHAR(255) NULL,
  `SourceCode` VARCHAR(255) NULL,
  `Author` VARCHAR(45) NULL,
  `TestCaseDescription` VARCHAR(255) NULL,
  `CreationDate` DATE NULL,
  PRIMARY KEY (`TestCase_id`),
  INDEX `fk_TestCase_TestSuite1_idx` (`TestSuite_id` ASC),
  CONSTRAINT `fk_TestCase_TestSuite1`
    FOREIGN KEY (`TestSuite_id`)
    REFERENCES `mydb`.`TestSuite` (`TestSuite_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TestCase_has_TestRun`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`TestCase_has_TestRun` ;

CREATE TABLE IF NOT EXISTS `mydb`.`TestCase_has_TestRun` (
  `TestCase_id` INT NOT NULL,
  `TestRun_id` INT NOT NULL,
  `Pass` TINYINT(1) NULL,
  PRIMARY KEY (`TestCase_id`, `TestRun_id`),
  INDEX `fk_Test Case_has_Test Run_Test Run1_idx` (`TestRun_id` ASC),
  INDEX `fk_Test Case_has_Test Run_Test Case1_idx` (`TestCase_id` ASC),
  CONSTRAINT `fk_Test Case_has_Test Run_Test Case1`
    FOREIGN KEY (`TestCase_id`)
    REFERENCES `mydb`.`TestCase` (`TestCase_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Test Case_has_Test Run_Test Run1`
    FOREIGN KEY (`TestRun_id`)
    REFERENCES `mydb`.`TestRun` (`TestRun_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TestCase_has_TestRequirement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`TestCase_has_TestRequirement` ;

CREATE TABLE IF NOT EXISTS `mydb`.`TestCase_has_TestRequirement` (
  `TestCase_TestCase_id` INT NOT NULL,
  `TestRequirement_TestRequirement_id` INT NOT NULL,
  PRIMARY KEY (`TestCase_TestCase_id`, `TestRequirement_TestRequirement_id`),
  INDEX `fk_TestCase_has_TestRequirement_TestRequirement1_idx` (`TestRequirement_TestRequirement_id` ASC),
  INDEX `fk_TestCase_has_TestRequirement_TestCase1_idx` (`TestCase_TestCase_id` ASC),
  CONSTRAINT `fk_TestCase_has_TestRequirement_TestCase1`
    FOREIGN KEY (`TestCase_TestCase_id`)
    REFERENCES `mydb`.`TestCase` (`TestCase_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TestCase_has_TestRequirement_TestRequirement1`
    FOREIGN KEY (`TestRequirement_TestRequirement_id`)
    REFERENCES `mydb`.`TestRequirement` (`TestRequirement_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TestSuite_has_TestRun`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`TestSuite_has_TestRun` ;

CREATE TABLE IF NOT EXISTS `mydb`.`TestSuite_has_TestRun` (
  `TestSuite_TestSuite_id` INT NOT NULL,
  `TestRun_TestRun_id` INT NOT NULL,
  PRIMARY KEY (`TestSuite_TestSuite_id`, `TestRun_TestRun_id`),
  INDEX `fk_TestSuite_has_TestRun_TestRun1_idx` (`TestRun_TestRun_id` ASC),
  INDEX `fk_TestSuite_has_TestRun_TestSuite1_idx` (`TestSuite_TestSuite_id` ASC),
  CONSTRAINT `fk_TestSuite_has_TestRun_TestSuite1`
    FOREIGN KEY (`TestSuite_TestSuite_id`)
    REFERENCES `mydb`.`TestSuite` (`TestSuite_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TestSuite_has_TestRun_TestRun1`
    FOREIGN KEY (`TestRun_TestRun_id`)
    REFERENCES `mydb`.`TestRun` (`TestRun_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
