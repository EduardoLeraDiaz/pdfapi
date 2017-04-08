<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class DocumentController extends FOSRestController
{

    public function getDocumentsAction()
    {
        $responseBody = json_encode(
            [
                'documents' => [
                    [
                        'id' => '11',
                        'title' => 'Probe title',
                        'description' => ' probe description',
                    ]
                ]
            ]
        );
        return new response($responseBody);
    }

    public function postDocumentAction()
    {
        $responseBody = json_encode([
            'created' => 'true'
        ]);
        return new response($responseBody);
    }

    public function deleteDocumentAction($id)
    {
        if((int)$id<3) {
            $responseBody = json_encode([
                'deleted' => 'true'
            ]);
            return new response($responseBody);
        }
            $responseBody = json_encode([
                'error' => 'Document not found'
            ]);
        return (new response($responseBody))->setStatusCode(400);

    }

    public function indexAction()
    {
        return new response("hello world");
    }
}