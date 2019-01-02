module.exports = function( grunt ) {
 
  grunt.initConfig({
 
    uglify : {
      options : {
        mangle : false
      },
 
      my_target : {
        files : {
          'site/templates/blog-lci/js/blog-lci.js' : [ 'site/templates/blog-lci/js/scripts.js' ]
        }
      }
    }, // uglify
 
 
 
    sass : {
      dist : {
        options : { style : 'compressed' },
        files : {
          'site/templates/blog-lci/css/template.css' : 'site/templates/blog-lci/sass/template.scss'
        }
      }
    }, // sass


    imagemin: {                          // Task
	    dynamic: {                         // Another target
	      files: [{
	        expand: true,                  // Enable dynamic expansion
	        cwd: 'site/templates/blog-lci/_images/',                   // Src matches are relative to this path
	        src: ['**/*.{png,jpg,gif}'],                                 // Actual patterns to match
	        dest: 'site/templates/blog-lci/images/'                  // Destination path prefix
	      }]
	    }
	  }, // imagemin

 
    watch : {
      dist : {
        files : [
          'site/templates/blog-lci/js/**/*',
          'site/templates/blog-lci/sass/**/*',
          'site/templates/blog-lci/_images/**/*'
        ],
 
        tasks : [ 'uglify', 'sass', 'imagemin' ]
      }
    } // watch
 
  });
 
 
  // Plugins do Grunt
  grunt.loadNpmTasks( 'grunt-contrib-uglify' );
  grunt.loadNpmTasks( 'grunt-contrib-sass' );
  grunt.loadNpmTasks('grunt-contrib-imagemin');
  grunt.loadNpmTasks( 'grunt-contrib-watch' );
 
  // Tarefas que serão executadas
  grunt.registerTask( 'default', [ 'uglify', 'sass', 'imagemin' ] );
 
  // Tarefa para Watch
  grunt.registerTask( 'w', [ 'watch' ] );
 
};
