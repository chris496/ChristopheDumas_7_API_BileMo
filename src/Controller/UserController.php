<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('api/client/{idClient}/users', name: 'users', methods: ['GET'])]
    public function getUserList(int $idClient, UserRepository $userRepository, ClientRepository $clientRepository, SerializerInterface $serializer): JsonResponse
    {
        $userList = $userRepository->findClient($idClient);
        $jsonUserList = $serializer->serialize($userList, 'json', ['groups' => 'getUsers']);
        
        return new JsonResponse($jsonUserList, Response::HTTP_OK, [], true);
    }

    #[Route('api/client/{idClient}/users/{id}', name: 'detailUser', methods: ['GET'])]
    public function getOneUser(int $idClient, int $id, User $user, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $oneUser = $userRepository->findUser($idClient, $id);
        $jsonUser = $serializer->serialize($oneUser, 'json', ['groups' => 'getUsers']);
        
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('api/client/{idClient}/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    public function deleteOneUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('api/client/{idClient}/users', name: 'createUser', methods: ['POST'])]
    public function createOneUser($idClient, Request $request, ClientRepository $clientRepository, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $content = $request->toArray();
        $idClient = $content['idClient'];
        $user->setClient($clientRepository->find($idclient));
        
        $em->persist($user);
        $em->flush();

        $oneUser = $userRepository->findUser($idClient, $id);
        $jsonUser = $serializer->serialize($oneUser, 'json', ['groups' => 'getUsers']);
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
        
    }
}
