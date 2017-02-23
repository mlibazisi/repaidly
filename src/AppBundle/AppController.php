<?php

namespace AppBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Constants\HttpCodeConstant;

abstract class AppController extends Controller
{

    /**
     * Returns a JSON HTTP response object
     *
     * @param mixed $data the response body
     * @param int $code an http status response code
     * @param array $headers configures http headers
     *
     * @return JsonResponse a JSON HTTP response object
     */
    protected function _ajaxResponse( $data = null, $code = HttpCodeConstant::HTTP_OK, $headers = [] ) {

        $status = ( !is_numeric( $code )
            || $code >= HttpCodeConstant::HTTP_BAD_REQUEST )
            ? 'error'
            : 'success';

        $data = [
            "status" => $status,
            "code"   => $code,
            "data"   => $data
        ];

        return new JsonResponse( $data, $code, $headers );

    }

}
