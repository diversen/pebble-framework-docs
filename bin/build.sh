#!/bin/sh
source venv/bin/activate
bin/generate-mkdocs.php
mkdocs build
