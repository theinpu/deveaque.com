#!/bin/sh
cd /home/deveaque/GoogleDrive/
#./grive -f -l out.log only download photoes, not sync
./grive -l out.log.txt # sync photoes

echo "move files to Content"
mv /home/deveaque/GoogleDrive/\[content\]/ToPost/* /home/deveaque/Content

echo "cron works" > out.cron.txt

cd /home/deveaque/scripts

cat /home/deveaque/GoogleDrive/.grive_state |  tr ", " "\n" | grep 138 > last.update

date --date='@`cat /home/deveaque/GoogleDrive/last.update' > last.update.date
