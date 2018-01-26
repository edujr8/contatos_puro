-- -----------------------------------------------------
-- Schema contato
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `contato` DEFAULT CHARACTER SET utf8 ;
USE `contato` ;

-- -----------------------------------------------------
-- Table `contato`.`contato`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contato`.`contato` (
  `id_contato` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `celular` VARCHAR(11) NOT NULL,
  `operadora_cel` VARCHAR(10) NOT NULL,
  `cidade` VARCHAR(30) NOT NULL,
  `estado` VARCHAR(30) NOT NULL,
  `data_nascimento` DATE NOT NULL,
  PRIMARY KEY (`id_contato`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE UNIQUE INDEX `id_contato_UNIQUE` ON `contato`.`contato` (`id_contato` ASC);

-- -----------------------------------------------------
-- Table `contato`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contato`.`usuario` (
  `id_usuario` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `login` VARCHAR(20) NOT NULL,
  `tipo_login` INT(1) NOT NULL,
  `senha` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_usuario`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE UNIQUE INDEX `id_usuario_UNIQUE` ON `contato`.`usuario` (`id_usuario` ASC);


/*
* Usuario ROOT Login: admin Senha: 123456
*/
INSERT INTO usuario (nome, login, tipo_login, senha) VALUES ('Administrador', 'admin', '1', 'e10adc3949ba59abbe56e057f20f883e');

/*
* Usuario GUEST Login: guest Senha: 123456
*/
INSERT INTO usuario (nome, login, tipo_login, senha) VALUES ('Visitante', 'guest', '2', 'e10adc3949ba59abbe56e057f20f883e');

/*
* Contato Inicial
*/
INSERT INTO contato (nome, email, celular, operadora_cel, cidade, estado, data_nascimento) VALUES ('Eduardo Montecino', 'zafrinus@hotmail.com', '11981643300', 'Tim', 'São Paulo', 'SP', '1986-09-15');
