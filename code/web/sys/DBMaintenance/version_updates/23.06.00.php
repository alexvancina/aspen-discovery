<?php
/** @noinspection PhpUnused */
function getUpdates23_06_00(): array {
	$curTime = time();
	return [
		/*'name' => [
			'title' => '',
			'description' => '',
			'continueOnError' => false,
			'sql' => [
				''
			]
		], //sample*/

		'add_administer_selected_browse_category_groups' => [
			'title' => 'Add Administer Selected Browse Category Groups Permission',
			'description' => 'Add Administer Selected Browse Category Groups Permission',
			'continueOnError' => false,
			'sql' => [
				"INSERT INTO permissions (sectionName, name, requiredModule, weight, description) VALUES ('Local Enrichment', 'Administer Selected Browse Category Groups', '', 15, 'Allows the user to view and edit only the Browse Category Groups they are assigned to.')",
			]
		], //add_administer_selected_browse_category_groups
		'add_selected_users_to_browse_category_groups' => [
			'title' => 'Add Selected Users to Browse Category Groups',
			'description' => 'Add Selected Users to Browse Category Groups',
			'continueOnError' => false,
			'sql' => [
				'CREATE TABLE IF NOT EXISTS browse_category_group_users (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
					browseCategoryGroupId INT(11),
					userId INT(11)
				) ENGINE INNODB',
				'ALTER TABLE browse_category_group_users ADD UNIQUE (browseCategoryGroupId, userId)',
			]
		], //add_selected_users_to_browse_category_groups
		'indexing_profile_add_check_sierra_mat_type_for_format' => [
			'title' => 'Indexing Profile Add Check Sierra Material Type for Format',
			'description' => 'Indexing Profile Add Check Sierra Material Type for Format',
			'continueOnError' => false,
			'sql' => [
				'ALTER TABLE indexing_profiles add column checkSierraMatTypeForFormat TINYINT DEFAULT 0'
			]
		], //indexing_profile_add_check_sierra_mat_type_for_format

		'add_ecommerce_payflow_settings' => [
			'title' => 'Add eCommerce vendor PayPal Payflow',
			'description' => 'Create tables to store settings for PayPal Payflow',
			'continueOnError' => true,
			'sql' => [
				'CREATE TABLE IF NOT EXISTS paypal_payflow_settings (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
					name VARCHAR(50) NOT NULL UNIQUE,
					sandboxMode TINYINT(1) DEFAULT 0,
					partner VARCHAR(72) NOT NULL,
					vendor VARCHAR(72) NOT NULL,
					user VARCHAR(72) NOT NULL,
					password VARCHAR(72) NOT NULL
				) ENGINE INNODB',
				'ALTER TABLE library ADD COLUMN paypalPayflowSettingId INT(11) DEFAULT -1',
			],
		],
		// add_ecommerce_payflow_settings
		'permissions_ecommerce_payflow' => [
			'title' => 'Add permissions for PayPal Payflow',
			'description' => 'Create permissions for administration of PayPal Payflow',
			'continueOnError' => true,
			'sql' => [
				"INSERT INTO permissions (sectionName, name, requiredModule, weight, description) VALUES ('eCommerce', 'Administer PayPal Payflow', '', 10, 'Controls if the user can change PayPal Payflow settings. <em>This has potential security and cost implications.</em>')",
				"INSERT INTO role_permissions(roleId, permissionId) VALUES ((SELECT roleId from roles where name='opacAdmin'), (SELECT id from permissions where name='Administer PayPal Payflow'))",
			],
		],
		// permissions_ecommerce_payflow
		'add_sso_saml_student_attributes' => [
			'title' => 'Add settings to setup student users with SSO',
			'description' => 'Add settings to setup student users with SSO using SAML',
			'continueOnError' => true,
			'sql' => [
				'ALTER TABLE sso_setting ADD COLUMN samlStudentPTypeAttr VARCHAR(255) DEFAULT null',
				'ALTER TABLE sso_setting ADD COLUMN samlStudentPTypeAttrValue VARCHAR(255) DEFAULT null',
				'ALTER TABLE sso_setting ADD COLUMN samlStudentPType VARCHAR(30) DEFAULT null',
			],
		],
		//add_sso_saml_student_attributes
		'add_show_edition_covers' => [
			'title' => 'Add option to show edition covers',
			'description' => 'Add option to show individual covers for each edition',
			'continueOnError' => true,
			'sql' => [
				'ALTER TABLE grouped_work_display_settings ADD COLUMN showEditionCovers TINYINT(1) DEFAULT 0',
			],
		],
		//add_show_edition_covers
		'event_library_mapping' => [
			'title' => 'Event Library Mapping',
			'description' => 'Maps library branch names to the values in Aspen for Events relevancy',
			'sql' => [
				'CREATE TABLE IF NOT EXISTS `event_library_map_values` (
				  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `aspenLocation` varchar(255) NOT NULL,
				  `eventsLocation` varchar(255) NOT NULL,
				  `locationId` INT(11) NOT NULL,
				  `libraryId` INT(11) NOT NULL,
				  UNIQUE KEY (`locationId`)
				)',
			]
		], //event_library_mapping
		'event_library_mapping_values' => [
			'title' => 'Event Library Mapping Values',
			'description' => 'Populates event_library_map_values with existing information.',
			'sql' => [
				"INSERT INTO event_library_map_values(aspenLocation, eventsLocation, locationId, libraryId) SELECT displayName, displayName, locationId, libraryId FROM location ORDER BY locationId ASC",
			]
		], //event_library_mapping_values
		'ticket_trends' => [
			'title' => 'Ticket Trends',
			'description' => 'Create new tables to store ticket trends',
			'continueOnError' => true,
			'sql' => [
				"DROP TABLE ticket_stats",
				"CREATE TABLE IF NOT EXISTS ticket_trend_bugs_by_severity (
					id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					year int(11) NOT NULL,
					month int(2) NOT NULL,
					day int(2) NOT NULL,
					severity VARCHAR(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
					count int(11) DEFAULT 0,
					UNIQUE KEY uniqueness (year,month,day,severity)   
				)",
				"CREATE TABLE IF NOT EXISTS ticket_trend_by_partner (
					id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					year int(11) NOT NULL,
					month int(2) NOT NULL,
					day int(2) NOT NULL,
					requestingPartner int(11) DEFAULT NULL,
					queue varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
					count int(11) DEFAULT 0,
					UNIQUE KEY uniqueness (year,month,day,requestingPartner, queue)   
				)",
				"CREATE TABLE IF NOT EXISTS ticket_trend_by_queue (
					id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					year int(11) NOT NULL,
					month int(2) NOT NULL,
					day int(2) NOT NULL,
					queue varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
					count int(11) DEFAULT 0,
					UNIQUE KEY uniqueness (year,month,day, queue)   
				)",
				"CREATE TABLE IF NOT EXISTS ticket_trend_by_component (
					id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					year int(11) NOT NULL,
					month int(2) NOT NULL,
					day int(2) NOT NULL,
					component varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL, 
					queue varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
					count int(11) DEFAULT 0,
					UNIQUE KEY uniqueness (year,month,day, component, queue)   
				)",
			]
		]
	];
}