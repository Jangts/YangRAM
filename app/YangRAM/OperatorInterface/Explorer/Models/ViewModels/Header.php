<?php
namespace Explorer\Models\ViewModels;
use Tangram\NIDO\DataObject;
use Application;
use CM\SPC\Preset;
use CM\SRC\Folder;

class Header extends DataObject {
    public function __construct($localdict, $uriarr, $length, $datatype, $itemtype, $folder = ''){
        $readonly = $this->readonly($uriarr, $length, $datatype, $folder);
        $newFolder = $this->newFolder($localdict, $readonly['mau']);
        $selects = $this->selects($localdict, $readonly['mau'], $readonly['opt']);
        $uploader = $this->uploader($localdict, $readonly['mau']);
        $viewType = $this->viewType($localdict, $readonly['swt']);
        $orderBy = $this->orderBy($localdict, $readonly['sot'], $readonly['bsz']);
        $tools = array($newFolder, $selects, $uploader, $viewType, $orderBy);
        $dir = $this->dir($localdict, $uriarr, $length, $datatype, $itemtype, $folder);
        $this->data = array($tools, $dir);
    }

    private function readonly($uriarr, $length, $datatype, $folder){
        $readOnlySorts = false;
		$readOnlyOrderBySize = false;
        $readOnlyViewTypeSwitch = false;
		$readOnlyMoveAndUpload = false;
		$readOnlyOperate = false;
        if($datatype=='sch'){
			$readOnlySorts = true;
			$readOnlyOrderBySize = true;
			$readOnlyMoveAndUpload = true;
			$readOnlyOperate = true;
        }elseif($length>3){
			if($length<5){
				$readOnlySorts = true;
                $readOnlyViewTypeSwitch = true;
			}else{
				$readOnlySorts = false;
			}
			if($datatype=='spc'){
				$readOnlyOrderBySize = true;
			}else{
				$readOnlyOrderBySize = $readOnlySorts;
			}
			if($datatype=='spc'||$length<6){
				$readOnlyMoveAndUpload = true;
				if($datatype=='spc'&&$length==7){
					$readOnlyOperate = false;
				}else{
					$readOnlyOperate = true;
				}
			}elseif($datatype=='src'&&$folder<6){
				$readOnlyMoveAndUpload = true;
				$readOnlyOperate = true;
			}else{
				$readOnlyMoveAndUpload = false;
				$readOnlyOperate = false;
			}  
        }
        return array(
            'sot' =>  $readOnlySorts,
            'swt' =>  $readOnlyViewTypeSwitch,
            'bsz'  =>  $readOnlyOrderBySize,
            'mau'  =>  $readOnlyMoveAndUpload,
            'opt'  =>  $readOnlyOperate
        );
    }

    private function newFolder($localdict, $readOnlyMoveAndUpload){
        return array(
            'classname' =>  'tools-left new-folder',
            'clicks'     =>  array(
                array(
                    'href'      =>  'trigger://EXPLORER::CreateFolder',
                    'readonly'  =>  $readOnlyMoveAndUpload,
                    'title'   =>  $localdict->titles['nf'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'new-folder-icon'
                        ),
                        'el'    =>  array(
                            'classname' =>  'new-folder-title',
                            'text'      =>  $localdict->cfld,
                        )
                    )
                )
            )
        );
    }

    private function selects($localdict, $readOnlyMoveAndUpload, $readOnlyOperate){
        return array(
            'classname' =>  'tools-left selects',
            'clicks'     =>  array(
                array(
                    'href'      =>  'trigger://EXPLORER::MoveSelected',
                    'readonly'  =>  $readOnlyMoveAndUpload,
                    'title'   =>  $localdict->titles['ms'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'move-selects'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::DeleteSelected',
                    'readonly'  =>  $readOnlyOperate,
                    'title'   =>  $localdict->titles['rs'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'dele-selects'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::CopyHTMLCodes',
                    'readonly'  =>  $readOnlyOperate,
                    'title'   =>  $localdict->titles['hc'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'html-codes'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::CopyJSONCodes',
                    'readonly'  =>  $readOnlyOperate,
                    'title'   =>  $localdict->titles['jc'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'json-codes'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::SelectAll',
                    'readonly'  =>  $readOnlyOperate,
                    'title'   =>  $localdict->titles['sa'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'select-all'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::DeselectAll',
                    'readonly'  =>  $readOnlyOperate,
                    'title'   =>  $localdict->titles['sn'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'un-select-all'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::InvertSelection',
                    'readonly'  =>  $readOnlyOperate,
                    'title'   =>  $localdict->titles['si'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'in-selects'
                        )
                    )
                )
            )
        );
    }

    private function uploader($localdict, $readOnlyMoveAndUpload){
        return array(
            'classname' =>  'tools-right upload-btn',
            'clicks'     =>  array(
                array(
                    'href'      =>  'trigger://EXPLORER::Uploader',
                    'readonly'  =>  $readOnlyMoveAndUpload,
                    'title'   =>  $localdict->titles['uf'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'upload-btn-icon'
                        ),
                        'el'    =>  array(
                            'classname' =>  'upload-btn-title',
                            'text'      =>  $localdict->ubtn,
                        )
                    )
                )
            )
        );
    }

    private function viewType($localdict, $readOnlyViewTypeSwitch){
        return array(
            'classname' =>  'tools-right view-mode',
            'clicks'     =>  array(
                array(
                    'href'      =>  'trigger://EXPLORER::SwitchMainContentViewType',
                    'readonly'  =>  $readOnlyViewTypeSwitch,
                    'title'   =>  $localdict->titles['tv'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'view-mode-icon'
                        ),
                        'el'    =>  array(
                            'classname' =>  'view-mode-title',
                            'text'      =>  $localdict->view,
                        )
                    )
                )
            )
        );
    }

    private function orderBy($localdict, $readOnlySorts, $readOnlyOrderBySize){
        $isSelected = array(
			'na'	=>	false,
			'nd'	=>	false,
			'ta'	=>	false,
			'td'	=>	false,
			'sa'	=>	false,
			'sd'	=>	false
		);
		if(isset($_GET["lo"])){
			$isSelected[$_GET["lo"]] = true;
		}
        return array(            
            'classname' =>  'tools-right order-by',
            'clicks'     =>  array(
                array(
                    'href'      =>  'trigger://EXPLORER::OrderByNameASC',
                    'readonly'  =>  $readOnlySorts,
                    'title'   =>  $localdict->titles['na'],
                    'selected'    =>  $isSelected['na'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'name-asc'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::OrderByNameDESC',
                    'readonly'  =>  $readOnlySorts,
                    'title'   =>  $localdict->titles['nd'],
                    'selected'    =>  $isSelected['nd'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'name-desc'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::OrderByModTimeASC',
                    'readonly'  =>  $readOnlySorts,
                    'title'   =>  $localdict->titles['ma'],
                    'selected'    =>  $isSelected['ta'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'time-asc'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::OrderByModTimeDESC',
                    'readonly'  =>  $readOnlySorts,
                    'title'   =>  $localdict->titles['md'],
                    'selected'    =>  $isSelected['td'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'time-desc'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::OrderByFileSizeASC',
                    'readonly'  =>  $readOnlyOrderBySize,
                    'title'   =>  $localdict->titles['fa'],
                    'selected'    =>  $isSelected['sa'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'size-asc'
                        )
                    )
                ),
                array(
                    'href'      =>  'trigger://EXPLORER::OrderByFileSizeDESC',
                    'readonly'  =>  $readOnlyOrderBySize,
                    'title'   =>  $localdict->titles['fd'],
                    'selected'    =>  $isSelected['sd'],
                    'body'      =>  array(
                        'ico'   =>  array(
                            'classname' =>  'size-desc'
                        )
                    )
                )
            )
        );
    }
	
	private function dir($localdict, $uriarr, $length, $datatype, $itemtype, $folder){
        $dir = array(
            array(
                'href'  =>  'default/',
                'text'  =>  $localdict->root
            )
        );
		if($uriarr&&$length>3&&$datatype!='default'){
			$href = $datatype.'/';
            $dir[] = array(
                'href'  =>  $href,
                'text'  =>  $localdict->$datatype
            );
			switch($datatype){
				case 'spc':
				if($length>5){
					$href .= 'preset/'.$itemtype.'/';
                    $result = Preset::id($itemtype)->toArray();
					if($result){
                        $dir[] = array(
                            'href'  =>  $href,
                            'text'  =>  $result["name"]
                        );
						for($i = 6; $i < $length; $i++){
                            $dir[] = array(
                                'href'  =>  $href.$uriarr[$i].'/',
                                'text'  =>  $uriarr[$i]
                            );
						}
					}
				}
				break;
				case 'src':
				if($length>4&&isset($localdict->$itemtype)){
					$path = $datatype.'/'.$itemtype.'/';
					$href = $path;
                    $dir[] = array(
                        'href'  =>  $href,
                        'text'  =>  $localdict->$itemtype
                    );
					if($length>5){
                        $curr = Folder::identity($folder);
                        $array = $curr->getAncestors();
                        foreach($array as $item){
                            $href = $path.$item->id.'/';
                            $dir[] = array(
                                'href'  =>  $href,
                                'text'  =>  ($item->id<7) ? $localdict->folders[$item->id] : $item->name
                            );
						}
					}
				}
				break;
			}
		}
        return $dir;
	}

    public function render(){
        $body = '<vision class="tools">';
        $tools = $this->renderTools();
        $body .= join('', $tools);
        $body .= '</vision><vision class="dir">';
        $dir = $this->renderDir();
        $body .= join('<click>/</click>', $dir);
        $body .= '</vision>';
        return $body;
    }

    public function renderTools(){
        $array = [];
        foreach($this->data[0] as $group){
            $body = '<vision class="'.$group['classname'].'">';
            foreach($group['clicks'] as $click){
                $body .= '<click href="'.$click['href'].'"';
                if($click['readonly']){
                    $body .= ' readonly="true"';
                }
                if(isset($click['selected'])&&$click['selected']){
                    $body .= ' selected="true"';
                }
                $body .= ' title="'.$click['title'].'">';
                $body .= '<ico class="'.$click['body']['ico']['classname'].'"></ico>';
                if(isset($click['body']['el'])){
                    $body .= '<el class="'.$click['body']['el']['classname'].'">'.$click['body']['el']['text'].'</el>';
                }
                $body .= '</click>';
            }
            $body .= '</vision>';
            $array[] = $body;
        }
        return $array;
    }

    public function renderDir(){
        $array = [];
        foreach($this->data[1] as $click){
            $array[] = '<click href="'.$click['href'].'">'.$click['text'].'</click>';
        }
        return $array;
    }
}
