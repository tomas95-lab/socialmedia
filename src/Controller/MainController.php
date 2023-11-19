<?php

namespace App\Controller;

use App\Entity\Usuarios;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(EntityManagerInterface $em)
    {
        $entidad = $em->getRepository('App\Entity\Usuarios')->findBy(['nombre' => 'tomas']);
        return $this->render('main/index.html.twig', [
            'usuarios' => $entidad,
        ]);
    }
    #[Route('/main/formulario', name: 'app_formulario')]
    public function formulario(EntityManagerInterface $em)
    {
        // Ejecuta la persistencia
        return $this->redirectToRoute('app_login');
    }
}
