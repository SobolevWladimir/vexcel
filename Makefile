# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc

# Configs
PARALLEL_NUM        ?= 6
PHP_MEMORY_LIMIT    ?= 2G
PHPCPD_MIN_LINES    ?= 20
FLOW_ID             := $(shell date +"%H%M%S")


PHP_CONT = php

PATH_ROOT    ?= `pwd`
PATH_BUILD   ?= $(PATH_ROOT)/build


PHP_BIN      ?= php
PHP_BIN_CONF ?= XDEBUG_MODE=coverage    \
    $(PHP_BIN)                          \
    -d max_execution_time=900           \
    -d memory_limit=$(PHP_MEMORY_LIMIT) \
    -d error_reporting=~E_DEPRECATED


VENDOR_BIN          ?= $(PHP_BIN_CONF) $(PATH_ROOT)/vendor/bin

PHPUNIT  		= $(PHP_CONT) ./vendor/bin/phpunit


## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

test-phpunit:  ## phpunit test
	$(call title,"PHPUnit tests")
	@$(PHPUNIT) --testdox tests

#### PHP-CS-Fixer - Static Analysis Tool ###############################################################################
test-phpcsfixer: ##@Testing PHP-CS-Fixer - Checking code to follow standards
	$(call title,"PHP-CS-Fixer checking code to follow standards")
	@echo "Src Path: $(PATH_SRC)"
	@PHP_CS_FIXER_IGNORE_ENV=1 $(VENDOR_BIN)/php-cs-fixer fix  \
        --config="$(PATH_ROOT)/.php-cs-fixer.php"              \
        --dry-run                                              \
        --diff                                                 

test-phpcsfixer-fix: ##@CI PHP-CS-Fixer - Auto fix code to follow standards
	$(call title,"Fix Coding Standards with PHP-CS-Fixer")
	@echo "Src Path: $(PATH_SRC)"
	@PHP_CS_FIXER_IGNORE_ENV=1 $(VENDOR_BIN)/php-cs-fixer fix  \
        --config="$(PATH_ROOT)/.php-cs-fixer.php"              \
        -vvv


test-phpcsfixer-diff: ##@Testing PHP-CS-Fixer - Checking code to follow standards (diff output)
	$(call title,"PHP-CS-Fixer checking code to follow standards \(diff output\)")
	@echo "Src Path: $(PATH_SRC)"
	@PHP_CS_FIXER_IGNORE_ENV=1 $(VENDOR_BIN)/php-cs-fixer fix  \
        --config="$(PATH_ROOT)/.php-cs-fixer.php"              \
        --dry-run                                              \
        --show-progress=dots                                   \
        --diff                                                 \
        -vvv

#### PHPStan - Static Analysis Tool ####################################################################################
test-phpstan: ##@Testing PHPStan - Static Analysis Tool
	$(call title,"PHPStan - Static Analysis Tool")
	@echo "Src Path: $(PATH_SRC)"
	@$(VENDOR_BIN)/phpstan analyse                                        \
        --configuration="$(PATH_ROOT)/phpstan.neon"                     \
        --memory-limit=$(PHP_MEMORY_LIMIT)                              \
				--error-format=table                                            \
        --no-ansi                                                       \
				 "$(PATH_SRC)"


