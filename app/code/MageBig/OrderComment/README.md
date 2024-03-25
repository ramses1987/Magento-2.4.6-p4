#Order Comment Extension
The Order Comment extension for Magento 2 developed by MageBig allows customers to add a special comment/notes/message 
while placing the order on the checkout page which can be viewed both in admin backend and customer account.

##Support: 
version - 2.3.x, 2.4.x

##How to install Extension

1. Download the archive file.
2. Unzip the file
3. Create a folder [Magento_Root]/app/code/MageBig/OrderComment
4. Drop/move the unzipped files to directory '[Magento_Root]/app/code/MageBig/OrderComment'

#Enable Extension:
- php bin/magento module:enable MageBig_OrderComment
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy
- php bin/magento cache:flush

#Disable Extension:
- php bin/magento module:disable MageBig_OrderComment
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy
- php bin/magento cache:flush
#Uninstall Data:
- php bin/magento module:uninstall MageBig_OrderComment
