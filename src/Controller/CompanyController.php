<?php

namespace App\Controller;

use App\DataTable\CompanyTableType;
use App\Entity\Company;
use App\Entity\CompanyContact;
use App\Entity\User;
use App\Form\CompanyContactType;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/companies')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'app_company')]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $table = $dataTableFactory->createFromType(CompanyTableType::class, ['user' => $user])->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('company/index.html.twig', [
            'datatable' => $table,
        ]);
    }

    #[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $company = new Company();
        $company->setUserProfile($user->getUserProfile());

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($company);
            $entityManager->flush();
            $this->addFlash('success', 'flashes.new');

            return $this->redirectToRoute('app_company_contact_new', ['id' => $company->getId()]);
        }

        return $this->render('company/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}/new-contact', name: 'app_company_contact_new', methods: ['GET', 'POST'])]
    public function contactNew(Company $company, Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new CompanyContact();
        $contact->setCompany($company);

        $form = $this->createForm(CompanyContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Only one main contact by company
            if ($contact->isMain()) {
                foreach ($company->getContacts() as $c) {
                    $c->setMain(false);
                    $entityManager->persist($c);
                }
            }
            if (!$contact->isMain() && $company->getContacts()->isEmpty()) {
                $contact->setMain(true);
            }

            $entityManager->persist($contact);
            $entityManager->flush();
            $this->addFlash('success', 'flashes.new');

            return $this->redirectToRoute('app_company');
        }

        return $this->render('company/contact_new.html.twig', [
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }
}