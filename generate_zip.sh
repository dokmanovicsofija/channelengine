#!/bin/bash

MODULE_NAME="channelengine"

VERSION="1.0.0"

ZIP_NAME="${MODULE_NAME}-${VERSION}.zip"

zip -r $ZIP_NAME . -x \
"*.git/*" \
"*.idea/*" \
"*.gitignore" \
"composer.json" \
"composer.lock" \

echo "ZIP arhiva kreirana: $ZIP_NAME"