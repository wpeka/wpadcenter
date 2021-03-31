/**
 * Grunt Tasks JavaScript.
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter
 * @author     WPeka <https://club.wpeka.com/>
 */

module.exports = function( grunt ) {
    'use strict';

    grunt.initConfig(
        {

        pkg: grunt.file.readJSON( 'package.json' ),

        clean: {
            build: ['release/<%= pkg.version %>']
        },

        uglify: {
            options: {

            },
            admin: {
                files: [{
                    expand: true,
                    cwd: 'release/<%= pkg.version %>/admin/js/',
                    src: [
                        '*.js',
                        '!*.min.js'
                    ],
                    dest: 'release/<%= pkg.version %>/admin/js/',
                    ext: '.min.js'
                }]
            },
            frontend: {
                files: [{
                    expand: true,
                    cwd: 'release/<%= pkg.version %>/public/js/',
                    src: [
                        '*.js',
                        '!*.min.js'
                    ],
                    dest: 'release/<%= pkg.version %>/public/js/',
                    ext: '.min.js'
                }]
            },
        },
        cssmin: {
            options: {

            },
            admin: {
                files: [{
                    expand: true,
                    cwd: 'release/<%= pkg.version %>/admin/css/',
                    src: [
                        '*.css',
                        '!*.min.css'
                    ],
                    dest: 'release/<%= pkg.version %>/admin/css/',
                    ext: '.min.css'
                }]
            },
            frontend: {
                files: [{
                    expand: true,
                    cwd: 'release/<%= pkg.version %>/public/css/',
                    src: [
                        '*.css',
                        '!*.min.css'
                    ],
                    dest: 'release/<%= pkg.version %>/public/css/',
                    ext: '.min.css'
                }]
            },
        },
        copy: {
            build: {
                options: {
                    mode: true,
                    expand: true,
                },
                src: [
                    '**',
                    '!admin/js/gutenberg-blocks/*.php',
                    '!node_modules/**',
                    '!release/**',
                    '!tests/**',
                    '!build/**',
                    '!src/**',
                    '!.git/**',
                    '!.github/**',
                    '!bin/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!package-lock.json',
                    '!.gitignore',
                    '!.gitmodules',
                    '!composer.lock',
                    '!composer.json',
                    '!*.yml',
                    '!*.xml',
                    '!*.config.*'
                ],
                dest: 'release/<%= pkg.version %>/'
            }
        },
        compress: {
            build: {
                options: {
                    mode: 'zip',
                    archive: './release/<%= pkg.name %>.<%= pkg.version %>.zip'
                },
                expand: true,
                cwd: 'release/<%= pkg.version %>/',
                src: ['**/*'],
                dest: '<%= pkg.name %>'
            }
        },

        addtextdomain: {
            options: {
                textdomain: 'wpadcenter',
            },
            update_all_domains: {
                options: {
                    updateDomains: true
                },
                src: ['*.php', '**/*.php', '!\.git/**/*', '!\.github/**/*', '!bin/*', '!src/**/*', '!src/*', '!node_modules/**/*', '!tests/**/*', '!vendor/**/*', '!analytics/*', '!analytics/**/*']
            }
        },

        wp_readme_to_markdown: {
            your_target: {
                files: {
                    'README.md': 'README.txt'
                }
            },
        },

        makepot: {
            target: {
                options: {
                    domainPath: '/languages',
                    exclude: ['\.git/*', '\.gitbook/*', 'bin/*', 'node_modules/*', 'tests/*', 'vendor/**/*', 'analytics/**/*', 'src/**/*'],
                    mainFile: 'wpadcenter.php',
                    potFilename: 'wpadcenter.pot',
                    potHeaders: {
                        poedit: true,
                        'x-poedit-keywordslist': true
                    },
                    type: 'wp-plugin',
                    updateTimestamp: true
                }
            }
        },

        shell: {
            build: [ 'npm run build' ].join( ' && ' ),
            buildguten: [ 'npm run build:gutenbergblocks' ].join( ' && ' ),
            translations: [ 'npm run makepot' ].join( ' && ' ),
        },

    } );

    require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );

    grunt.registerTask( 'default', ['i18n', 'readme'] );
    grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
    grunt.registerTask( 'readme', ['wp_readme_to_markdown'] );
    grunt.registerTask( 'compile', ['shell:build'] );
    grunt.registerTask( 'build', ['shell:build', 'shell:buildguten', 'clean:build', 'copy:build', 'uglify:admin', 'uglify:frontend', 'cssmin:admin', 'cssmin:frontend', 'compress:build'] );
    grunt.util.linefeed = '\n';
};
