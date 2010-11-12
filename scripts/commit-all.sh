cd lib
sh ../scripts/arrumar-codigo-php.sh
sh ../scripts/orig-apagar.sh
cd ..
git add *
git commit -a
sh scripts/src-comitar.sh
