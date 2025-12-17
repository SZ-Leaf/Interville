<?php
// src/Service/UserRegistrationService.php
namespace App\Services\User;

use App\DTO\User\RegisterUserRequest;
use App\Factory\User\UserFactory;
use App\Exception\User\RegistrationException;
use App\Repository\PromoRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserRegistrationService
{
   public function __construct(
      private readonly UserRepository $userRepository,
      private readonly PromoRepository $promoRepository,
      private readonly RoleRepository $roleRepository,
      private readonly UserPasswordHasherInterface $hasher,
      private readonly UserFactory $userFactory,
      private readonly ValidatorInterface $validator,
      private readonly EntityManagerInterface $em,
   ) {}

   /**
    * @throws RegistrationException 
    */
   public function register(RegisterUserRequest $dto): array
   {
      // 1. DTO validation (NotBlank, Email, etc)
      $violations = $this->validator->validate($dto);
      if (count($violations) > 0) {
         $errs = [];
         foreach ($violations as $v) {
               $errs[] = sprintf('%s: %s', $v->getPropertyPath(), $v->getMessage());
         }
         throw new RegistrationException(implode('; ', $errs));
      }

      // 2. Promo presence check
      $city = $dto->getPromoCity();
      $year = $dto->getPromoYear();
      if (empty($city) || empty($year)) {
         throw RegistrationException::missingPromo();
      }

      // 3. Uniqueness checks
      if ($this->userRepository->findOneBy(['email' => $dto->email])) {
         throw RegistrationException::emailExists();
      }

      if ($this->userRepository->findOneBy(['username' => $dto->username])) {
         throw RegistrationException::usernameExists();
      }

      // 4. Find promo
      $promo = $this->promoRepository->findOneBy([
         'city' => $city,
         'year' => $year,
      ]);
      if (!$promo) {
         throw RegistrationException::invalidPromo();
      }

      // 5. Find role
      $role = $this->roleRepository->findOneBy(['title' => 'user']);
      if (!$role) {
         throw new RegistrationException('Default role not found.');
      }

      // 6. Hash password
      $dummyUserForHasher = new \App\Entity\User(); // hasher requires a UserInterface instance
      $passwordHash = $this->hasher->hashPassword($dummyUserForHasher, $dto->password);

      // 7. Build user entity
      $user = $this->userFactory->createFromData(
         $dto->email,
         $dto->firstName,
         $dto->lastName,
         $dto->username,
         $passwordHash,
         $promo,
         $role
      );

      // 8. Persist
      $this->em->persist($user);
      $this->em->flush();

      // Return relevant data
      return [
         'email' => $user->getEmail(),
         'id' => $user->getId(),
      ];
   }
}
