SET NAMES utf8;  
SET time_zone = '+00:00';  
    
CREATE TABLE `user` (  
    `iduser` int(11) NOT NULL AUTO_INCREMENT,  
    `username` varchar(50) NOT NULL,  
    `password` varchar(128) NOT NULL,  
    `email` varchar(100) NOT NULL,  
    `firstname` varchar(100) DEFAULT NULL,  
    `lastname` varchar(100) DEFAULT NULL,  
    `address` text,  
    `mobile` varchar(50) DEFAULT NULL,  
    `tel` varchar(50) DEFAULT NULL,  
    `fax` varchar(50) DEFAULT NULL,  
    `sex` varchar(1) DEFAULT NULL,  
    `entreprise` int(11) DEFAULT NULL,  
    `signature` text COMMENT 'signature',  
    `dlogin` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  
    `acl` text COMMENT 'access rule',  
    PRIMARY KEY (`iduser`),  
    UNIQUE KEY `username` (`username`),  
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;