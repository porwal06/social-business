<?php

namespace Drupal\custom_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\user\Entity\User;
use Drupal\profile\Entity\Profile;

class UserRegistrationController extends ControllerBase {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function create($container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function register(Request $request) {
    $data = json_decode($request->getContent(), TRUE);

    // Validate required fields.
    if (empty($data['email']) || empty($data['password']) || empty($data['first_name']) || empty($data['last_name'])) {
      return new JsonResponse(['error' => 'Missing required fields'], 400);
    }

    // Check if the user already exists.
    $existing_users = $this->entityTypeManager->getStorage('user')->loadByProperties(['mail' => $data['email']]);
    if (!empty($existing_users)) {
      return new JsonResponse(['error' => 'User already exists with this email'], 400);
    }

    // Create the user.
    $user = User::create();
    $user->setEmail($data['email']);
    $user->setUsername($data['email']); // Use email as username.
    $user->setPassword($data['password']);
    // $user->activate(); // Deactivate by default for OTP verification.
    $user->enforceIsNew();
    $user->save();

    // Create the profile.
    $profile = Profile::create([
      'type' => 'profile', // Adjust to the machine name of your profile type.
      'uid' => $user->id(),
    ]);
    $profile->set('field_profile_first_name', $data['first_name']);
    $profile->set('field_profile_last_name', $data['last_name']);
    if (!empty($data['field_profile_image'])) {
      $profile->set('field_profile_image', $data['field_profile_image']);
    }
    $profile->save();

    // Trigger OTP verification.
    $otp_send = _otp_generate_otp($user->id(), TRUE);
    if ($otp_send) {
      $message = t("A new verification code has been sent to @email", ['@email' => $data['email']]);
    }
    else {
      $message = t("You account has been registered but OTP generation reached the maximum number of attempts. Please contact to admin.");
    }

    return new JsonResponse([
      'message' => $message,
      'user_id' => $user->id(),
    ], 201);
  }
}
