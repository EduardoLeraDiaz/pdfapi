<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends FOSRestController
{
    
    public function getDocumentsAction()
    {
        return json_encode(
            [
                'Documents' => [
                    [
                        'id'=>'1',
                        'title' => 'Probe title',
                        'description' => ' probe description',
                    ]
                ]
            ]
        );
    }

    public function postDocumentAction()
    {

    }

    public function deleteDocumentAction($id)
    {

    }

    public function indexAction() {
        return new response("hello world");
    }
}