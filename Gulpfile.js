var gulp = require('gulp');

gulp.task('default', function() {
   gulp.src('./vendor/designmodo/Flat-UI/dist/**/*')
   .pipe(gulp.dest('./src/Nozyczki/ShortenerBundle/Resources/public/'));
});
