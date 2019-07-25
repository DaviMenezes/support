<?php
use Dvi\Support\Environment\Environment;
use Dvi\Support\Environment\EnvironmentEnum;
use Dvi\Component\TemplateEngine\TemplateManager;
use Symfony\Component\HttpFoundation\Session\Session;

require_once "database.php";

Environment::set(EnvironmentEnum::production());

http()->obj()->setSession(new Session());

TemplateManager::bladeOne();
