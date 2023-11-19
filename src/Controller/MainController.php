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

        // Recupera el objeto de usuario de la sesión
        $usuario = $session->get('usuario');
        $nombre = $usuario ? $usuario->getNombre() : null;

        // Recupera el usuario usando el objeto almacenado en la sesión
        $usuario = $em->getRepository('App\Entity\Usuarios')->findOneBy(['nombre' => $nombre]);

        $usuarioId = $usuario ? $usuario->getId() : null;

        $params = $request->request->all();

        $contenido = null;
        $mensajesTodos = null;
        $fecha = null;
        if ($params && $usuarioId) {
            $mensajes = new Mensajes();
            $contenido = $mensajes->setContenido($params['message']);
            $fechaActual = new \DateTime();
            $fecha = $mensajes->setFecha($fechaActual);

            // Utiliza el objeto de Usuarios almacenado en la sesión
            $mensajeNombre = $mensajes->setNombre($usuario);

            $em->persist($contenido);
            $em->persist($fecha);
            $em->persist($mensajes);
            $em->persist($mensajeNombre);
            $em->flush();
        }
        if ($contenido) {
            $contenido = $mensajes->getContenido();
            $fecha = $mensajes->getFecha();
        }

        // Recupera todos los mensajes para mostrarlos en la vista
        $mensajesTodos = $em->getRepository('App\Entity\Mensajes')->findAll();

        return $this->render('main/chat.html.twig', [
            'usuarioAct' => $nombre,
            'mensajes' => $mensajesTodos
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

            // Guarda el objeto de usuario en la sesión
            $session->set('usuario', $entidad);

            $em->persist($nombre);
            $em->flush();

            return $this->redirectToRoute('app_chat');
        }

        return $this->render('main/login.html.twig');
    }
}
