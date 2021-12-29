module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    var appConfig = {

        // Definindo os diretórios
        dirs: {
            js:   "assets/js",
            sass: "assets/sass",
            css:  "assets/css",
            img:  "assets/images",
            font: "assets/font"
        },

        // Metadata
        pkg: grunt.file.readJSON("package.json"),

        banner:
        "/*\n" +
        "Theme Name: <%= pkg.title %>\n" +
        "Theme URI: <%= pkg.homepage %>\n" +
        "Description: <%= pkg.description %>\n" +
        "Author: <%= pkg.author.name %>\n" +
        "Author URI: <%= pkg.author.url %>\n" +
        "Version: <%= pkg.version %>\n" +
        "License: Private\n" +
        "\n" +
        "Copyright (c) <%= grunt.template.today(\"dd-mm-yyyy\") %> <%= pkg.author.name %>\n" +
        "*/\n",

        // Observação de mudanças nos arquivos
        watch: {
            options: {
                livereload: false
            },
            css: {
                files: ["<%= dirs.sass %>/{,*/}*.{scss,sass}"],
                tasks: ["compass", "concat", "cssmin"]
            },
            js: {
                files: ["<%= jshint.all %>"],
                tasks: ["jshint", "uglify"]
            },
            html: {
                files: [
                    // carregamento automático do browser para as atualizações das extensões abaixo
                    "/*.{html,htm,shtml,shtm,xhtml,php,jsp,asp,aspx,erb,ctp}"
                ]
            }
        },

        // Compilacão de arquivos Sass/Scss para CSS
        compass: {
            dist: {
                options: {
                    force: true,

                    relativeAssets: true,
                    httpPath: "/",
                    cssDir: "<%= dirs.css %>",
                    sassDir: "<%= dirs.sass %>",
                    imagesDir: "<%= dirs.img %>",
                    javascriptsDir: "<%= dirs.js %>",
                    fontsDir: "<%= dirs.font %>",

                    noLineComments: true,

                    outputStyle: "compressed",
                    environment: "production"
                    //config: 'config.rb'
                }
            }
        },

        // Validação de arquivos
        jshint: {
            all: [
                "Gruntfile.js",
                "<%= dirs.js %>/javascript.js"
            ]
        },

        // Minificação e concatenação de arquivos
        uglify: {
            options: {
                force: true,
                mangle: false,
                banner: "<%= banner %>"
            },
            dist: {
                files: {
                    "javascript.js": [
                        'features/jqueryui/js/jquery-ui-1.10.3.custom.min.js',
                        'features/bootstrap/js/bootstrap.min.js',
                        'features/bootstrapModal/bootstrap-modal.js',
                        'features/bootstrapModal/bootstrap-modalmanager.js',
                        'features/prettyPhoto/js/jquery.prettyPhoto.js',
                        'features/scrollbar/jquery.mousewheel.min.js',
                        'features/scrollbar/jquery.mCustomScrollbar.js',
                        'functions/ClassInstagram/instafeed.min.js',
                        'functions/ClassInstagram/feed_instagram.js',
                        "<%= dirs.js %>/javascript.js"
                    ]
                }
            }
        },

        concat: {
            css: {
                src: [
                    'features/jqueryui/css/theme/jquery-ui-1.10.3.custom.min.css',
                    'features/bootstrap/css/bootstrap.min.css',
                    'features/bootstrap/css/bootstrap-responsive.min.css',
                    'features/bootstrapModal/bootstrap-modal.css',
                    'features/fontawesome/css/font-awesome.css',
                    'features/prettyPhoto/css/prettyPhoto.css',
                    'features/scrollbar/jquery.mCustomScrollbar.css',
                    'assets/css/style.css'
                    ],
                dest: 'style.css'
            }
        },

        cssmin: {
            add_banner: {
                options: {
                    banner: "<%= banner %>",
                    keepSpecialComments: "0"
                },
                files: {
                    'style.css': ['style.css']
                }
            }
        },

        // Otimização de imagens
        imagemin: {
            dist: {
                options: {
                    optimizationLevel: 3,
                    progressive: true
                },
                files: [{
                    expand: true,
                    cwd: "<%= dirs.img %>/",
                    src: "<%= dirs.img %>/**",
                    dest: "<%= dirs.img %>/"
                }]
            }
        }

    };


    // Iniciando as configurações do Grunt
    grunt.initConfig(appConfig);


    // Registrando as tarefas
    // --------------------------

    grunt.registerTask( "default", ['compass', 'concat', 'cssmin', 'jshint', 'uglify', 'imagemin']);

    // Minify CSS
    grunt.registerTask( "css", [ 'compass', 'concat', 'cssmin' ]);

    // Minify JS
    grunt.registerTask( "js", [ 'jshint', 'uglify', 'cssmin' ]);

    // Minify image
    grunt.registerTask( "image", [ "imagemin" ] );

};