<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\OptionType;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/option")
 */
class AdminOptionController extends AbstractController
{
    /**
     * @Route("/", name="admin.option.index", methods={"GET"})
     * @param OptionRepository $optionRepository
     * @return Response
     */
    public function index(OptionRepository $optionRepository): Response
    {
        return $this->render('admin/option/index.html.twig', ['options' => $optionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="admin.option.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $option = new Option();
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($option);
            $entityManager->flush();

            return $this->redirectToRoute('admin.option.index');
        }

        return $this->render('admin/option/new.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.option.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Option $option
     * @return Response
     */
    public function edit(Request $request, Option $option): Response
    {
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.option.index', ['id' => $option->getId()]);
        }

        return $this->render('admin/option/edit.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.option.delete", methods={"DELETE"})
     * @param Request $request
     * @param Option $option
     * @return Response
     */
    public function delete(Request $request, Option $option): Response
    {
        if ($this->isCsrfTokenValid('delete'.$option->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($option);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.option.index');
    }
}
