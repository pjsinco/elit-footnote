var files = {

  dev: {
    scripts: [
      'scripts/**/*.js',
    ],
    styles: [
      'sass/**/*.scss',
    ],
  },

  vendor: {
    scripts: [
      'vendor/scripts/bigfoot.min.js',
    ],
    styles: [
      'vendor/styles/bigfoot-default.scss',
    ],
  },
};

module.exports = function(grunt) {


  grunt.initConfig({

    pkg: grunt.file.readJSON('package.json'),
  
    sass: {
      options: {
        sourceMap: true,
      },
      dev: {
        files: {
          'public/styles/<%= pkg.name %>.css': [files.vendor.styles, files.dev.styles],
        },
      },
    },

    concat: {
      options: {
          //stripBanners: true,
      },
      all: {
        //src: [files.vendor.scripts, files.dev.scripts],
        src: [files.dev.scripts],
        dest: './public/scripts/<%= pkg.name %>.js',
      },
    },
  
    uglify: {
      options: {
        banner: '/* <%= pkg.name %> - v<%= pkg.version %> */'
      },
      dist: {
        files: {
          './public/scripts/<%= pkg.name %>.min.js': ['<%= concat.all.dest %>']
        }
      }
    },

    notify: {
      sass: {
        options: {
          title: 'Sass',
          message: 'Sassed!',
        },
      },
      scripts: {
        options: {
          title: 'Scripts',
          message: 'Processed!',
        }
      }
    },

    autoprefixer: {
      css: {
        src: 'public/styles/<%= pkg.name %>.css',
        options: {
          browsers: [
            '> 1%',
            'last 2 versions',
            'Firefox ESR',
            'iOS >= 7',
            'ie >= 10'
          ],
        },
      },
    },

    watch: {
      options: {
        livereload: true,
      },

      scripts: {
        //files: [files.vendor.scripts, files.dev.scripts],
        files: [files.dev.scripts],
        tasks: ['concat', 'uglify', 'notify:scripts'],
      },
      sass: {
        files: [files.vendor.styles, files.dev.styles],
        tasks: ['sass:dev', 'notify:sass', 'autoprefixer:css' ],
      },
    },

  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-notify');
  grunt.loadNpmTasks("grunt-autoprefixer");

  grunt.registerTask('scripts-dev', ['concat']);
  grunt.registerTask('scripts-dist', ['concat', 'uglify']);
  grunt.registerTask('compile-sass', ['sass:dev', 'notify:sass']);
  grunt.registerTask('default', ['watch']);
};
