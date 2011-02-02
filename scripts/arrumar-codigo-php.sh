#!/bin/sh
#    astyle --style=ansi  -b --indent=spaces=4 --indent-labels --unpad=paren --convert-tabs --indent-preprocessor --add-brackets --break-blocks=all --break-closing-brackets --break-elseifs $file

DIR=`pwd`

for file in `find $DIR | grep ".php$"`;
do
  if [ -f $file ]; then
    astyle --style=ansi --indent=spaces=2 --indent-labels --unpad-paren --convert-tabs --indent-preprocessor --add-brackets --break-blocks=all --break-closing-brackets $file

    if [ -f $file.orig ]; then
      echo "Apagando: " $file.orig
      rm -f $file.orig
    fi

  fi
done
