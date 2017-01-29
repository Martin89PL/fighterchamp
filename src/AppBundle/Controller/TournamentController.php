<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 10.08.16
 * Time: 19:18
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Form\FightType;
use AppBundle\Form\SignUpTournamentType;
use AppBundle\Entity\SignUpTournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



/**
 * @Route("/turniej")
 */
class TournamentController extends Controller
{

//    /**
//     * List all tournaments.
//     *
//     * @Route("/", name="tournament_list")
//     */
//    public function tournamentListAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $tournaments = $em->getRepository('AppBundle:Tournament')
//            ->findAll();
//
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
//
//            $user = $this->getUser();
//
//            $userAdminTournaments = $em->getRepository('AppBundle:UserAdminTournament')
//                ->findBy((['user' => $user]));
//
//            $adminTournaments = [];
//
//            foreach ($userAdminTournaments as $item){
//
//                $adminTournaments [] = $item->getTournament();
//            }
//        }
//
//
//        return $this->render('tournament/list.twig', array(
//            'tournaments' => $tournaments,
//            'adminTournaments'=> $adminTournaments ?? null
//        ));
//    }
//
//    /**
//     * Creates a new tournament entity.
//     *
//     * @Route("/new", name="tournament_new")
//     * @Method({"GET", "POST"})
//     */
//    public function TournamentNewAction(Request $request)
//    {
//        $userAdmin = $this->getUser();
//        $tournament = new Tournament($userAdmin);
//        $form = $this->createForm('AppBundle\Form\TournamentType', $tournament);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($tournament);
//            $em->flush($tournament);
//
//            return $this->redirectToRoute('tournament_show', array('id' => $tournament->getId()));
//        }
//
//        return $this->render('admin/tournament/new.html.twig', array(
//            'tournament' => $tournament,
//            'form' => $form->createView(),
//        ));
//    }
//    /**
//     * Displays a form to edit an existing tournament entity.
//     *
//     * @Route("/{id}/edytuj", name="tournament_edit")
//     * @Method({"GET", "POST"})
//     */
//    public function TournamentEditAction(Request $request, Tournament $tournament)
//    {
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
//
//            $user = $this->getUser();
//            $em = $this->getDoctrine()->getManager();
//
//            $isAdmin = $em->getRepository('AppBundle:UserAdminTournament')
//                ->findOneBy(['tournament' => $tournament, 'user' => $user]);
//
//            if ($isAdmin) {
//
//                $deleteForm = $this->createDeleteForm($tournament);
//                $editForm = $this->createForm('AppBundle\Form\TournamentType', $tournament);
//                $editForm->handleRequest($request);
//
//                if ($editForm->isSubmitted() && $editForm->isValid()) {
//                    $this->getDoctrine()->getManager()->flush();
//
//                    return $this->redirectToRoute('tournament_edit', array('id' => $tournament->getId()));
//                }
//
//
//                return $this->render('tournament/edit.html.twig', array(
//                    'tournament' => $tournament,
//                    'edit_form' => $editForm->createView(),
//                    'delete_form' => $deleteForm->createView(),
//                    'isAdmin' => $isAdmin
//                ));
//            }
//
//        }else{
//            return new AccessDeniedException();
//        }
//    }
//
//
//    /**
//     * Deletes a tournament entity.
//     *
//     * @Route("/{id}", name="tournament_delete")
//     * @Method("DELETE")
//     */
//    public function deleteAction(Request $request, Tournament $tournament)
//    {
//        $form = $this->createDeleteForm($tournament);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($tournament);
//            $em->flush($tournament);
//        }
//
//        return $this->redirectToRoute('tournament_list');
//    }
//
//    /**
//     * Creates a form to delete a tournament entity.
//     *
//     * @param Tournament $tournament The tournament entity
//     *
//     * @return \Symfony\Component\Form\Form The form
//     */
//    private function createDeleteForm(Tournament $tournament)
//    {
//        return $this->createFormBuilder()
//            ->setAction($this->generateUrl('tournament_delete', array('id' => $tournament->getId())))
//            ->setMethod('DELETE')
//            ->getForm()
//            ;
//    }

    /**
     * @Route("/{id}", name="tournament_show")
     */
    public function tournamentShowAction(Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:SignUpTournament')
            ->signUpUserOrder($tournament);

            return $this->render('tournament/show.twig', array(
                'tournament' => $tournament,
                'users' => $users
            ));
    }
}