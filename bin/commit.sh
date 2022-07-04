#!/bin/sh
cp -rf site/* ../pebble-framework/docs/ 
cd ../pebble-framework 
git add . && git commit -m "docs" && git push
