<?php 
defined('C5_EXECUTE') or die("Access Denied.");
// LOAD FOR PAGE SELECTOR
$pageSelector = Loader::helper('form/page_selector');

// LOAD FOR REDACTOR & FILE SELECTOR
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission(); ?>
<style type="text/css">
    .redactor_editor { padding: 20px; }
</style>

<!-- USE FOR IMAGE SELECTOR -->
<style type="text/css">
.select-image { display: block; padding: 15px; cursor: pointer; background: #dedede; border: 1px solid #cdcdcd; text-align: center; color: #333; vertical-align: center; }
.select-image img { max-width: 100%; }
</style>


<style type="text/css">
    .panel-heading { cursor: move; }
    .panel-body { display: none; }
</style>

<?php  print Loader::helper('concrete/ui')->tabs(array(
    array('pane-items', t('Items'), true),
    array('pane-settings', t('Settings'))
));?>

<div class="ccm-tab-content" id="ccm-tab-content-pane-items">

    <div class="form-group">
        <?php  echo $form->label('carouselTitle',t("Carousel Title")); ?>
        <?php  echo $form->text('carouselTitle',$carouselTitle);?>
    </div>
    
    <div class="well bg-info">
        <?php  echo t('You can rearrange items if needed.'); ?>
    </div>
    
    <div class="items-container">
        
        <!-- DYNAMIC ITEMS WILL GET LOADED INTO HERE -->
        
    </div>  
    
    <span class="btn btn-success btn-add-item"><?php  echo t('Add Item') ?></span> 

</div>

<div class="ccm-tab-content" id="ccm-tab-content-pane-settings">
    
    <div class="row">
        
        <div class="col-xs-4">            
            <div class="form-group">
                <?php  echo $form->label('itemsmobile',t("Items on Mobile")); ?>
                <?php  echo $form->select('itemsmobile', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4'), $itemsmobile?$itemsmobile:'2'); ?>
            </div>            
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?php  echo $form->label('itemstablet',t("Items on Tablet")); ?>
                <?php  echo $form->select('itemstablet', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10'), $itemstablet?$itemstablet:'4'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?php  echo $form->label('itemsdesktop',t("Items on Desktop")); ?>
                <?php  echo $form->select('itemsdesktop', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10'), $itemsdesktop?$itemsdesktop:'6'); ?>
            </div>
        </div>
        
    </div>
    <div class="row">
        
        <div class="col-xs-6">
            <div class="form-group">
                <?php  echo $form->label('thumbwidth',t('Thumb Width')); ?>
                <div class="input-group">
                    <?php  echo $form->text('thumbwidth',$thumbwidth?$thumbwidth:'300'); ?>
                    <div class="input-group-addon">px</div>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <?php  echo $form->label('thumbheight',t('Thumb Height')); ?>
                <div class="input-group">
                    <?php  echo $form->text('thumbheight',$thumbheight?$thumbheight:'200'); ?>
                    <div class="input-group-addon">px</div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>

<!-- THE TEMPLATE WE'LL USE FOR EACH ITEM -->
<script type="text/template" id="item-template">
    <div class="item panel panel-default" data-order="<%=sort%>">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5><i class="fa fa-arrows drag-handle"></i>
                    <?php echo t('Item')?> <%=parseInt(sort)+1%></h5>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="javascript:editItem(<%=sort%>);" class="btn btn-edit-item btn-default"><?php echo t('Edit')?></a>
                    <a href="javascript:deleteItem(<%=sort%>)" class="btn btn-delete-item btn-danger"><?php echo t('Delete')?></a>
                </div>
            </div>
        </div>
        <div class="panel-body form-horizontal">
            <!-- IMAGE SELECTOR --->
            <div class="form-group">
                <label class="col-xs-3 control-label"><?php  echo t('Select Image') ?></label>
                <div class="col-xs-9">
                    <a href="javascript:chooseImage(<%=sort%>);" class="select-image" id="select-image-<%=sort%>">
                        <% if (thumb.length > 0) { %>
                            <img src="<%= thumb %>" />
                        <% } else { %>
                            <i class="fa fa-picture-o"></i>
                        <% } %>
                    </a>
                    <input type="hidden" name="<?php  echo $view->field('fID')?>[]" class="image-fID" value="<%=fID%>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-xs-3 control-label" for="title<%=sort%>"><?php echo t('Title:')?></label>
                <div class="col-xs-9">
                    <input class="form-control" type="text" name="title[]" id="title<%=sort%>" value="<%=title%>">
                </div>
            </div>
            
            <!-- REDACTOR --->
            <div class="form-group">
                <label class="col-xs-3 control-label" for="carcontent<%=sort%>"><?php echo t('Put in some Content:')?></label>
                <div class="col-xs-9">
                    <textarea class="redactor-content" name="carcontent[]" id="carcontent<%=sort%>"><%=carcontent%></textarea>
                </div>
            </div>
            
            
            <!-- PAGE SELECTOR --->
            <div class="form-group">
                <label class="col-xs-3 control-label"><?php echo t('Select a Page')?></label>
                <div class="col-xs-9" id="select-page-<%=sort%>">
                    <?php  $this->inc('elements/page_selector.php');?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-xs-3 control-label" for="btntext<%=sort%>"><?php echo t('Button Text:')?></label>
                <div class="col-xs-9">
                    <input class="form-control" type="text" name="btntext[]" id="btntext<%=sort%>" value="<%=btntext%>">
                </div>
            </div>
                        
            <input class="item-sort" type="hidden" name="<?php  echo $view->field('sort')?>[]" value="<%=sort%>"/>
            
        </div>
    </div><!-- .item -->
</script>


<script type="text/javascript">

//Edit Button
var editItem = function(i){
    $(".item[data-order='"+i+"']").find(".panel-body").toggle();
};
//Delete Button
var deleteItem = function(i) {
    var confirmDelete = confirm('<?php  echo t('Are you sure?') ?>');
    if(confirmDelete == true) {
        $(".item[data-order='"+i+"']").remove();
        indexItems();
    }
};
//Choose Image
var chooseImage = function(i){
    var imgShell = $('#select-image-'+i);
    ConcreteFileManager.launchDialog(function (data) {
        ConcreteFileManager.getFileDetails(data.fID, function(r) {
            jQuery.fn.dialog.hideLoader();
            var file = r.files[0];
            imgShell.html(file.resultsThumbnailImg);
            imgShell.next('.image-fID').val(file.fID)
        });
    });
};

//Index our Items
function indexItems(){
    $('.items-container .item').each(function(i) {
        $(this).find('.item-sort').val(i);
        $(this).attr("data-order",i);
    });
};

$(function(){
    
    //DEFINE VARS
    
        //use when using Redactor (wysiwyg)
        var CCM_EDITOR_SECURITY_TOKEN = "<?php  echo Loader::helper('validation/token')->generate('editor')?>";
        
        //Define container and items
        var itemsContainer = $('.items-container');
        var itemTemplate = _.template($('#item-template').html());
    
    //BASIC FUNCTIONS
    
        //Make items sortable. If we re-sort them, re-index them.
        $(".items-container").sortable({
            handle: ".panel-heading",
            update: function(){
                indexItems();
            }
        });
    
    //LOAD UP OUR ITEMS
        
        //for each Item, apply the template.
        <?php  
        if($items) {
            foreach ($items as $item) { 
        ?>
        itemsContainer.append(itemTemplate({
            //define variables to pass to the template.
            
            //IMAGE SELECTOR
            fID: '<?php  echo $item['fID'] ?>',
            <?php  if($item['fID']) { ?>
            thumb: '<?php  echo File::getByID($item['fID'])->getThumbnailURL('file_manager_listing');?>',
            <?php  } else { ?>
            thumb: '',
            <?php  } ?>
            
            title: '<?php  echo addslashes($item['title']) ?>',
                        
            //REDACTOR
            carcontent: '<?php  echo str_replace(array("\t", "\r", "\n"), "", addslashes($item['carcontent']))?>',
            
                        
            //PAGE SELECTOR
            <?php  if($item['pageID']){
                $page = Page::getByID($item['pageID']);
                $pageName = $page->getCollectionName();
            }
            ?>
            pageID: '<?php echo $item['pageID']?>',
            pageName: '<?php echo $pageName?>',
            
            btntext: '<?php  echo addslashes($item['btntext']) ?>',            
            sort: '<?php echo $item['sort'] ?>'
        }));
        <?php  
            }
        }
        ?>    
        
        //Init Index
        indexItems();

        //Init Redactor
        $('.redactor-content').redactor({
            minHeight: '200',
            'concrete5': {
                filemanager: <?php  echo $fp->canAccessFileManager()?>,
                sitemap: <?php  echo $tp->canAccessSitemap()?>,
                lightbox: true
            }
        });
        
    //CREATE NEW ITEM
        
        $('.btn-add-item').click(function(){
            
            //Use the template to create a new item.
            var temp = $(".items-container .item").length;
            temp = (temp);
            itemsContainer.append(itemTemplate({
                //vars to pass to the template
                //IMAGE SELECTOR
                fID: '',
                thumb: '',
                
                title: '',
                
                //REDACTOR
                carcontent: '',                
                
                //PAGE SELECTOR
                pageID: '',
                pageName: '',
                
                btntext: '',
                sort: temp
            }));
            
            var thisModal = $(this).closest('.ui-dialog-content');
            var newItem = $('.items-container .item').last();
            thisModal.scrollTop(newItem.offset().top);
            
            //Init Redactor
            newItem.find('.redactor-content').redactor({
                minHeight: '100',
                'concrete5': {
                    filemanager: <?php  echo $fp->canAccessFileManager()?>,
                    sitemap: <?php  echo $tp->canAccessSitemap()?>,
                    lightbox: true
                }
            });
            
            //Init Index
            indexItems();
        });    

});
</script>