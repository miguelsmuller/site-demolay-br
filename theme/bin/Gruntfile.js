/*global module:false, require:false*/
module.exports = function(grunt) {

	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		dirs: {
			css     : '../assets/styles',
			sass    : '../assets/sass',
			scripts : '../assets/scripts',
			images  : '../assets/images',
			fonts   : '../assets/fonts',
			root    : '../../../../../public/.'
		},

		// Watch for changes
		watch: {
			options: {
				livereload: false
			},
			css: {
				files: ['<%= dirs.sass %>/{,*/}*.{scss,sass}'],
				tasks: ['compass']
			},
			scripts: {
				files: ['<%= jshint.all %>'],
				tasks: ['jshint', 'uglify']
			},
			html: {
				files: [
				'/*.{html,htm,shtml,shtm,xhtml,php,jsp,asp,aspx,erb,ctp}'
				]
			}
		},

		// Javascript linting with jshint
		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			all: [
				'Gruntfile.js',
				'<%= dirs.scripts %>/*.js',
				'!<%= dirs.scripts %>/*.min.js'
			]
		},

		// Uglify to concat and minify
		uglify: {
			options: {
				force: true,
				mangle: false
			},
			dist: {
				files: {
					'<%= dirs.scripts %>/javascript.min.js': [
						//BOOTSTRAP SASS
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/affix.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/collapse.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/tab.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/transition.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/modal.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/tooltip.js',

						//CUSTOM JS
						'<%= dirs.scripts %>/javascript.js'
					],

					'<%= dirs.scripts %>/maps.min.js': [
              			//CUSTOM JS
              			'<%= dirs.scripts %>/maps.js'
            		],

            		'<%= dirs.scripts %>/social.min.js': [
              			//CUSTOM JS
              			'<%= dirs.scripts %>/social.js'
            		]
				}
			}
		},

		// Compile scss/sass files to CSS
		compass: {
			dist: {
				options: {
			  		force: true,

			  		config: 'config.rb',

					sassDir: 'assets/sass',
					cssDir: 'assets/styles',
					imagesDir: 'assets/images',
					fontsDir: 'demolay-theme/<%= dirs.fonts %>/',
					javascriptsDir: 'demolay-theme/<%= dirs.scripts %>',

					outputStyle: 'compressed',
					relativeAssets: true,
					noLineComments: true
				}
			}
		},

		// Image optimization
		imagemin: {
			dist: {
				options: {
					optimizationLevel: 5,
					progressive: true
				},
				files: [{
			  		expand: true,
			  		cwd: '<%= dirs.images %>/',
			  		src: ['**/*.{png,jpg,gif}'],
			  		dest: '<%= dirs.images %>/'
				}]
			},
			upload: {
				files: [{
			  		expand: true,
			  		cwd: '<%= dirs.root %>/public-files/',
			  		src: ['**/*.{png,jpg,gif}'],
			  		dest: '<%= dirs.root %>/public-files/'
				}]
			}
		}
	});

	grunt.registerTask( 'default', [ 'compass', 'jshint', 'uglify' ]);
	grunt.registerTask( 'css', [ 'compass' ]);
	grunt.registerTask( 'js', [ 'jshint', 'uglify' ]);
};
