#!/bin/sh
DIR=`pwd`
X=0
Y=0
for file in `find $DIR | grep ".php$"`;
do
  X=`cat $file | wc -l`
  Y=`expr $X + $Y`
  echo $X $Y
done

echo $Y

