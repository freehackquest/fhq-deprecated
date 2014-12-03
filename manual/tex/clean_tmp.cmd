@ECHO OFF

ECHO delete temporary files
DEL /S/Q *.~* 
DEL /S/Q *.log
DEL /S/Q *.aux
DEL /S/Q *.out
DEL /S/Q *.toc
DEL /S/Q *.synctex.gz

