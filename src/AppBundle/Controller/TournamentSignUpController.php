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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/turnieje")
 */
class TournamentSignUpController extends Controller
{
    /**
     * @Route("/{id}/admin", name="tournament_sign_up_test")
     */
    public function testAction()
    {
        return new Response(
            '<html><body>Lucky number: </body></html>'
        );
    }

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

        $form = null;
        $formDelete = null;
        $isUserRegister = null;

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

            $weightCategories = $em->getRepository('AppBundle:Ruleset')
                ->findBy([
                    $user->getMaleWithName() => true,
                    AgeCategoryConverter::convert($date_diff) => true
                ], ['weight' => 'ASC']);

            $isAlreadySignUp = $isUserRegister ?? new SignUpTournament($user, $tournament);

            $form = $this->createForm(SignUpTournamentType::class, $isAlreadySignUp, ['trait_choices' => $weightCategories]);
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
        }

        return $this->render('tournament/sign_up.twig', array(
            'form' => (!empty($form)) ?  $form->createView(): null,
            'formDelete' => (!empty($form)) ? $formDelete->createView() : null,
            'tournament' => $tournament,
            'users' => $users,
            'fights' => $fights,
            'isUserRegister' => $isUserRegister,
            'signUpTournament' => $serializer->normalize($signUpTournament),
        ));

    }
}