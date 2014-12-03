<?php

namespace SocialTracker\Bundle\ApplicationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SocialTrackerApplicationBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
