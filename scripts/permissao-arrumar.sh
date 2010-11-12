#!/bin/sh

DIR=`pwd`

for FILE in `find $DIR`; do
  if [ -d $FILE ]
  then
    echo "Diretorio"
    chmod a+rx $FILE
    chmod o-w $FILE
  else
    echo "Arquivo"
    chmod a+r $FILE
    chmod a-x $FILE
  fi

done
