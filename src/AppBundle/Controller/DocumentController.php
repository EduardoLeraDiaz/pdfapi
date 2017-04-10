<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Document;

class DocumentController extends FOSRestController
{

    /**
     * List the avaible documents
     */
    public function getDocumentsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $documents = [
            'documents' => $em->getRepository('AppBundle:Document')->getAvaibleDocuments()
        ];

        return new response(json_encode($documents), 200);
    }

    /**
     * create a document
     *
     */
    public function postDocumentAction(Request $request)
    {
        /**
         * var Serializer
         */
        return new Response('{"created":"true"}',200);
        $serializer = $this->get('serializer');
        $document = $serializer->deserialize($request->get('data'), Document::class, 'json');

        $uploadFile = $request->files->get('document');
        if (!is_null($uploadFile)) {
            $content = file_get_contents($uploadFile->getPathname());
            $document->setDocument($content);
        }

        /**
         * var validator
         */
        /**
         * TODO:
         * prove if the content of the document is a valid pdf
         */
        $validator = $this->get('validator');
        $errors = $validator->validate($document);

        if (count($errors) > 0) {
            return $this->view(['error' => (string)$errors], 400);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($document);
        $em->flush();

        return $this->view(['created' => 'true'], 200);
    }

    /**
     * @param Request
     * @return View
     */
    public function deleteDocumentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('AppBundle:Document')->findOneById($id);
        if (empty($document)) {
            return $this->view([
                'error' => 'Document not found'
            ], 400);
        }

        $em->remove($document);
        $em->flush();

        return $this->view([
            'deleted' => 'true'
        ], 200);
    }

    public function getDocumentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('AppBundle:Document')->findOneById($id);
        if (empty($document)) {
            return $this->view([
                'error' => 'Document not found'
            ], 400);
        }
        $content = stream_get_contents($document->getDocument());
        $response = new Response();
        $response->headers->set('Content-Length', $document->getDocument());
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set("Content-type", 'application/pdf');
        $response->setContent($content);

        return $response;
    }

    public function indexAction(Request $request)
    {
        return new Response("hello world", 200);
    }
}