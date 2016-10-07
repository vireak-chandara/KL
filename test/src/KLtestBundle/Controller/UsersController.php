<?php

namespace KLtestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use KLtestBundle\Entity\User;
use KLtestBundle\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsersController extends Controller
{
  public function indexAction()
  {
    $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('KLtestBundle:User');
    
    $listUsers = $repository->findAll();
    
    return $this->render('KLtestBundle:Users:index.html.twig', array(
      'listUsers' => $listUsers
    ));
  }
  
  public function addAction(Request $request)
  {
      
      //On pourrait récupérer les variables depuis un formulaire
    $em = $this->getDoctrine()->getManager();
    $user = new User();
    $user->setNom('CHAN DARA');
    $user->setPrenom('Vireak');
    $user->setActif(true);
    $user->setDateCreation(new \Datetime());
    $user->addGroup(1);
    $em->persist($user);
    // On déclenche l'enregistrement
    $em->flush();
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'User bien enregistré.');

      // Puis on redirige vers la page de visualisation de cet user
      return $this->redirectToRoute('kl_users_view', array('id' => $user->getId()));
    }
    return $this->redirectToRoute('kl_users');
  }

  public function viewAction($id)
  {
    $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('KLtestBundle:User');
    
    $user = $repository->find($id);
    if (null === $user) {
      throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
    }
    
    return $this->render('KLtestBundle:Users:view.html.twig', array(
      'user' => $user
    ));
  }
  
  public function editAction($id)
  {
   $em = $this->getDoctrine()->getManager();

    $user = $em->getRepository('KLtestBundle:User')->find($id);
    if (null === $user) {
      throw new NotFoundHttpException("L'User d'id ".$id." n'existe pas.");
    }
    
    //On pourrait récupérer les variables depuis un formulaire
    $user->setNom("Nouveau nom");
    $user->setPrenom("Nouveau prenom");
    $user->setEmail("Nouvel email");
    
    $em->flush();
    return $this->redirectToRoute('kl_users_view', array('id' => $user->getId()));
  }

  
}