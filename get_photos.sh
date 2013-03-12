#!/bin/bash
# SCRIPT:  method1.sh
# PURPOSE: Process a file line by line with PIPED while-read loop.

FILENAME=photos.csv
count=0
cat $FILENAME | while read LINE
do
 let count++
  if [ "$count" -gt 6514 ]; then
   # echo "$LINE" | `cut -d ; -f 2,3`
   PHOTOURL=`echo "$LINE" | awk 'BEGIN { FS=";"; OFS=";"; } {print $5}' -|sed -e s/_6\.jpg/_7\.jpg/g`
   PHOTOFILENAME=`echo "$LINE" | awk 'BEGIN { FS=";"; OFS=";"; } {print $1,$2,$6,$11}' - |grep L$|sed -e "s/;/_/g"|sed -e "s/_NULL//g"|sed -e "s/ /-/g"|sed -e "s/$/.jpg/"`
   echo "downloading $PHOTOURL  -->  $PHOTOFILENAME"
   `wget --wait=1 -O photos/$PHOTOFILENAME $PHOTOURL`
  fi
done

echo -e "\nTotal $count Lines read"