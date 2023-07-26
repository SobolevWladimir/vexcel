PHP_CONT = php

PHPUNIT  		= $(PHP_CONT) ./vendor/bin/phpunit

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

test-phpunit:  ## phpunit test
	$(call title,"PHPUnit tests")
	@$(PHPUNIT) --testdox tests

example-compile:
	clang++ -g -O3 toy.cpp `llvm-config --cxxflags`

example-run:
	./a.out

