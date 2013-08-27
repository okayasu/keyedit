#!/bin/sh

distfile="${HOME}/.ssh/authorized_keys"

case $1 in
-s)
	grep "^# $2 " ${distfile}
	;;
-w)
	time=`/bin/date "+%Y-%m-%d %H:%M:%S"`
	/bin/echo "# $2 $time" >> ${distfile}
	/bin/echo $3 >> {distfile}
	;;
--help)
	echo "usage: keyedit.sh [options]"
	echo "  -s name      show name and added time."
	echo "  -w name key  add key with name."
	echo "  --help       show usage."
	echo "  empty        show all names and added time."
	;;
*)
	grep '^#' ${distfile}
	;;
esac
