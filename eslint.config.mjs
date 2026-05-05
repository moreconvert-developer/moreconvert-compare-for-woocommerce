import wordpress from '@wordpress/eslint-plugin';

export default [
	{
		ignores: [
			'**/vendor/',
			'**/node_modules/',
			'**/options/node_modules/',
			'assets/**/*.js',
			'options/assets/**/*.js',
			'options/src/lib/**/*.js',
			'**/gulpfile.babel.js',
			'options/gulpfile.babel.js',
		],
	},
	...wordpress.configs.recommended,
	{
		languageOptions: {
			ecmaVersion: 2020,
			sourceType: 'module',
			parserOptions: {
				requireConfigFile: false,
				babelOptions: { presets: ['@babel/preset-env'] },
			},
			globals: {
				jQuery: 'readonly',
				wp: 'readonly',
				McCompare: 'readonly',
				mctAdminParams: 'readonly',
				moment: 'readonly',
				tinyMCE: 'readonly',
				_: 'readonly',
				Color: 'readonly',
				confirm: 'readonly',
				alert: 'readonly',
				location: 'readonly',
			},
		},

		// Define files to lint
		files: ['src/**/*.js', 'options/src/js/*.js'],
		// Ignores (equivalent to .eslintignore)
	},
	{
		// Custom rule overrides
		rules: {
			camelcase: 'warn',
			'no-unused-vars': 'warn',
			'@wordpress/no-global-event-listener': 'off',
			'jsdoc/check-tag-names': 'off',
			'no-console': 'off',
			'no-alert': 'off',
		},
	},
];
