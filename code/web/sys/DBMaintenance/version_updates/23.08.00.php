<?php
/** @noinspection PhpUnused */
function getUpdates23_08_00(): array {
	$curTime = time();
	return [
		/*'name' => [
			'title' => '',
			'description' => '',
			'continueOnError' => false,
			'sql' => [
				''
			]
		], //name*/


		//mark - ByWater
		'custom_facets' => [
			'title' => 'Add custom facet indexing information to Indexing Profiles',
			'description' => 'Add custom facet indexing information to Indexing Profiles',
			'continueOnError' => true,
			'sql' => [
				"ALTER TABLE indexing_profiles ADD COLUMN customFacet1SourceField VARCHAR(50) DEFAULT ''",
				"ALTER TABLE indexing_profiles ADD COLUMN customFacet1ValuesToInclude TEXT",
				"ALTER TABLE indexing_profiles ADD COLUMN customFacet1ValuesToExclude TEXT",
				"ALTER TABLE indexing_profiles ADD COLUMN customFacet2SourceField VARCHAR(50) DEFAULT ''",
				"ALTER TABLE indexing_profiles ADD COLUMN customFacet2ValuesToInclude TEXT",
				"ALTER TABLE indexing_profiles ADD COLUMN customFacet2ValuesToExclude TEXT",
				"ALTER TABLE indexing_profiles ADD COLUMN customFacet3SourceField VARCHAR(50) DEFAULT ''",
				"ALTER TABLE indexing_profiles ADD COLUMN customFacet3ValuesToInclude TEXT",
				"ALTER TABLE indexing_profiles ADD COLUMN customFacet3ValuesToExclude TEXT",
				"UPDATE indexing_profiles set customFacet1ValuesToInclude = '.*'",
				"UPDATE indexing_profiles set customFacet2ValuesToInclude = '.*'",
				"UPDATE indexing_profiles set customFacet3ValuesToInclude = '.*'",
			]
		],
		'twilio_settings' => [
			'title' => 'Twilio Settings',
			'description' => 'Add twilio settings and permissions',
			'continueOnError' => true,
			'sql' => [
				"CREATE TABLE IF NOT EXISTS twilio_settings (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					name VARCHAR(50) UNIQUE,
					phone VARCHAR(15),
					accountSid VARCHAR(50),
					authToken VARCHAR(256)
				)",
				"ALTER TABLE library ADD COLUMN twilioSettingId INT(11) DEFAULT -1",
				"INSERT INTO permissions (sectionName, name, requiredModule, weight, description) VALUES ('System Administration', 'Administer Twilio', '', 34, 'Controls if the user can change Twilio settings. <em>This has potential security and cost implications.</em>')",
				"INSERT INTO role_permissions(roleId, permissionId) VALUES ((SELECT roleId from roles where name='opacAdmin'), (SELECT id from permissions where name='Administer Twilio'))",
			]
		],

		//kirstien - ByWater
		'aspen_lida_self_check_settings' => [
			'title' => 'Aspen LiDA Self-Check Settings',
			'description' => 'Add Aspen LiDA self-check settings and permissions',
			'continueOnError' => true,
			'sql' => [
				'CREATE TABLE IF NOT EXISTS aspen_lida_self_check_settings (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					name VARCHAR(50) UNIQUE,
					isEnabled TINYINT(1) DEFAULT 0
				)',
				'ALTER TABLE location ADD COLUMN lidaSelfCheckSettingId INT(11) DEFAULT -1',
				"INSERT INTO permissions (sectionName, name, requiredModule, weight, description) VALUES ('Aspen LiDA', 'Administer Aspen LiDA Self-Check Settings', 'Aspen LiDA', 10, 'Controls if the user can change Aspen LiDA Self-Check settings.')",
				"INSERT INTO role_permissions(roleId, permissionId) VALUES ((SELECT roleId from roles where name='opacAdmin'), (SELECT id from permissions where name='Administer Aspen LiDA Self-Check Settings'))",
			]
		],
		'aspen_lida_permissions_update' => [
			'title' => 'Update Aspen LiDA permissions',
			'description' => 'Add Aspen LiDA as required module for Aspen LiDA permissions',
			'continueOnError' => true,
			'sql' => [
				"UPDATE permissions set requiredModule = 'Aspen LiDA' WHERE sectionName = 'Aspen LiDA'",
			]

		],

		//kodi - ByWater

		'webpage_default_image' => [
			'title' => 'Website Indexing - Set default image for cover images',
			'description' => 'Update website_indexing_settings table to have default values for the default cover image',
			'sql' => [
				"ALTER TABLE website_indexing_settings ADD COLUMN defaultCover VARCHAR(100) default ''",
			],
		], //webpage_default_image
		'OAI_default_image' => [
			'title' => 'OAI Indexing - Set default image for cover images',
			'description' => 'Update open_archives_collection table to have default values for the default cover image',
			'sql' => [
				"ALTER TABLE open_archives_collection ADD COLUMN defaultCover VARCHAR(100) default ''",
			],
		], //OAI_default_image
		'events_in_lists' => [
			'title' => 'Events in Lists Settings',
			'description'=> 'Add settings for events in lists for Communico, Springshare LibCal, and Library Market',
			'sql' => [
				'ALTER TABLE lm_library_calendar_settings ADD COLUMN eventsInLists tinyint(1) default 1',
				'ALTER TABLE springshare_libcal_settings ADD COLUMN eventsInLists tinyint(1) default 1',
				'ALTER TABLE communico_settings ADD COLUMN eventsInLists tinyint(1) default 1',
			],
		], //events_in_lists
		'bypass_event_pages' => [
			'title' => 'Bypass Aspen event pages',
			'description'=> 'Add settings for events to bypass the Aspen event page and redirect the user to the event page on the native platform',
			'sql' => [
				'ALTER TABLE lm_library_calendar_settings ADD COLUMN bypassAspenEventPages tinyint(1) default 0',
				'ALTER TABLE springshare_libcal_settings ADD COLUMN bypassAspenEventPages tinyint(1) default 0',
				'ALTER TABLE communico_settings ADD COLUMN bypassAspenEventPages tinyint(1) default 0',
			],
		], //bypass_event_pages

		//other organizations

	];
}