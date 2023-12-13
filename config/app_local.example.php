<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */
return [
	/*
	 * Debug Level:
	 *
	 * Production Mode:
	 * false: No error messages, errors, or warnings shown.
	 *
	 * Development Mode:
	 * true: Errors and warnings shown.
	 */
	'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),

	/*
	 * Security and encryption configuration
	 *
	 * - salt - A random string used in security hashing methods.
	 *   The salt value is also used as the encryption key.
	 *   You should treat it as extremely sensitive data.
	 */
	'Security' => [
		'salt' => env('SECURITY_SALT', '1c3af70b73b858fa4012ee226dc97934d7d63a569024e2fd556bbcc6498ac75b'),
	],

	/*
	 * Connection information used by the ORM to connect
	 * to your application's datastores.
	 *
	 * See app.php for more configuration options.
	 */
	'Datasources' => [
		'default' => [
			'host' => 'database.iflowvtex.internal',
			/*
			 * CakePHP will use the default DB port based on the driver selected
			 * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
			 * the following line and set the port accordingly
			 */
			//'port' => 'non_standard_port_number',

			'username' => 'iflowvtexdb',
			'password' => 'iflowvtexdb',

			'database' => 'iflowvtexdb',
			/*
			 * If not using the default 'public' schema with the PostgreSQL driver
			 * set it here.
			 */
			//'schema' => 'myapp',

			/*
			 * You can use a DSN string to set the entire configuration
			 */
			'url' => env('DATABASE_URL', null),
		],

		/*
		 * The test connection is used during the test suite.
		 */
		'test' => [
			'host' => 'localhost',
			//'port' => 'non_standard_port_number',
			'username' => 'my_app',
			'password' => 'secret',
			'database' => 'test_myapp',
			//'schema' => 'myapp',
			'url' => env('DATABASE_TEST_URL', 'sqlite://127.0.0.1/tests.sqlite'),
		],
	],

	/*
	 * Email configuration.
	 *
	 * Host and credential configuration in case you are using SmtpTransport
	 *
	 * See app.php for more configuration options.
	 */
	'EmailTransport' => [
		'default' => [
			'className' => 'Smtp',
			'host' => 'smtp.gmail.com',
			'tls' => true,
			'port' => 587,
			'username' => 'jsuarez@mydesign.com.ar',
			'password' => 'pyoqxijcrguydejt',
			//'client' => null,
			//'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
		],
	],
	'Session' => [
		'defaults' => 'php',
		'timeout' => 24 * 60 //in minutes
	],

	'iflow' => [
		'url_quotations' => 'https://tn.iflow21.com/tmp/ws_rate_vtex.php',
		'host_tracking' => 'https://test-tracking.iflow21.com',
		'host_api' => 'test-api.iflow21.com'
	]
];
