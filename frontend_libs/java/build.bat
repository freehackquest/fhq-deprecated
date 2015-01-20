@echo off
echo build javac
javac -g -sourcepath src\ ^
  src\FHQCommandLine.java

echo try run
cd src
java FHQCommandLine
  