ALTER TABLE  `#__asinustimetracking_selection` ADD  `state` TINYINT(3) NOT NULL DEFAULT '1' AFTER `cg_id`;
-- Inspired by http://stackoverflow.com/a/16405301/766177
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = `#__asinustimetracking_user`
        AND table_schema = DATABASE()
        AND column_name = 'employee_id'
    ) > 0,
    "SELECT 1",
    "ALTER IGNORE TABLE `#__asinustimetracking_user` ADD `employee_id` INT( 11 ) NOT NULL"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;