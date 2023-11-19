<?php

namespace App\Controller;

use App\Entity\Mensajes;
use App\Entity\Usuarios;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/index', name: 'app_chat')]
    public function index(EntityManagerInterface $em, Request $request)
    {
        $session = $request->getSession();
        $nombre = $session->get('nombre');
        $usuario = $em->getRepository('App\Entity\Usuarios')->findOneBy(['nombre' => $nombre]);

        $usuarioId = $usuario ? $usuario->getId() : null;

        if ($usuarioId !== null) {
            echo "<pre>";
            var_dump($usuarioId);
            die();
        }
        $params = $request->request->all();

        $contenido = null;
        $fecha = null;
        if ($params && $usuarioId) {
            $mensajes = new Mensajes();
            $contenido = $mensajes->setContenido($params['message']);
            $fechaActual = new \DateTime();
            $fecha = $mensajes->setFecha($fechaActual);

            // Utiliza la entidad completa de Usuarios
            $mensajes->setNombre($usuario);

            $em->persist($contenido);
            $em->persist($fecha);
            $em->persist($mensajes);
            $em->flush();
        }
        if ($contenido) {
            $contenido = $mensajes->getContenido();
            $fecha = $mensajes->getFecha();
        }
        return $this->render('main/chat.html.twig', [
            'nombre' => $nombre,
            'contenido' => $contenido,
            'fecha' => $fecha,
        ]);
    }
    #[Route('/login', name: 'app_formulario')]
    public function formulario(EntityManagerInterface $em, Request $request, SessionInterface $session)
    {
        $params = $request->request->all();
        $nombre = null;
        $entidad = null;
        if (!(empty($params))) {
            $entidad = new Usuarios;
            $nombre = $entidad->setNombre($params['nombre']);
            $em->persist($nombre);
            $em->flush();
            $session->set('nombre', $entidad->getNombre());

            return $this->redirectToRoute('app_chat');
        }

        return $this->render('main/login.html.twig');
    }
}
