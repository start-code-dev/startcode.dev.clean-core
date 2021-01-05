<?php

namespace Startcode\CleanCore\Service;

interface ServiceInterface
{
    public function getFormatterInstance();

    public function getMeta();

    public function getUseCaseInstance();
}
