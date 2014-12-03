#!/bin/bash

git rev-list HEAD --count . > base/version.tex
pdflatex fhq-api
