<?php

namespace App\Controller\User;

use App\Entity\Role;
use App\Form\User\UserFormType;
use App\Service\Role\RoleService;
use App\Service\Route\RouteService;
use App\Service\User\UserProfileService;
use App\Form\User\RegistrationType;
use App\Service\User\UserService;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\Security\TokenGenerator;
use App\Service\Security\Mailer;
use App\Service\Security\CaptchaValidator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use App\Entity\User;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends AbstractController
{

    /** @var UserProfileService */
    private $service;

    private $userService;


    public function __construct(
        UserProfileService $service,
        UserService $userService
    ) {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @param UserInterface $user
     * @return Response
     */
    public function profile(UserInterface $user)
    {

        if(!is_object($user->getUserProfile())) {
            $userProfile = $this->service->newUserProfile();
            $this->service->repository->save($userProfile);
            $user->setUserProfile($userProfile);
            $result = $this->userService->saveUser($user, false, false);
        }


        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Edit user
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function edit(UserInterface $user, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {

        $form = $this->createForm(UserProfileFormType::class, $user->getUserProfile());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type = 'success';
            $message = $translator->trans('Succesfully edited');
            $userProfile = $form->getData();

            $result = $this->service->repository->save($userProfile);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Profile not edited');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_userprofile');
        }

        return $this->render('user/edit-profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
