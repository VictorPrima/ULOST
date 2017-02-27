<?php

namespace Ulost\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UlostUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
