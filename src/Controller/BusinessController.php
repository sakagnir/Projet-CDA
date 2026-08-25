<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\User;
use App\Form\BusinessFormType;
use App\Repository\AvailabilitiesRepository;
use App\Repository\BusinessRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BusinessController extends AbstractController
{
    #[Route(path: '/business', name: 'app_all_business')]
    public function getAllBusiness(Request $request, BusinessRepository $businessRepository): Response
    {
        $query = trim((string) $request->query->get('q', ''));
        $query = '' !== $query ? $query : null;

        return $this->render('business/list.html.twig', [
            'businesses' => $businessRepository->search($query),
            'query' => $query,
        ]);
    }

    #[Route(path: '/business/{id}', name: 'app_show_business', requirements: ['id' => '\d+'])]
    public function showBusiness(Business $business, Request $request, AvailabilitiesRepository $availabilitiesRepository): Response
    {
        $from = null;
        if ($request->query->get('from')) {
            $from = \DateTime::createFromFormat('Y-m-d', $request->query->get('from')) ?: null;
        }

        return $this->render('business/show.html.twig', [
            'business' => $business,
            'availabilities' => $availabilitiesRepository->search($business, $from),
            'from' => $from,
        ]);
    }

    #[Route(path: '/business/add', name: 'app_add_business')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createBusiness(Request $request, EntityManagerInterface $entityManager, RoleRepository $roleRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (null !== $user->getBusiness()) {
            $this->addFlash('error', 'Vous avez déjà un commerce.');

            return $this->redirectToRoute('app_home');
        }

        $business = new Business();
        $form = $this->createForm(BusinessFormType::class, $business);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $business->setManager($user);

            // un utilisateur simple devient propriétaire de commerce ; un admin garde son rôle
            if (null === $user->getRole() || 'ROLE_USER' === $user->getRole()->getName()) {
                $user->setRole($roleRepository->findOneBy(['name' => 'ROLE_BUSINESS_OWNER']));
            }

            $entityManager->persist($business);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commerce a été créé.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('business/create.html.twig', [
            'businessForm' => $form,
        ]);
    }

    #[Route(path: '/business/update/{id}', name: 'app_update_business')]
    public function modifyBusiness()
    {
        return null;
    }
}
