
##Qemu manager on php

#Depends: qemu-kvm apache2 php (5 or up)

#For install : 
-Download index.php and move in /var/www/html/

-Open index.php and edit variables.

-Run apache2 service (or exec cd /var/www/html/ ; nohup php -S 0.0.0.0:80 2> /dev/null | cat > /dev/null &).

-Open browser in http://localhost (or your ip adress).

-Enjoy :D

-
