<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/picture")
 * Class AdminPictureController
 * @package App\Controller\Admin
 */
class AdminPictureController extends AbstractController
{
    /**
     * @Route("/{id}", name="admin.picture.delete", methods="DELETE")
     * @param Picture $picture
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Picture $picture, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $data['_token'])) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($picture);
            $em->flush();
            return new JsonResponse(['success' => 1]);
        }
        return new JsonResponse(['error' => 'Token Invalid'], 400);
    }
}