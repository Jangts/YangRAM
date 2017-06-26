<?php
namespace CM\SPC;
use AF\Models\R3Model_BC;

/**
 *	Special Use Content Custom Tag Model
 */
final class Tag extends R3Model_BC {
	protected static
	$ca_path = '',
    $table = DB_CNT.'map_spctags',
    $indexes = ['id'],
    $aikey = 'id',
    $defaults = [
        'id'				=>	0,
        'tag'				=>	'',
        'set_alias'         =>  '',
        'cnt_id'     		=>	0
    ];

    protected function build($data, $posted = false){
        parent::build($data, $posted);
        $this->readonly = true;
    }

    public static function byId($tag, $set_alias){
        $require = [
            'tag' => $tag
        ];
        if(is_string($set_alias)){
			$require['set_alias'] = $set_alias;
		}
        return self::query($require);
	}

    public static function byContent($cnt_id){
        return self::query(['cnt_id' => $cnt_id]);
	}

    public static function byType($set_alias){
        if(is_numeric($set_alias)){
            if($SET = Preset::id($set_alias)){
                return parent::query("`set_alias` = $SET->ALIAS");
            }
            return [];
        }
        if(is_string($set_alias)){
            return parent::query("`set_alias` = $set_alias");
        }
        return [];
	}

    public static function posttags($tags, $cnt_id, $preset){
        $rdo = self::getRDO();
        $rdo->requiring("cnt_id = '$cnt_id' AND tag NOT IN ('".join("','", $tags)."')")->delete();
        foreach($tags as $tag){
            $rdo->insert([
                'tag'	=>	$tag,
                'set_alias'	=>	$preset,
                'cnt_id'	=>	$cnt_id
            ], true);
        }  
    }
}
