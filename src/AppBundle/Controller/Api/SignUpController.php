<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Fight;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignUpController extends Controller
{
    public function showAction(SignUpTournament $signUp)
    {
        return $signUp;
    }

    public function newAction(Request $request, EntityManagerInterface $entityManager)
    {
        $userId = $request->request->get('userId');
        $formula = $request->request->get('formula');
        $weight = $request->request->get('weight');
        $tournamentId = $request->request->get('tournamentId');

        $user = $entityManager->getReference(User::class, $userId);
        $tournament = $entityManager->getReference(Tournament::class, $tournamentId);

        $signUpTournament = new SignUpTournament($user, $tournament);
        $signUpTournament->setFormula($formula);
        $signUpTournament->setWeight($weight);

        $entityManager->persist($signUpTournament);
        $entityManager->flush();

        return new Response();
    }

    public function list(Tournament $tournament, EntityManagerInterface $entityManager)
    {
       return $entityManager->getRepository(SignUpTournament::class)
            ->findBy(['tournament' => $tournament]);
    }
}
