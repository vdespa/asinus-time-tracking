ALTER TABLE  `#__asinustimetracking_selection` ADD  `state` TINYINT(3) NOT NULL DEFAULT '1' AFTER `cg_id`;
ALTER IGNORE TABLE  `#__asinustimetracking_user` ADD  `employee_id` INT( 11 ) NOT NULL;