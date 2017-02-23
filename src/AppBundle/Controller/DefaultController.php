<?php

namespace AppBundle\Controller;

use AppBundle\AppController;
use AppBundle\Constants\ViewConstant;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AppController
{

    /**
     * Loads the homepage
     *
     * @return Response an Http response object
     */
    public function indexAction()
    {

        return $this->render( ViewConstant::HOME_PAGE );

    }


}
