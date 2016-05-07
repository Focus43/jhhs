<?php    
namespace Concrete\Package\D3RandomImage\Block\D3RandomImage;

use Package;
use FileList;
use FileSet;
use Core;
use \Concrete\Core\Block\BlockController;

class Controller extends BlockController 
{
	protected $btTable = 'btD3RandomImage';
	protected $btInterfaceWidth = "400";
	protected $btInterfaceHeight = "300";
	protected $btDefaultSet = "multimedia";
	
	public function getBlockTypeName() 
	{
		$p = Package::getByHandle('d3_random_image');
		return $p->getPackageName();
	}

	public function getBlockTypeDescription() 
	{
		$p = Package::getByHandle('d3_random_image');
		return $p->getPackageDescription();
	}
	
	
	public function view()
	{
		try {
			$fv = $this->getRandomImage($this->fsID);
			$imagePath = $this->getImagePath($fv);
			
			$this->set('fv', $fv);
			$this->set('imagePath', $imagePath);
		} catch(\Exception $e) {
			$this->set('error', $e->getMessage());
		}
	}
	
	public function edit()
	{
		$this->set('max_width', (empty($this->max_width) ? '' : $this->max_width));
		$this->set('max_height', (empty($this->max_height) ? '' : $this->max_height));
	}
	
	public function save($args) 
	{
		$args['do_crop'] = ($args['do_crop']) ? true : false;
		
		// Due to a bug in the core, we can't save null values yet...
		$args['max_width'] = (!empty($args['max_width'])) ? $args['max_width'] : 0;
		$args['max_height'] = (!empty($args['max_height'])) ? $args['max_height'] : 0;
		
		parent::save($args);
	}
	
	/**
	 * @param int $fsID
	 * @return \FileVersion | Exception
	 */
	public function getRandomImage($fsID)
	{
		if (!$fsID) {
			throw new \Exception(t("No file set has been selected"));
		}
		
		$fs = FileSet::getByID($fsID);
		if (!$fs) {
			throw new \Exception(t("File set doesn't exist (anymore)"));
		}
		
		$list = new FileList();
		$list->filterBySet($fs);
		$list->setItemsPerPage(1);
		$list->sortBy('RAND()');
		$files = $list->get();
		
		if (!$files OR !is_array($files) OR !$files[0]) {
			throw new \Exception(t("File set is empty"));
		}
		
		$fv = $files[0]->getRecentVersion();
		
		if (!$fv) {
			throw new \Exception(t("File version not found"));
		}
		
		return $fv;
	}
	
	/**
	 * @param \FileVersion $fv
	 * @return string
	 */
	public function getImagePath($fv)
	{
		if (!empty($this->max_width) OR !empty($this->max_height)) {
			$max_width  = empty($this->max_width) ? 9999 : $this->max_width;
			$max_height = empty($this->max_height) ? 9999 : $this->max_height;
			
			$ih = Core::make('helper/image');
			$thumb = $ih->getThumbnail($fv->getFile(), $max_width, $max_height, $this->do_crop);
			return $thumb->src;
		} else {
			return $fv->getRelativePath();
		}
	}
	
	
	/**
	 * @return array FileSetID, FileSetName
	 **/
	public function getFileSetsOptions()
	{
        $options = array("" => t("None"));

		$sets = FileSet::getMySets();
		
		if ($sets) {
	        foreach($sets as $set){
	            $options[$set->getFileSetID()] = $set->getFileSetName();
	        }
		}
		
		return $options;
	}
}
