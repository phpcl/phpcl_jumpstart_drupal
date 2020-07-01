<?php
namespace Drupal\phpcl_test\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
/**
 * Login block
 *
 * @Block(
 *   id = "phpcl_test_login_block",
 *   admin_label = @Translation("Login"),
 *   category = @Translation("phpcl_test")
 * )
 */
class LoginBlock extends BlockBase
{

    /**
    * {@inheritdoc}
    */
    public function build() {
        return \Drupal::formBuilder()
            ->getForm('Drupal\phpcl_test\Form\LoginBlockForm');
    }
    /**
    * {@inheritdoc}
    */
    protected function blockAccess(AccountInterface $account) {
        return AccessResult::allowedIfHasPermission($account, 'access');
    }
    /**
    * {@inheritdoc}
    */
    public function blockForm($form, FormStateInterface $form_state) {
        $form = parent::blockForm($form, $form_state);
        $config = $this->getConfiguration();
        return $form;
    }
    /**
    * {@inheritdoc}
    */
    public function blockSubmit($form, FormStateInterface $form_state) {
        $this->setConfigurationValue(
            'phpcl_test_login_block_settings',
            $form_state->getValue('phpcl_test_login__block_settings')
        );
    }
}
