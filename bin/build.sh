#!/bin/sh
bin/generate-mkdocs.php
mkdocs build
cp -rf site/* docs/
