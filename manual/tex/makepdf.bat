@echo off
git rev-list HEAD --count . > base/version.tex
pdflatex manual
mv manual.pdf ../
