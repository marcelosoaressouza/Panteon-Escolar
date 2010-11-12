#!/bin/sh

DIR=`pwd`

for file in `find $DIR | grep ".php$"`;
do
  astyle --indent=spaces=2 --brackets=linux --indent-labels --unpad=paren --convert-tabs --indent-preprocessor $file;
done


