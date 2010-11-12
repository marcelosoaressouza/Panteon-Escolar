DIR=`pwd`

for FILE in `find $DIR`; do
  if [ -d $FILE ]
  then
    echo "Diretorio"
    chmod a+rx $FILE
  else
    echo "Arquivo"
    chmod a+r $FILE
    chmod a-x $FILE
  fi

done

