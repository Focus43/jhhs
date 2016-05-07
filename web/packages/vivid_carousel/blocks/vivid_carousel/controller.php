<?php 
namespace Concrete\Package\VividCarousel\Block\VividCarousel;
use \Concrete\Core\Block\BlockController;
use Loader;
use Page;
defined('C5_EXECUTE') or die("Access Denied.");
class Controller extends BlockController
{
    protected $btTable = 'btVividCarousel';
    protected $btInterfaceWidth = "700";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "465";

    public function getBlockTypeDescription()
    {
        return t("Add a Carousel Content to your Site");
    }

    public function getBlockTypeName()
    {
        return t("Vivid Carousel");
    }

    public function add()
    {
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
    }

    public function edit()
    {
        $this->requireAsset('redactor'); 
        $this->requireAsset('core/file-manager'); 
        $this->requireAsset('core/sitemap');  
        
        $db = Loader::db();
        $items = $db->GetAll('SELECT * from btVividCarouselItem WHERE bID = ? ORDER BY sort', array($this->bID));
        $this->set('items', $items);
    }

    public function view()
    {
        $db = Loader::db();
        $items = $db->GetAll('SELECT * from btVividCarouselItem WHERE bID = ? ORDER BY sort', array($this->bID));
        $this->set('items', $items);
    }

    public function duplicate($newBID) {
        parent::duplicate($newBID);
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btVividCarouselItem where bID = ?';
        $r = $db->query($q, $v);
        while ($row = $r->FetchRow()) {
            if(empty($args['pageID'][$i])){$args['pageID'][$i]=0;}
            if(empty($args['fID'][$i])){$args['fID'][$i]=0;}
            $vals = array($newBID,$row['fID'][$i],$row['title'][$i],$row['carcontent'][$i],$row['pageID'][$i],$row['btntext'][$i],$row['sort'][$i]);  
            $db->execute('INSERT INTO btVividCarouselItem (bID, fID, title, carcontent, pageID, btntext, sort) values(?,?,?,?,?,?,?)', $vals);
        }
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('btVividCarouselItem', array('bID' => $this->bID));
        parent::delete();
    }

    public function save($args)
    {
        $db = Loader::db();
        $db->execute('DELETE from btVividCarouselItem WHERE bID = ?', array($this->bID));
        $count = count($args['sort']);
        $i = 0;
        parent::save($args);
        while ($i < $count) {
            if(empty($args['pageID'][$i])){$args['pageID'][$i]=0;}
            if(empty($args['fID'][$i])){$args['fID'][$i]=0;}
            $vals = array($this->bID,$args['fID'][$i],$args['title'][$i],$args['carcontent'][$i],$args['pageID'][$i],$args['btntext'][$i],$args['sort'][$i]);     
            $db->execute('INSERT INTO btVividCarouselItem (bID, fID, title, carcontent, pageID, btntext, sort) values(?,?,?,?,?,?,?)', $vals);
            $i++;
        }
    }
    
    public function validate($args)
    {
        $e = Loader::helper('validation/error');
        if(empty($args['thumbwidth'])){
            $e->add(t("Thumb Width must be set"));
        }
        if(!ctype_digit(trim($args['thumbwidth']))){
            $e->add(t("Thumb Width must be solely numeric"));
        }
        if(empty($args['thumbheight'])){
            $e->add(t("Thumb Width must be set"));
        }
        if(!ctype_digit(trim($args['thumbheight']))){
            $e->add(t("Thumb Width must be solely numeric"));
        }
        if(strlen($args['carouselTitle'])>255){
            $e->add(t("Sorry, but the characters in the carousel title cannot exceed 255 characters"));
        }
        $count = count($args['sort']);
        for($i=0;$i<$count;$i++){
            if(strlen($args['title'][$i])>255){
                $e->add(t('The title in item %s is too long. Reduce it to 255 characters or less', $i+1));    
            }
            if(strlen($args['btntext'][$i])>255){
                $e->add(t('The button text in item %s is too long. Reduce it to 255 characters or less', $i+1));    
            }
        }
        
        return $e;
    }
    

}