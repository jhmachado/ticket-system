#!/usr/bin/env bash

set -e

cd $TRAVIS_BUILD_DIR

exec 42>&1
phpunit_status=$(bin/phpunit --colors=always | tee /dev/fd/42 | tail -2)
exec 42>&-

if [[ $phpunit_status =~ '🐛 PHPUnit completed execution with failure 🐛' ]]; then
	exit 1
elif [[ $phpunit_status =~ '⌛ PHPUnit completed execution with long tests ⌛' ]]; then
	exit 1
elif [[ ! $phpunit_status =~ '🎉 PHPUnit completed execution successfully 🎉' ]]; then
	echo
	echo "💩 PHPUnit died 💩"
	exit 1
fi
