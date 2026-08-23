<?php

namespace App\Controller;

use App\Entity\Availabilities;
use App\Entity\Business;
use App\Entity\User;
use App\Form\AvailabilityFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AvailabilityController extends AbstractController
{
    #[Route(path: '/business/{id}/availabilities/add', name: 'app_add_availability', requirements: ['id' => '\d+'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(Business $business, Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($business->getManager()?->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException();
        }

        $availability = new Availabilities();
        $availability->setBusiness($business);

        $form = $this->createForm(AvailabilityFormType::class, $availability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($availability);
            $entityManager->flush();

            $this->addFlash('success', 'Créneau ajouté.');

            return $this->redirectToRoute('app_show_business', ['id' => $business->getId()]);
        }

        return $this->render('business/availability_add.html.twig', [
            'business' => $business,
            'availabilityForm' => $form,
        ]);
    }
}
