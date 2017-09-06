# README  


### ACL  

ACL can get true, false, signin or defined name  
#### ex  
    {
    ...
      "acl":true,
      "acl":false,
      "acl":"signin",
      "acl":"beta_test",
    ...
    }
  
  

### MySQL  
  
    SET NAMES utf8;  
    SET time_zone = '+00:00';  
      
    CREATE TABLE `user` (  
      `id` int(11) NOT NULL AUTO_INCREMENT,  
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
      `date_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  
      `acl` text COMMENT 'access rule',  
      PRIMARY KEY (`id`),  
      UNIQUE KEY `username` (`username`),  
      UNIQUE KEY `email` (`email`),  
      KEY `entreprise` (`entreprise`),  
      CONSTRAINT `user_ibfk_1` FOREIGN KEY (`entreprise`) REFERENCES `entreprise` (`id`)  
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;  
  
  