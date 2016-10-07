<?php

namespace KLtestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use KLtestBundle\Entity\User;
use KLtestBundle\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GroupsController extends Controller
{
  public function indexAction()
  {
    $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('KLtestBundle:Group');
    
    $listUsers = $repository->findAll();
    
    return $this->render('KLtestBundle:Groups:index.html.twig', array(
      'listUsers' => $listUsers
    ));
  }

  public function addAction(Request $request)
  {
     //On pourrait récupérer les variables depuis un formulaire
    $em = $this->getDoctrine()->getManager();
    $group = new Group();
    $group->setNom("Un nouveau groupe");
    $em->persist($group);
    // On déclenche l'enregistrement
    $em->flush();
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Groupe bien enregistré.');

      // Puis on redirige vers la page de visualisation de ce group
      return $this->redirectToRoute('kl_groups_view', array('id' => $group->getId()));
    }
    return $this->redirectToRoute('kl_groups');
    
  }
  
  public function viewAction($id)
  {
    $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('KLtestBundle:Group');
    
    $group = $repository->find($id);
    if (null === $group) {
      throw new NotFoundHttpException("Le groupe d'id ".$id." n'existe pas.");
    }
    
    return $this->render('KLtestBundle:Groups:view.html.twig', array(
      'group' => $group
    ));
  }
  
  public function editAction($id)
  {
   $em = $this->getDoctrine()->getManager();

    $group = $em->getRepository('KLtestBundle:Group')->find($id);
    if (null === $group) {
      throw new NotFoundHttpException("Le groupe d'id ".$id." n'existe pas.");
    }
    //On pourrait récupérer les nouvelles variables depuis un formulaire
    $group->setNom("Un nouveau nom");
    //On ajoute un utilisateur au groupe
    $group->addUser(1);
    
    $em->flush();
    
    return $this->redirectToRoute('kl_groups_view', array('id' => $group->getId()));
  }
  
  public function deleteAction($idGroup, $idUser)
  {
      //Permet de supprimer un user du group
     $em = $this->getDoctrine()->getManager();
    // On récupère le groupe $id
    $group = $em->getRepository('KLtestBundle:Group')->find($idGroup);
    $user = $em->getRepository('KLtestBundle:User')->find($idUser);
    if (null === $group) {
      throw new NotFoundHttpException("Le groupe d'id ".$id." n'existe pas.");
    }
    if (null === $user) {
      throw new NotFoundHttpException("L'user d'id ".$id." n'existe pas.");
    }
    $group->removeUser($user);
    $em->flush();
    
    return $this->redirectToRoute('kl_groups_view', array('id' => $group->getId()));
  }

  
}