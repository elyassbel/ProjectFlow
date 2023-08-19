<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/settings')]
class SettingController extends AbstractController
{
    #[Route('/', name: 'app_setting')]
    public function index(): Response
    {
        return $this->render('setting/index.html.twig', [
            'controller_name' => 'SettingController',
        ]);
    }

    #[Route('/email', name: 'app_setting_email')]
    public function email(): Response
    {
        return $this->render('setting/email.html.twig', [
            'controller_name' => 'SettingController',
        ]);
    }

    #[Route('/security', name: 'app_setting_security')]
    public function security(): Response
    {
        return $this->render('setting/security.html.twig', [
            'controller_name' => 'SettingController',
        ]);
    }

    #[Route('/preferences', name: 'app_setting_preferences')]
    public function preferences(): Response
    {
        return $this->render('setting/preferences.html.twig', [
            'controller_name' => 'SettingController',
        ]);
    }
}
