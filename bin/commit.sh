#!/bin/sh
source venv/bin/activate
bin/generate-mkdocs.php
mkdocs build
cp -rf site/* ../pebble-framework/docs/ 
cd ../pebble-framework 
git add . && git commit -m "docs" && git push

