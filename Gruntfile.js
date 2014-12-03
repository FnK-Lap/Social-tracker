module.exports = function(grunt) {
    // Do grunt-related things in here

    var conf = {
        webDir      : "web/assets/",
        scssDir     : "scss/",
        cssDir      : "css/",
        jsDir       : "js/",
        imgDir      : "img/",
        imgProdDir  : "prod/",
        stathamPath : 'web/assets/js/statham.json',
        jsFiles     : []
    };

    var jsMix = grunt.file.readJSON(conf.stathamPath);
    for ( var i = 0; i < jsMix.files.length; i++) {
        conf.jsFiles.push(conf.webDir+conf.jsDir+jsMix.files[i]);
    }

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        
        // Automatically run a task when a file changes
        watch: {
            css: {
                files: ["**/*.scss"],
                tasks: ['cssroutine'],
                options: {
                    cwd: conf.webDir+conf.scssDir,
                    livereload: true,
                },

            },
            js: {
                files: ["**/*.js", "!**/*.min.js"],
                tasks: ['jsroutine'],
                options: {
                    cwd: conf.webDir+conf.jsDir,
                    livereload: true,
                },
            }
        },
        
        //Compile specified SASS files
        // sass: {
        //     css: {
        //         files: [{
        //             expand: true,
        //             cwd: conf.webDir+conf.scssDir,
        //             src: ['app.scss'],
        //             dest: conf.webDir+conf.cssDir,
        //             ext: '.css'
        //         }],
        //         options: {                   
        //             includePaths: [
        //                 conf.webDir+conf.scssDir
        //             ]
        //         },

        //     },
        // },

        sass: {
          css: {
            options: {
              loadPath: conf.webDir+conf.scssDir,
              style: "compact"
            },
            files: [{
                expand: true,
                cwd: conf.webDir+conf.scssDir,
                src: ['app.scss'],
                dest: conf.webDir+conf.cssDir,
                ext: '.css'
            }] 
          }
        },
        
        // Compress generated css files
        cssmin: {
            minify: {
                expand: true,
                cwd: conf.webDir+conf.cssDir,
                src: ['app.css', '!app.min.css'],
                dest: conf.webDir+conf.cssDir,
                ext: '.min.css'
            }
        },
        
        //Prefix CSS3 properties
        autoprefixer: {
            no_dest: {
              src: conf.webDir+conf.cssDir+'app.css' // globbing is also possible here
            },
        },
        
        // UglifyJS
        uglify: {
            minify_all: {
                files: [{
                    expand: true,
                    cwd: conf.webDir+conf.jsDir,
                    src: ['**/*.js', '!**/*.min.js', '!**/*.mix.js'],
                    dest: conf.webDir+conf.jsDir,
                    ext: '.min.js'
                }],
            },
        },

        // Script concatenation
        concat: {
            dist: {
                src: conf.jsFiles,
                dest: conf.webDir+conf.jsDir+'scripts.min.js'
            },
        },

        // Image minification
        imagemin: {
            all: {
                options: {
                    optimizationLevel: 7,
                    pngquant: true
                },
                files: [{
                    expand: true,
                    cwd: conf.webDir+conf.imgDir,
                    src: ['**/*.{png,jpg,gif}'],
                    dest: conf.webDir+conf.imgDir
                }]
            },
        },


    });

    //grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-newer');
    grunt.loadNpmTasks('grunt-imgcompress');

    //Watch
    grunt.registerTask('cssroutine', ['sass:css', 'newer:autoprefixer', 'newer:cssmin']);
    grunt.registerTask('jsroutine', ['uglify'/*,'newer:concat'*/]);
    grunt.registerTask('imgroutine', ['newer:imagemin']);

};