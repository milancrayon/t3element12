CREATE TABLE tx_t3element_domain_model_elements ( 
	data text NOT NULL DEFAULT ''
);

CREATE TABLE tx_t3element_domain_model_license ( 
	email varchar(255) DEFAULT '' NOT NULL,
	c_lc  varchar(255) DEFAULT '' NOT NULL,
	status int(11) unsigned DEFAULT '0' NOT NULL,
	is_verify int(11) unsigned DEFAULT '0' NOT NULL,
	log varchar(255) DEFAULT '' NOT NULL,
	version varchar(255) DEFAULT '' NOT NULL,
	last_verify int(11) unsigned DEFAULT '0' NOT NULL,
);

CREATE TABLE tx_t3element_domain_model_themeconfig (
	header text DEFAULT '',
	footer text DEFAULT '',
	cssjs text DEFAULT '',
	menu text DEFAULT '',
	langm text DEFAULT '',
);