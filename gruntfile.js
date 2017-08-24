(function () {
    'use strict';
}());
module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            options: {
                separator: '\r\n\r\n'
            },
            dist: {
                src: ['assets/src/js/app.js', 'assets/src/js/CreatePoll.js', 'assets/src/js/AnswerPoll.js'],
                dest: 'assets/dist/js/main.js'
            }
        },
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */'
            },
            dist: {
                files: {
                    'assets/dist/js/main.min.js': ['<%= concat.dist.dest %>']
                }
            }
        },
        jshint: {
            files: ['gruntfile.js', 'assets/src/js/*.js'],
            options: {
                globals: {
                     jQuery: true,
                     console: true,
                     module: true
                }
            }
       },
       compass: {
            dist: {
                options: {
                    sassDir: 'assets/src/css',
                    cssDir: 'assets/dist/css',
                    environment: 'development',
                    outputStyle: 'compressed'
                }
            }
        },
        watch: {
            files: ['<%= jshint.files %>', 'assets/src/css/**/*.scss'],
            tasks: ['concat', 'uglify', 'jshint', 'compass']
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');    
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('default', ['concat', 'uglify', 'jshint', 'compass', 'watch']);
};