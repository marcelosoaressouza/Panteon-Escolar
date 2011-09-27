for file in `find .` ; do

  if [ ! -d "$file" ]; then
     echo "Arquivo" $file

     chmod a+rwx $file
     chmod a-x $file
     chmod o-w $file
  else
     chmod a+rwx $file
     chmod o-w $file

  fi

done
