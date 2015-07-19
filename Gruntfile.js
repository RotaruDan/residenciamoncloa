module.exports = function(grunt) {
    'use strict';

    grunt.initConfig({
        less  : {
            production : {
                files: {
                    "./css/freelancer.css": "./less/freelancer.less"
                }
            }
        },
        cssmin : {
            minify : {
                expand: true,
                cwd   : './css',
                src   : ['*.css', '!*.min.css'],
                dest  : './css',
                ext   : '.min.css'
            }
        }
    });


    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.registerTask('default', ['less', 'cssmin']);
};