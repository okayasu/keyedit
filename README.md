keyedit
=======

add ssh public key to authorized_keys from WebUI

setup
-------
copy gitkey.php to your web server.
edit path to sudo command if necessary.

copy keyedit.sh to user directory who want to edit authorized_keys.

add sudoers setting like below

  httpd ALL=(targetuser,root) NOPASSWD: ~targetuser/keyedit.sh
