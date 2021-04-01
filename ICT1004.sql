-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `user_verified` TINYINT NOT NULL DEFAULT 0,
  `token` VARCHAR(255) NOT NULL,
  `user_created` TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `UserID_UNIQUE` (`user_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`items_listing`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`items_listing` (
  `item_id` INT NOT NULL AUTO_INCREMENT,
  `user_user_id` INT NOT NULL,
  `item_name` VARCHAR(45) NOT NULL,
  `description` LONGTEXT NULL,
  `date_added` DATETIME NOT NULL,
  `item_status` TINYINT NOT NULL,
  PRIMARY KEY (`item_id`, `user_user_id`),
  UNIQUE INDEX `item_id_UNIQUE` (`item_id` ASC) ,
  INDEX `fk_items_listing_user_idx` (`user_user_id` ASC) ,
  CONSTRAINT `fk_items_listing_user`
    FOREIGN KEY (`user_user_id`)
    REFERENCES `mydb`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`item_transaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`item_transaction` (
  `transaction_id` INT NOT NULL AUTO_INCREMENT,
  `rating` INT NULL,
  `seller_id` INT NOT NULL,
  `buyer_id` INT NOT NULL,
  `item_id` INT NOT NULL,
  PRIMARY KEY (`transaction_id`, `seller_id`, `buyer_id`, `item_id`),
  INDEX `fk_item_transaction_user1_idx` (`seller_id` ASC) ,
  INDEX `fk_item_transaction_user2_idx` (`buyer_id` ASC) ,
  INDEX `fk_item_chat_items_listing1_idx` (`item_id` ASC) ,
  CONSTRAINT `fk_item_transaction_user1`
    FOREIGN KEY (`seller_id`)
    REFERENCES `mydb`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_transaction_user2`
    FOREIGN KEY (`buyer_id`)
    REFERENCES `mydb`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_chat_items_listing1`
    FOREIGN KEY (`item_id`)
    REFERENCES `mydb`.`items_listing` (`item_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`item_chat`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`item_chat` (
  `chat` INT NOT NULL,
  `seller_id` INT NOT NULL,
  `buyer_id` INT NOT NULL,
  `timestamp` DATETIME NOT NULL,
  PRIMARY KEY (`seller_id`, `buyer_id`),
  INDEX `fk_item_transaction_user1_idx` (`seller_id` ASC) ,
  INDEX `fk_item_transaction_user2_idx` (`buyer_id` ASC) ,
  CONSTRAINT `fk_item_transaction_user10`
    FOREIGN KEY (`seller_id`)
    REFERENCES `mydb`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_transaction_user20`
    FOREIGN KEY (`buyer_id`)
    REFERENCES `mydb`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`item_image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`item_image` (
  `image_id` INT NOT NULL,
  `item_id` INT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`image_id`, `item_id`),
  INDEX `fk_item_image_items_listing1_idx` (`item_id` ASC) ,
  CONSTRAINT `fk_item_image_items_listing1`
    FOREIGN KEY (`item_id`)
    REFERENCES `mydb`.`items_listing` (`item_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
