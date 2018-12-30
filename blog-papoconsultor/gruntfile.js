module.exports = function( grunt ) {
 
  grunt.initConfig({
 
    uglify : {
      options : {
        mangle : false
      },
 
      my_target : {
        files : {
          'site/templates/blogpapodeconsultor/js/blogpapodeconsultor.js' : [ 'site/templates/blogpapodeconsultor/js/scripts.js' ]
        }
      }
    }, // uglify
 
 
 
    sass : {
      dist : {
        options : { style : 'compressed' },
        files : {
          'site/templates/blogpapodeconsultor/css/template.css' : 'site/templates/blogpapodeconsultor/sass/template.scss'
        }
      }
    }, // sass


    imagemin: {                          // Task
	    dynamic: {                         // Another target
	      files: [{
	        expand: true,                  // Enable dynamic expansion
	        cwd: 'site/templates/blogpapodeconsultor/_images/',                   // Src matches are relative to this path
	        src: ['**/*.{png,jpg,gif}'],                                 // Actual patterns to match
	        dest: 'site/templates/blogpapodeconsultor/images/'                  // Destination path prefix
	      }]
	    }
	  }, // imagemin

 
    watch : {
      dist : {
        files : [
          'site/templates/blogpapodeconsultor/js/**/*',
          'site/templates/blogpapodeconsultor/sass/**/*',
          'site/templates/blogpapodeconsultor/_images/**/*'
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
