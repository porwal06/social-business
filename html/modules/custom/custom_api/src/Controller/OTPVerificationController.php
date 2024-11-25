<?php

namespace Drupal\custom_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\user\Entity\User;
use Drupal\user\UserDataInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OTPVerificationController extends ControllerBase {

  /**
   * @var \Drupal\user\UserDataInterface
   */
  protected $userData;

  /**
   * Constructs the controller with dependencies.
   *
   * @param \Drupal\user\UserDataInterface $user_data
   *   The user data service.
   */
  public function __construct(UserDataInterface $user_data) {
    $this->userData = $user_data;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('user.data')
    );
  }

  /**
   * Verifies OTP for user registration and activates the account.
   */
  public function verifyOtp(Request $request) {
    $data = json_decode($request->getContent(), TRUE);

    // Validate request parameters.
    if (empty($data['email']) || empty($data['otp'])) {
      return new JsonResponse(['error' => 'Email and OTP are required'], 400);
    }

    // Load the user by email.
    $users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties(['mail' => $data['email']]);
    if (empty($users)) {
      return new JsonResponse(['error' => 'Invalid email address'], 400);
    }

    $user = reset($users);
    $uid = $user->id();

    // Retrieve OTP and expiration time using UserDataInterface.
    $stored_otp = $this->userData->get('otp', $uid, 'otp_user_register_random_otp');
    $otp_time = $this->userData->get('otp', $uid, 'otp_user_register_random_otp_time');

    if (empty($stored_otp) || empty($otp_time)) {
      return new JsonResponse(['error' => 'OTP not found for the user.'], 400);
    }

    // Validate OTP expiration.
    if ($otp_time == NULL || $otp_time < (time() - 24 * 60 * 60)) {
      return new JsonResponse(['error' => t('The activation code you entered has expired. Please resend verification code again.')], 400);
    }

    // Validate OTP (stored as md5).
    if (md5($data['otp']) !== $stored_otp) {
      return new JsonResponse(['error' => t('You entered an incorrect activation code.')], 400);
    }

    // Activate the user account.
    if (!$user->isActive()) {
      $user->activate();
      $user->save();
    }

    // Log the user in (optional).
    user_login_finalize($user);

    // Clean up OTP data using UserDataInterface.
    $this->userData->delete('otp', $uid, 'otp_user_register_random_otp');
    $this->userData->delete('otp', $uid, 'otp_user_register_random_otp_time');

    return new JsonResponse([
      'message' => 'OTP verified successfully. User is now logged in.',
      'user_id' => $uid,
      'csrf_token' => \Drupal::service('csrf_token')->get('rest'),
    ], 200);
  }

  /**
   * Resend OTP to the user.
   */
  public function resendOtp(Request $request) {
    $data = json_decode($request->getContent(), TRUE);

    // Validate request parameters.
    if (empty($data['email'])) {
      return new JsonResponse(['error' => 'Email is required'], 400);
    }
    // Load the user by email.
    $users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties(['mail' => $data['email']]);
    if (empty($users)) {
      return new JsonResponse(['error' => 'Invalid email address'], 400);
    }
    $user = reset($users);

    $otp_send = _otp_generate_otp($user, TRUE, TRUE); //keep last param as False if no OTP want to send in WS
    if (is_array($otp_send) && $otp_send['email_send']) {
      $message = t("A new verification code has been sent to @email and OTP @otp", ['@email' => $data['email'], '@otp' => $otp_send['otp']]);
      return new JsonResponse([
        'message' => $message       
      ], 200);
    }
    elseif (!$otp_send) {
      return new JsonResponse(['error' => "You've reached the maximum number of attempts. Please retry after few hours or contact support."], 400);
    }
    else {
      return new JsonResponse(['error' => 'Some error occurred in sending OTP'], 500);
    }


    if ($result['result'] !== TRUE) {
      return new JsonResponse(['error' => 'Failed to send OTP email.'], 500);
    }

    return new JsonResponse(['message' => 'OTP resent successfully.']);
  }
}
