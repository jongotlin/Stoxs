<?php

namespace Stoxs\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class StoxsUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
