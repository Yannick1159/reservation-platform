<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoomController extends AbstractController
{
    #[Route('/room', name: 'app_room')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomTypeForm::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($room);
            $entityManager->flush();

            $this->addFlash('success', 'Room successfully created!');
            return $this->redirectToRoute('app_room');
        }

        return $this->render('room/room.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
