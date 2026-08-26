<?php

namespace App\Controller;

use App\Entity\Availabilities;
use App\Entity\User;
use App\Entity\Booking;
use App\Form\BookingFormType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookingController extends AbstractController
{
    #[Route(path: '/booking/new/{id}', name: 'app_book_availability')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function bookAnAvailability(Availabilities $availabilities, Request $request, EntityManagerInterface $entityManager, BookingRepository $bookingRepository)
    {
        if ($bookingRepository->isAvailabilityTaken($availabilities)) {
            $this->addFlash('error', 'Ce créneau est déjà réservé');
            return $this->redirectToRoute('app_show_business', ['id' => $availabilities->getBusiness()->getId()]);
        }
        $booking = new Booking();

        $user = $this->getUser();
        $booking->setClient($user);
        $booking->setAvailabilites($availabilities);
        $booking->setIsValidated(false);

        $form = $this->createForm(BookingFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation du créneau validé');
            return $this->redirectToRoute('app_show_business', ['id' => $availabilities->getBusiness()->getId()]);
        }
        return $this->render('booking/create.html.twig', [
            'bookingForm' => $form,
            'availability' => $availabilities,
        ]);
    }
}
