<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Form\SignUpTournamentType;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Repository\FightRepository;
use AppBundle\Repository\SignUpTournamentRepository;
use AppBundle\Util\AgeCategoryConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

        /** @var FightRepository $fightRepository */
        $fightRepository = $em->getRepository('AppBundle:Fight');
        $fights = $fightRepository->fightReadyOrderBy($tournament);

        $form = null;
        $formDelete = null;
        $isUserRegister = null;

        if ($this->get('security.authorization_checker')->isGranted('CAN_TOURNAMENT_ACTON', $tournament)) {
            /** @var User $user */
            $user = $this->getUser();
            $isUserRegister = $signUpTournamentRepository->findUserRegisteredTournaments($user, $tournament);

            $redirectUrl = $this->redirectToRoute("tournament_sign_up", ['id' => $tournament->getId()]);

            $dateDiff = (date_diff($user->getBirthDay(), $tournament->getStart()))->format("%y");

            $weightCategories = $em->getRepository('AppBundle:Ruleset')
                ->findBy([
                    $user->getMaleWithName() => true,
                    AgeCategoryConverter::convert($dateDiff) => true
                ], ['weight' => 'ASC']);

            $isAlreadySignUp = $isUserRegister ?? new SignUpTournament($user, $tournament);

            $form = $this->createForm(SignUpTournamentType::class, $isAlreadySignUp, ['trait_choices' => $weightCategories]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();
                return $redirectUrl;
            }

            $formDelete = $this->createFormBuilder($isUserRegister)->getForm();
            $formDelete->handleRequest($request);

            if ($formDelete->isValid()) {
                $isUserRegister->delete();
                $em->flush();
                return $redirectUrl;
            }
        }

        return $this->render('tournament/sign_up.twig', [
            'form' => (!empty($form)) ?  $form->createView(): null,
            'formDelete' => (!empty($form)) ? $formDelete->createView() : null,
            'tournament' => $tournament,
            'users' => $signUpTournamentRepository->signUpUserOrder($tournament),
            'fights' => $fights,
            'isUserRegister' => $isUserRegister,
            'signUpTournament' => $serializer->normalize($signUpTournament),
        ]);
    }
}
