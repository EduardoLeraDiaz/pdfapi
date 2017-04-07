<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class DocumentPDFController extends FOSRestController
{
    public function getDocumentPDFsAction()
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

    public function postDocumentPDFAction()
    {

    }

    public function deleteDocumentPDFAction($id)
    {

    }

    public function indexAction() {
        return $this->render("hello world");
    }
}