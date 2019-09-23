<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use AppBundle\Entity\UserCoach;
use AppBundle\Event\Events;
use AppBundle\Event\UserCreatedEvent;
use AppBundle\Event\UserEvent;
use AppBundle\Form\User\CoachType;
use AppBundle\Form\User\UserType;
use AppBundle\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\User\FighterType;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api"))
 */
class UserController extends Controller
{
    /**
     * @Route("/ludzie/{id}", name="api_user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        return $user;
    }


    /**
     * @Route("/user-create", name="user_create")
     * @Method("POST")
     */
    public function createAction(Request $request, EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher,
                                 LoginFormAuthenticator $loginFormAuthenticator, GuardAuthenticatorHandler $guardAuthenticatorHandler)
    {
        $form = $this->createForm($this->getFormType($request), null, [
            'method' => 'POST',
            'action' => $this->generateUrl('user_create')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var $user User
             */
            $user = $form->getData();
            $coachId = $form->get('coachId')->getData();

            if ($coachId) {
                $coach = $em->getRepository(User::class)
                    ->find($coachId);
                $userCoach = new UserCoach($user, $coach);
                $em->persist($userCoach);
            }


            $user->setHash(hash('sha256', md5(rand())));

            $em->persist($user);
            $em->flush();

            $eventDispatcher->dispatch(
                Events::USER_REGISTERED,
                new UserCreatedEvent($user)
            );

            $this->addFlash('success', 'Sukces! Twój profil został utworzony! Jesteś zalogowany!');
            $this->addFlash('danger',
                "Na twój email {$user->getEmail()} został wysłany link który musisz kliknąć aby twoje konto było aktywne");

            $guardAuthenticatorHandler
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $loginFormAuthenticator,
                    'main'
                );

            return new JsonResponse(
                ['location' => $this->generateUrl('user_show', ['id' => $user->getId()])], 200);
        }

        return new JsonResponse(
            [
                'form' => $this->renderView($this->getFormTypeView($request),
                    [
                        'form' => $form->createView(),
                    ])], 400);
    }


    /**
     * @Route("/user-update", name="api_user_update")
     */
    public function updateAction(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        $form = $this->createForm($this->getFormType($request), $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var $user User
             */
            $user = $form->getData();

            if ($form->has('coachId')) {
                $coachId = $form->get('coachId')->getData();
                if ($coachId) {
                    $coach = $em->getRepository(User::class)
                        ->find($coachId);
                    $userCoach = new UserCoach($user, $coach);
                    $em->persist($userCoach);
                }
            }

            if ($coach = $user->getCoaches()->first()) {
                $userCoachOld = $em->getRepository(UserCoach::class)
                    ->findOneBy(['coach' => $coach, 'fighter' => $user]);
                $em->remove($userCoachOld);
            }

            $em->flush();

            $this->addFlash('success', 'Sukces! Zmiany na twoim profilu zostały zapisane!!');

            return new JsonResponse(null, 200);
        }


        return new JsonResponse(
            [
                'form' => $this->renderView($this->getFormTypeView($request),
                    [
                        'form' => $form->createView(),
                    ])], 400);
    }

    /**
     * @Route("/ludzie", name="api_user_list")
     * @Method("GET")
     */
    public function listAction(Request $request, EntityManagerInterface $em)
    {
        $type = $request->query->get('type', 1);

        if ($type == 1) {
            return $em->getRepository(User::class)->findAllFighters();
        }

        return  $em->getRepository(User::class)->findAllListAction($type);
    }

    /**
     * @Route("/ludzie/{id}", name="api_user_delete")
     * @Method("DELETE")
     */
    public function delete(User $user)
    {

    }


    private function getFormType(Request $request): string
    {
        $data = $request->request->all();
        $type = $data['fighter']['type'] ?? $data['coach']['type'] ?? $data['user']['type'];


        switch ($type) {
            case '1':
                return FighterType::class;
            case '2':
                return CoachType::class;
            case '3':
                return UserType::class;
            default:
                return 'Nie ma takiego typu';
        }
    }

    private function getFormTypeView(Request $request): string
    {
        $data = $request->request->all();
        $type = $data['fighter']['type'] ?? $data['coach']['type'] ?? $data['user']['type'];

        switch ($type) {
            case '1':
            case '2':
                return 'user/fighter/_form.html.twig';
            case '3':
                return 'user/fan/_form.html.twig';
            default:
                return 'Nie ma takiego typu';
        }
    }
}