#cat index.html | sed -e "s/main.css?v=[0-9]*/main.css?v=`date +%s`/g" -e "s/main.js?v=[0-9]*/main.js?v=`date +%s`/g" > index.html

cat index-promo.php | sed -e "s/main.css?v=[0-9]*/main.css?v=`date +%s`/g" -e "s/main.js?v=[0-9]*/main.js?v=`date +%s`/g" > index-promo.php

cat index-promo-admin.php | sed -e "s/main.css?v=[0-9]*/main.css?v=`date +%s`/g" -e "s/main.js?v=[0-9]*/main.js?v=`date +%s`/g" > index-promo-admin.php
cat index-promo-admin.php | sed -e "s/admin.css?v=[0-9]*/admin.css?v=`date +%s`/g" -e "s/admin.js?v=[0-9]*/admin.js?v=`date +%s`/g" > index-promo-admin.php