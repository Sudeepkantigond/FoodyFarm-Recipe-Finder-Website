
create database cooking;

DROP TABLE IF EXISTS `admin_dir`;
CREATE TABLE IF NOT EXISTS `admin_dir` (
  `a_id` int(222) NOT NULL AUTO_INCREMENT,
  `username` varchar(222) NOT NULL,
  `password` varchar(222) NOT NULL,
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;



INSERT INTO `admin_dir` (`a_id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

DROP TABLE IF EXISTS `commentbar`;
CREATE TABLE IF NOT EXISTS `commentbar` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(222) NOT NULL,
  `text` text NOT NULL,
  `date_time` varchar(222) NOT NULL,
  `recipy_id` int(222) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `full_recipy`;
CREATE TABLE IF NOT EXISTS `full_recipy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(222) NOT NULL,
  `title_text` text NOT NULL,
  `image` varchar(222) NOT NULL,
  `ing_text` text NOT NULL,
  `disc` text NOT NULL,
  `rid` varchar(222) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `post_rating`;
CREATE TABLE IF NOT EXISTS `post_rating` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `rating_number` int(11) NOT NULL,
  `total_points` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Block, 0 = Unblock',
  PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `recipes`;
CREATE TABLE IF NOT EXISTS `recipes` (
  `rid` int(222) NOT NULL AUTO_INCREMENT,
  `rimage` varchar(222) NOT NULL,
  `resname` varchar(222) NOT NULL,
  `rtext` text NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `signup`;
CREATE TABLE IF NOT EXISTS `signup` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(244) NOT NULL,
  `lastname` varchar(244) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;


--Trigger to delete the detailed recipe afer deleting in recipe table 
DELIMITER //
CREATE TRIGGER delete_full_recipe 
AFTER DELETE ON recipes
FOR EACH ROW
BEGIN
    DELETE FROM full_recipy
    WHERE rid = OLD.rid;
END;
//
DELIMITER ;

-- Remove all comments from the commentbar associated with the deleted user
DELIMITER //
CREATE TRIGGER delete_user_and_recipe_comments
AFTER DELETE ON signup
FOR EACH ROW
BEGIN
    DELETE FROM commentbar
    WHERE user_id = OLD.user_id;
END;
//
DELIMITER ;

--Trigger to restrict signups which is already signed up earlier
DELIMITER //
CREATE TRIGGER before_signup_insert 
BEFORE INSERT ON signup
FOR EACH ROW
BEGIN
  DECLARE email_count INT;

  SELECT COUNT(*) INTO email_count
  FROM signup
  WHERE email = NEW.email;

  IF email_count > 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Email address already exists. Please use a different email.';
  END IF;
END;
//
DELIMITER ;

-- Procedure for error handling

DELIMITER //

CREATE PROCEDURE AddCommentSafe(
    IN p_user_id INT,
    IN p_text TEXT,
    IN p_date_time VARCHAR(222),
    IN p_recipy_id INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error occurred while inserting comment!';
    END;

    START TRANSACTION;

    INSERT INTO commentbar (user_id, text, date_time, recipy_id)
    VALUES (p_user_id, p_text, p_date_time, p_recipy_id);

    COMMIT;
END //

DELIMITER ;



