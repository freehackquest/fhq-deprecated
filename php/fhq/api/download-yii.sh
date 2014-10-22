#!/bin/bash

rm -rf framework
git clone https://github.com/yiisoft/yii.git yii_temp
mv yii_temp/framework framework
rm -rf yii_temp
