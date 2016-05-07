<?php       

namespace Concrete\Package\VividCarousel;
use Package;
use BlockType;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{

	protected $pkgHandle = 'vivid_carousel';
	protected $appVersionRequired = '5.7.1';
	protected $pkgVersion = '1.0.1';
	
	
	
	public function getPackageDescription()
	{
		return t("Add a Carousel to your Site");
	}

	public function getPackageName()
	{
		return t("Vivid Carousel");
	}
	
	public function install()
	{
		$pkg = parent::install();
        BlockType::installBlockTypeFromPackage('vivid_carousel', $pkg); 
        
	}
}
?>