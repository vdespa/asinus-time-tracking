ALTER TABLE  `#__asinustimetracking_selection` ADD  `state` TINYINT(3) NOT NULL DEFAULT '1' AFTER `cg_id`;

IF NOT EXISTS( (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE()
        AND COLUMN_NAME='employee_id' AND TABLE_NAME='#__asinustimetracking_user') ) THEN
    ALTER TABLE  `#__asinustimetracking_user` ADD  `employee_id` INT( 11 ) NOT NULL;
END IF;