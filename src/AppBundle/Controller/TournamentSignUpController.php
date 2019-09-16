<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ruleset;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Form\SignUpTournamentType;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Repository\FightRepository;
use AppBundle\Repository\SignUpTournamentRepository;
use AppBundle\Util\AgeCategoryConverter;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/turnieje")
 */
class TournamentSignUpController extends Controller
{
    /**
     * @Route("/{id}/zapisy", name="tournament_sign_up")
     */
    public function signUpAction(Tournament $tournament, SerializerInterface $serializer, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var SignUpTournamentRepository $signUpTournamentRepository */
        $signUpTournamentRepository = $em->getRepository('AppBundle:SignUpTournament');

        $signUpTournament = $signUpTournamentRepository->findAllSortByMaleClassWeightSurname($tournament);
        $users = $signUpTournamentRepository->signUpUserOrder($tournament);

        /** @var FightRepository $fightRepository */
        $fightRepository = $em->getRepository('AppBundle:Fight');

        $fights = $fightRepository->fightReadyOrderBy($tournament);


        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') && ($this->getUser())->getType() != 3) {
            /** @var User $user */
            $user = $this->getUser();
            $isUserRegister = $signUpTournamentRepository
                ->findOneBy([
                    'user' => $user->getId(),
                    'tournament' => $tournament,
                    'deleted_at' => null
                ]);

            $birthDay = $user->getBirthDay();
            $tournamentDay = $tournament->getStart();

            $date_diff = date_diff($birthDay, $tournamentDay);
            $date_diff = $date_diff->format("%y");

            $traitChoices = $em->getRepository('AppBundle:Ruleset')
                ->findBy([
                    $user->getMaleWithName() => true,
                    AgeCategoryConverter::convert($date_diff) => true
                ], ['weight' => 'ASC']);

            $isAlreadySignUp = $isUserRegister ?? new SignUpTournament($user, $tournament);

            $form = $this->createForm(SignUpTournamentType::class, $isAlreadySignUp, ['trait_choices' => $traitChoices]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $signUpTournament = $form->getData();
                $em->persist($signUpTournament);
                $em->flush();
                return $this->redirectToRoute("tournament_sign_up", ['id' => $tournament->getId()]);
            }

            $formDelete = $this->createFormBuilder($isUserRegister)->getForm();
            $formDelete->handleRequest($request);

            if ($formDelete->isValid()) {
                $isUserRegister->delete();
                $em->flush();
                return $this->redirectToRoute("tournament_sign_up", ['id' => $tournament->getId()]);
            }


            return $this->render('tournament/sign_up.twig', array(
                'form' => $form->createView(),
                'formDelete' => $formDelete->createView(),
                'tournament' => $tournament,
                'users' => $users,
                'date_diff' => $date_diff,
                'isUserRegister' => $isUserRegister,
                'fights' => $fights,
                'signUpTournament' => $serializer->normalize($signUpTournament),
            ));

        }

        return $this->render('tournament/sign_up.twig', array(
            'tournament' => $tournament,
            'users' => $users,
            'fights' => $fights,
            'isUserRegister' => null,
            'signUpTournament' => $serializer->normalize($signUpTournament),
        ));

    }
}