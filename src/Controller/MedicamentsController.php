<?php

namespace App\Controller;

use App\Entity\Medicaments;
use App\Form\MedicamentsType;
use App\Repository\MedicamentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/medicaments")
 */
class MedicamentsController extends AbstractController
{
    /**
     * @Route("/", name="medicaments_index", methods={"GET"})
     */
    public function index(MedicamentsRepository $medicamentsRepository): Response
    {
        return $this->render('medicaments/index.html.twig', [
            'medicaments' => $medicamentsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="medicaments_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $medicament = new Medicaments();
        $form = $this->createForm(MedicamentsType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($medicament);
            $entityManager->flush();

            return $this->redirectToRoute('medicaments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medicaments/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="medicaments_show", methods={"GET"})
     */
    public function show(Medicaments $medicament): Response
    {
        return $this->render('medicaments/show.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="medicaments_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Medicaments $medicament): Response
    {
        $form = $this->createForm(MedicamentsType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('medicaments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medicaments/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="medicaments_delete", methods={"POST"})
     */
    public function delete(Request $request, Medicaments $medicament): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medicament->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($medicament);
            $entityManager->flush();
        }

        return $this->redirectToRoute('medicaments_index', [], Response::HTTP_SEE_OTHER);
    }
}
