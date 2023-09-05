<?php
namespace Drupal\custom_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\Markup;

class CustomFormController extends ControllerBase {

  protected $configFactory;

  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

	/**
	 * Display the About Us page with configuration data.
	 */
	public function aboutUsPage() {

		$config = $this->configFactory->get('custom_form.settings');
		$num_groups = $config->get('num_groups');

		$content = [];

		for ($i = 0; $i <= $num_groups; $i++) {
			$group = [
				'leaderName' => $config->get("Leader_".($i+1) . '_name'),
				'designation' => $config->get("Leader_".($i+1) . '_designation'),
				'linkedinLink' => $config->get("Leader_".($i+1). '_linkedin_link'),
				//'profileImage' => $config->get("Leader_".($i+1). '_profile_image'),
			];
			$content[] = $group;
		}

		$anchor_ref = $config->get('anchor_reference');
		$anchorReference = '';

		if (!empty($anchor_ref)) {
			$anchorUser = \Drupal\user\Entity\User::load($anchor_ref);
			$anchorReference = $anchorUser->getAccountName();
			$field_description = $anchorUser->get('field_description')->value;
		}

		return [
			'#theme' => 'custom_form_data',
			'#content' => $content,
			'#anchorReference' => $anchorReference,
			'#description' => $field_description,
		];
	}
}

