CREATE TABLE #__asinustimetracking_entries (
	ct_id      	int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
	cu_id      	int(11) NOT NULL,
	cs_id      	int(11) NOT NULL,
	cg_id      	int(11) NOT NULL,
	cc_id		int(11) NOT NULL,
	entry_date 	datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	start_time 	datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	end_time   	datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	start_pause	datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	end_pause  	datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	timestamp  	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	qty        	int(11) NULL DEFAULT '0',
	remark		varchar(2000)
	);
CREATE TABLE #__asinustimetracking_roles (
	crid       	int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
	description	varchar(255) NOT NULL
	);
CREATE TABLE #__asinustimetracking_selection (
	cg_id      	int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
	state      	TINYINT(3) NOT NULL DEFAULT '1',
	description	varchar(155) NOT NULL
	);
CREATE TABLE #__asinustimetracking_services (
	csid       	int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
	description	varchar(255) NOT NULL,
	is_worktime	tinyint(1) NOT NULL
	);
CREATE TABLE #__asinustimetracking_user (
	cuid    	int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
	uid     	int(11) NOT NULL,
	crid    	int(11) NOT NULL DEFAULT '1',
	is_admin	tinyint(1) NOT NULL DEFAULT '0',
	employee_id INT( 11 ) NOT NULL DEFAULT '0'
	);
CREATE TABLE #__asinustimetracking_userservices (
	cus_id	int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
	cu_id 	int(11) NOT NULL,
	csid  	int(11) NOT NULL,
	price 	decimal(10,2) NOT NULL
	);

CREATE TABLE #__asinustimetracking_costunit (
	cc_id 		int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
	name		varchar(255) NOT NULL,
	description	varchar(2000)
);

CREATE TABLE #__asinustimetracking_config (
	cp_id	int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
	name 	varchar(255) NOT NULL,
	value	varchar(3000) NULL
	);

CREATE TABLE #__asinustimetracking_pricerange (
		cp_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
		cu_id INT(11) NOT NULL,
		cs_id INT(11) NOT NULL,
		start_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		end_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		entry_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		price DECIMAL(10,2) NOT NULL
	);

INSERT INTO #__asinustimetracking_config(cp_id, name, value)
  VALUES(1, 'currency', '&euro;');

INSERT INTO #__asinustimetracking_selection
(
	description
) VALUES
(
	'Empty'
);

INSERT INTO #__asinustimetracking_costunit
(
	name,
	description
) VALUES
(
	'Basic',
	'General Cost Centre'
);

INSERT INTO #__asinustimetracking_roles
	(
		crid,
		description
	)
VALUES
	(
		1,
		'User'
	);
INSERT INTO #__asinustimetracking_roles
	(
		crid,
		description
	)
VALUES
	(
		2,
		'No role'
	);
INSERT INTO #__asinustimetracking_services
	(
		csid,
		description,
		is_worktime
	)
VALUES
	(
		1,
		'Working hours',
		TRUE
	);
