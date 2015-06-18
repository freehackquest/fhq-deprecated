sync = require 'browser-sync'
notify = require 'gulp-notify'
browserify = require 'browserify'
source = require 'vinyl-source-stream'
watchify = require 'watchify'
gulpif = require 'gulp-if'
gulp = require 'gulp'

ignore = require 'gulp-ignore'
stylus = require 'gulp-stylus'
cmq = require 'gulp-combine-media-queries'
autoprefixer = require 'gulp-autoprefixer'
cssmin = require 'gulp-cssmin'
sourcemaps = require 'gulp-sourcemaps'
rename = require 'gulp-rename'
plumber = require 'gulp-plumber'

streamify = require 'gulp-streamify'
uglify = require 'gulp-uglify'

# production = false
# or you can get it with yargs or another the simular thing
args = require('yargs').argv
production = args.p or args.production

paths =
  stylus: './src/stylus/main.styl'
  browserify: './src/js/app.coffee'
  output: 'app.js'
  dest: './dest/'

gulp.task 'watch', ['browser-sync', 'watchjs', 'watchstylus']

gulp.task 'default', ['browserify', 'stylus']

buildScript = (files, watch) ->
  rebundle = (callback) ->
    stream = bundler.bundle()
    stream
      .on "error", notify.onError         # optional (for gulp-notify)
        title: "Compile Error"            #
        message: "<%= error.message %>"   #
      .pipe source paths.output
      .pipe gulpif production, streamify do uglify # optional (for gulp-uglify)
      .pipe gulpif production, rename (path) -> path.basename += '.min'
      .pipe gulp.dest paths.dest
      .pipe sync.reload stream: true      # optional (for browser-sync)

    stream.on 'end', ->
      do callback if typeof callback == "function"

  props = watchify.args
  props.entries = files
  props.debug = not production

  bundler = if watch then watchify(browserify props) else browserify props
  bundler.transform "coffee-reactify" # "coffeeify" or whatever or comment it
  bundler.on "update", ->
    now = new Date().toTimeString()[..7]
    console.log "[#{now.gray}] Starting #{"'browserify'".cyan}..."
    startTime = new Date().getTime()
    rebundle ->
      time = (new Date().getTime() - startTime) / 1000
      now = new Date().toTimeString()[..7]
      console.log "[#{now.gray}] Finished #{"'browserify'".cyan} after #{(time + 's').magenta}"

  rebundle()

gulp.task 'browserify', ->                 # compile (slow)
  buildScript paths.browserify, false

gulp.task 'watchjs', ->                    # watch and compile (first time slow, after fast)
  buildScript paths.browserify, true

gulp.task 'browser-sync', ->
  sync
    notify: false
    open: false
    server:
      baseDir: './dest'
    snippetOptions: rule:
      match: /<\/body>/i
      fn: (snippet, match) ->
        snippet + match

gulp.task 'watchstylus', ->
  gulp.watch '**/*.styl', ['stylus']

gulp.task 'stylus', ->
  gulp.src paths.stylus
    .pipe plumber errorHandler: notify.onError "Error: <%= error.message %>"
    .pipe stylus
      'include css': true
      compress: production
    .pipe gulpif production, cmq()
    .pipe autoprefixer browsers: ['last 2 version', '> 1%']
    .pipe gulpif production, cssmin()
    .pipe gulpif production, rename (path) -> path.basename += '.min'
    .pipe gulp.dest paths.dest
    .pipe sync.reload stream: true

