<?php

namespace LoginBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LoginBundle extends Bundle
{
    public function getParent()
{
    return 'FOSUserBundle';
}
}
