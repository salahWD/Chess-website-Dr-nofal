let gulp = require("gulp");
let concat = require("gulp-concat");
let prefixer = require("gulp-autoprefixer");
let sass = require('gulp-sass')(require('sass'));

// css task
gulp.task("css", () => {
  return gulp
    .src("./scss/*")
    .pipe(sass({ outputStyle: "compressed" }))
    .pipe(prefixer("last 2 versions"))
    .pipe(gulp.dest("./css"));
});

// javascript task
// gulp.task("js", () => {
//   return gulp
//     .src("project/js/*.js")
//     .pipe(concat("all.js"))
//     .pipe(gulp.dest("dist"));
// });

// watch the file task
gulp.task("watch", () => {
  console.log("watching...");
  gulp.watch("./scss/**/*.scss", gulp.series("css"));
});
