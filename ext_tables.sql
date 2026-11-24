#
# Table structure for table 'tx_janolawservice_domain_model_janolawservice'
# System fields (uid, pid, tstamp, crdate, deleted, hidden, starttime, endtime,
# workspace and language fields) are added automatically from TCA.
#
CREATE TABLE tx_janolawservice_domain_model_janolawservice
(
	type            varchar(255) DEFAULT '' NOT NULL,
	userid          int(11) DEFAULT '0' NOT NULL,
	shopid          int(11) DEFAULT '0' NOT NULL,
	external        text NOT NULL,
	legacy_language varchar(255) DEFAULT '' NOT NULL,
	pdf             varchar(255) DEFAULT '' NOT NULL
);
