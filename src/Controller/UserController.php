<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('api/client/{idClient}/users', name: 'users', methods: ['GET'])]
    public function getUserList(int $idClient, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        
    }

    #[Route('api/client/{idClient}/users/{id}', name: 'detailUser', methods: ['GET'])]
    public function getOneUser(User $user, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {

    }

    #[Route('api/client/{idClient}/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    public function deleteOneUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        
    }

    #[Route('api/client/{idClient}/users', name: 'createUser', methods: ['POST'])]
    public function createOneUser($idClient, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        
    }
}
