#!/bin/bash
git checkout master
git merge --no-ff develop
git push
git checkout develop
