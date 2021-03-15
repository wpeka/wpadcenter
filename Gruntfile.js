module.exports = function( grunt ) {
    'use strict';

    const pkg = grunt.file.readJSON( 'package.json' );

    grunt.initConfig( {

        pkg,

        clean: {
            release: [ 'release/' ],
        },

        copy: {
            release: {
                files: [
                    {
                        expand: true,
                        src: [
                            'wpadcenter.php',
                            'LICENSE.txt',
                            'admin/**',
                            'analytics/**',
                            'images/**',
                            'includes/**',
                            'languages/**',
                            'public/**',
                            'README.txt',
                            '!**/*.css.map',
                            '!**/*.js.map',
                            '!**/*.{ai,eps,psd}'
                        ],
                        dest: 'release/<%= pkg.name %>',
                    },
                ],
            },
        },

        compress: {
            wpadcenter: {
                options: {
                    archive: 'release/wpadcenter.zip',
                },
                files: [
                    {
                        cwd: 'release/<%= pkg.name %>/',
                        dest: '<%= pkg.name %>/',
                        src: [ '**' ],
                    },
                ],
            },
        },

        replace: {
            readme: {
                src: 'readme.*',
                overwrite: true,
                replacements: [
                    {
                        from: /^(\*\*|)Stable tag:(\*\*|)(\s*?)[a-zA-Z0-9.-]+(\s*?)$/mi,
                        to: '$1Stable tag:$2$3<%= pkg.version %>$4',
                    },
                    {
                        from: /Tested up to:(\s*?)[a-zA-Z0-9\.\-\+]+$/m,
                        to: 'Tested up to:$1' + pkg.tested_up_to,
                    },
                ],
            },
            languages: {
                src: 'languages/wpadcenter.pot',
                overwrite: true,
                replacements: [
                    {
                        from: /(Project-Id-Version: WP AdCenter )[0-9\.]+/,
                        to: '$1' + pkg.version,
                    },
                ],
            },
        },

        shell: {
            build: [ 'npm run build' ].join( ' && ' ),
            translations: [ 'npm run makepot' ].join( ' && ' ),
        },

    } );

    require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );

    grunt.registerTask( 'build', [ 'shell:build', 'update-pot', 'replace', 'clean:release', 'copy:release', 'compress' ] );
    grunt.registerTask( 'update-pot', [ 'replace:languages' ] );
    grunt.registerTask( 'version', [ 'replace' ] );
};
