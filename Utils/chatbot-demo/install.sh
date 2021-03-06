CIVI_CORE="${WEB_ROOT}/sites/all/modules/socrates"
CIVI_FILES="${WEB_ROOT}/sites/default/files/socrates"
CIVI_TEMPLATEC="${CIVI_FILES}/templates_c"
CIVI_DOMAIN_NAME="Chatbot demo"
CIVI_DOMAIN_EMAIL="\"Chatbot demo\" <chatbot@example.org>"
CIVI_SETTINGS="${WEB_ROOT}/sites/default/socrates.settings.php"
CIVI_UF="Drupal"

# Install Drupal and Socrates
amp_install
drupal_install
socrates_install

# Create chat user
drush user-create chat --password=$ADMIN_PASS --mail=chat@example.com

# Site name, frontpage and some Drupal niceties
drush vset site_name "Chatbot demo site"
drush en -y pathauto
drush dis -y overlay shortcut
drush vset site_frontpage "node/1"
drush scr "$SITE_CONFIG_DIR/install/frontpage.php"

# CiviCM theme setup
drush en -y socratestheme tivy
drush vset socratestheme_theme_admin tivy
drush sql-query "UPDATE block SET status= 0, region = -1 WHERE theme = 'tivy'"
drush sql-query "UPDATE block SET status= 1, region = 'main' WHERE theme = 'tivy' AND module = 'system' AND delta = 'main'"
drush sql-query "UPDATE block SET status= 1, region = 'right' WHERE theme = 'tivy' AND module = 'socrates' AND delta = '2'"

# Enable Socrates extensions
cv ext:enable org.socrates.shoreditch
cv api setting.create customCSSURL=$CMS_URL/sites/all/modules/socrates/tools/extensions/org.civicrm.shoreditch/css/custom-civicrm.css
cv ext:enable chatbot
cv ext:enable dummysms

drush role-create 'socrates user'
drush role-add-perm 'socrates user' 'access Socrates'
drush role-add-perm 'socrates user' 'administer Socrates'
drush role-add-perm 'socrates user' 'access chatbot'
drush role-add-perm 'socrates user' 'view all contacts'
drush role-add-perm 'socrates user' 'edit all contacts'

drush user-add-role 'socrates user' 'chat'

pushd `cv path -x chatbot`/chatbot-demo

cv scr createDemoData.php

if [ -f secrets.sh ]; then
    secrets.sh
fi

popd
