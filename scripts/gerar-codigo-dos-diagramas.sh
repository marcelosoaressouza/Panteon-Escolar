for file in *.dia ; do dia2code -l licensa -t php5 $file ; done

rm BaseModel*
rm BaseModule*
rm PanteonEscolar*

for file in *.php ; do mv $file `echo $file | sed 's/\(.*\.\)php/\1class.php/'` ; done

mv *DB*.php tmp/lib/classes/db
mv *Model*.php tmp/lib/classes/model

for file in *.php ; do mv $file `echo $file | tr '[A-Z]' '[a-z]'` ; done

mv *.php tmp/lib/modules

