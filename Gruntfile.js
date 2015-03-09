module.exports = function (grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),



        bowercopy: {
			options: {
				srcPrefix: 'bower_components',
				destPrefix: 'web/assets'
			 },
            myCss: {
                options: {
                    srcPrefix: 'frontend'
                },
                files: {
                    'css/sb-admin.css': 'css/sb-admin.css'
                }
            },
			scripts: {
				files: {
					'js/jquery.js': 'jquery/dist/jquery.js',
					'js/bootstrap.js': 'bootstrap/dist/js/bootstrap.js'
				}
		 	},
			stylesheets: {
				files: {
					'css/bootstrap.css': 'bootstrap/dist/css/bootstrap.css',
					'css/font-awesome.css': 'font-awesome/css/font-awesome.css',

				}
		 	},
			fonts: {
				files: {
					'fonts': 'font-awesome/fonts'
				}
	       		}
		}
	});

	grunt.loadNpmTasks('grunt-bowercopy');
	grunt.registerTask('default', ['bowercopy']);
};
