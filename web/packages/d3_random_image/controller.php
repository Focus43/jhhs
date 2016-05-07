<?php  
namespace Concrete\Package\D3RandomImage;

use Package;
use BlockType;

/**
 * @author akodde
 */
class Controller extends Package 
{
	protected $pkgHandle = 'd3_random_image';
	protected $appVersionRequired = '5.7.1';
	protected $pkgVersion = '1.0';
	
	public function getPackageName() 
	{
		return t("Random Image"); 
	}	
	
	public function getPackageDescription() 
	{
		return t("Display a random image from a file set");
	}
	
	public function install() 
	{
		$pkg = parent::install();
		
		BlockType::installBlockTypeFromPackage('d3_random_image', $pkg);
	}
	
	public function uninstall() 
	{
		parent::uninstall();
		
		$db = \Database::get();
		$db->Execute('DROP TABLE IF EXISTS btD3RandomImage');
	}
}