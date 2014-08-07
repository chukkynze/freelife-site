#!/bin/bash

echo "Updating mysql configs in /etc/mysql/my.cnf."
sudo sed -i "s/bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/my.cnf
echo "Updated mysql bind address in /etc/mysql/my.cnf to 0.0.0.0 to allow external connections."

echo "Assigning mysql root user access on %."
sudo mysql -u root -pfreelife123 --execute "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'freelife123' with GRANT OPTION; " freelife_db
echo "Assigned mysql user root access on all hosts."

echo "Creating mysql user freelife_dba and granting access on localhost and % for all databases."
sudo mysql -u root -pfreelife123 --execute "GRANT ALL PRIVILEGES ON freelife_db.* TO 'freelife_dba'@'localhost'    IDENTIFIED BY 'nL]Eoa!g,[4*' with GRANT OPTION; " freelife_db
sudo mysql -u root -pfreelife123 --execute "GRANT ALL PRIVILEGES ON freelife_db.* TO 'freelife_dba'@'%'            IDENTIFIED BY 'nL]Eoa!g,[4*' with GRANT OPTION; " freelife_db

sudo mysql -u root -pfreelife123 --execute "GRANT ALL PRIVILEGES ON freelife_queue.* TO 'freelife_dba'@'localhost' IDENTIFIED BY 'nL]Eoa!g,[4*' with GRANT OPTION; " freelife_db
sudo mysql -u root -pfreelife123 --execute "GRANT ALL PRIVILEGES ON freelife_queue.* TO 'freelife_dba'@'%'         IDENTIFIED BY 'nL]Eoa!g,[4*' with GRANT OPTION; " freelife_db

sudo mysql -u root -pfreelife123 --execute "GRANT ALL PRIVILEGES ON freelife_utils.* TO 'freelife_dba'@'localhost' IDENTIFIED BY 'nL]Eoa!g,[4*' with GRANT OPTION; " freelife_db
sudo mysql -u root -pfreelife123 --execute "GRANT ALL PRIVILEGES ON freelife_utils.* TO 'freelife_dba'@'%'         IDENTIFIED BY 'nL]Eoa!g,[4*' with GRANT OPTION; " freelife_db
echo "Assigned mysql user freelife_dba access on all hosts for all databases."

echo "Flushing privileges."
sudo mysql -u root -pfreelife123 --execute "FLUSH PRIVILEGES;" freelife_db
echo "Flushed privileges."

sudo service mysql stop
sudo service mysql start
