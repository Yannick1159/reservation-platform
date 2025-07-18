<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserTypeForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {

    }

    #[Route('/register', name: 'app_register')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(UserTypeForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $this->validation($user, $form);

            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }

                return $this->redirectToRoute('app_register');
            }

            $plaintextPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Registration successful!');
            return $this->redirectToRoute('app_register');
        }

        return $this->render('auth/registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function validation(User $user, FormInterface $form): array
    {
        $errors = [];

        if ($this->userRepository->findBy(['email' => $user->getEmail()])) {
            $errors[] = 'This email is already taken!';
        }

        $plaintextPassword = $form->get('password')->getData();
        if ($plaintextPassword !== $form->get('password_repeat')->getData()) {
            $errors[] = 'Passwords do not match!';
        }

        return $errors;
    }
}
