#!/bin/sh

DIR=`pwd`

for file in `find $DIR | grep ".orig$"`;
do
  echo $file
  rm $file
done


