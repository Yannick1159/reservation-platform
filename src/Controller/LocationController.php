<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LocationController extends AbstractController
{
    #[Route('/location', name: 'app_location')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationTypeForm::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Location successfully created!');
            return $this->redirectToRoute('app_location');
        }

        return $this->render('location/location.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
